<?php

namespace App\Livewire\Admin;

use App\Models\Lab;
use App\Models\SoilSample;
use App\Models\User;
use App\Models\Location;
use App\Exports\LabReportExport;
use App\Exports\ZoneReportExport;
use App\Exports\FieldOfficialReportExport;
use App\Exports\TechnicianReportExport;
use App\Exports\SelfSampleReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends Component
{
    use AuthorizesRequests;

    public string $activeTab = 'summary';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $labFilter = '';
    public bool $isAdmin = true;
    public ?int $userLabId = null;
    protected array $labs = [];
    protected array $summaryData = [];
    protected $labReportData;
    protected $zoneReportData;
    protected $fieldOfficialReportData;
    protected $technicianReportData;
    protected array $monthlyLabels = [];
    protected array $monthlyCounts = [];
    protected $selfSampleSummary = [];
    protected $selfSampleLabData;
    protected $selfSampleCollectors;

    protected function getDateFrom()
    {
        return $this->dateFrom ?: now()->subYear()->startOfDay()->format('Y-m-d');
    }

    protected function getDateTo()
    {
        return $this->dateTo ?: now()->endOfDay()->format('Y-m-d');
    }

    public function mount()
    {
        $user = auth()->user();
        $this->isAdmin = $user->hasRole('Admin');
        if (!$this->isAdmin) {
            $this->userLabId = $user->lab_id;
        }
        $this->labs = Lab::orderBy('name')->pluck('name', 'id')->toArray();
        $this->loadAllData();
    }

    public function updatedActiveTab() { $this->loadAllData(); }
    public function updatedDateFrom() { $this->loadAllData(); }
    public function updatedDateTo() { $this->loadAllData(); }
    public function updatedLabFilter() { $this->loadAllData(); }

    public function loadAllData()
    {
        if ($this->dateFrom && $this->dateTo && $this->dateFrom > $this->dateTo) {
            [$this->dateFrom, $this->dateTo] = [$this->dateTo, $this->dateFrom];
        }
        $this->labs = Lab::orderBy('name')->pluck('name', 'id')->toArray();
        $this->loadSummary();
        $this->loadLabReport();
        $this->loadZoneReport();
        $this->loadFieldOfficialReport();
        $this->loadTechnicianReport();
        $this->loadSelfSampleReport();
    }

    protected function baseQuery()
    {
        $q = SoilSample::query();
        if ($this->userLabId) {
            $q->where('lab_id', $this->userLabId);
        } elseif ($this->labFilter) {
            $q->where('lab_id', $this->labFilter);
        }
        return $q;
    }

    protected function dateFilteredQuery($column = 'created_at')
    {
        return $this->baseQuery()
            ->where("$column", '>=', $this->getDateFrom())
            ->where("$column", '<=', $this->getDateTo() . ' 23:59:59');
    }

    public function loadSummary()
    {
        $from = $this->getDateFrom();
        $to = $this->getDateTo();

        $all = $this->dateFilteredQuery('created_at');
        $total = (clone $all)->count();
        $collected = (clone $all)->where('status', 'collected')->count();
        $received = (clone $all)->where('status', 'received')->count();
        $testing = (clone $all)->where('status', 'testing')->count();
        $reported = (clone $all)->where('status', 'reported')->count();
        $field = (clone $all)->where('source', 'field')->count();
        $self = (clone $all)->where('source', 'self')->count();

        $queue = SoilSample::query()
            ->when($this->userLabId, fn($q) => $q->where('lab_id', $this->userLabId))
            ->when($this->labFilter, fn($q) => $q->where('lab_id', $this->labFilter))
            ->whereIn('status', ['collected', 'received'])
            ->count();

        $this->summaryData = compact('total', 'collected', 'received', 'testing', 'reported', 'queue', 'field', 'self');

        $this->monthlyLabels = [];
        $this->monthlyCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $this->monthlyLabels[] = $month->format('M Y');
            $q = SoilSample::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month);
            if ($this->userLabId) {
                $q->where('lab_id', $this->userLabId);
            } elseif ($this->labFilter) {
                $q->where('lab_id', $this->labFilter);
            }
            $this->monthlyCounts[] = $q->count();
        }
    }

    public function loadLabReport()
    {
        $from = $this->getDateFrom();
        $to = $this->getDateTo() . ' 23:59:59';
        $q = Lab::withCount([
            'soilSamples' => fn($sq) => $sq->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as collected_count' => fn($sq) => $sq->where('status', 'collected')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as received_count' => fn($sq) => $sq->where('status', 'received')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as testing_count' => fn($sq) => $sq->where('status', 'testing')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as reported_count' => fn($sq) => $sq->where('status', 'reported')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as self_samples_count' => fn($sq) => $sq->where('source', 'self')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'farmers',
            'users',
        ]);
        if ($this->userLabId) {
            $q->where('id', $this->userLabId);
        } elseif ($this->labFilter) {
            $q->where('id', $this->labFilter);
        }
        $this->labReportData = $q->orderBy('soil_samples_count', 'desc')->get();
    }

    public function loadZoneReport()
    {
        $from = $this->getDateFrom();
        $to = $this->getDateTo() . ' 23:59:59';
        $zones = Location::where('type', 'zone')
            ->withCount(['fieldOfficials'])
            ->when($this->userLabId || $this->labFilter, function ($q) {
                $labId = $this->userLabId ?: $this->labFilter;
                $q->whereIn('id', function ($sub) use ($labId) {
                    $sub->select('zone_id')->from('farmers')
                        ->where('lab_id', $labId)
                        ->whereNotNull('zone_id');
                });
            })
            ->get()->keyBy('id');

        $stats = SoilSample::selectRaw('farmers.zone_id, soil_samples.status, soil_samples.source, COUNT(*) as total')
            ->join('farmers', 'soil_samples.farmer_id', '=', 'farmers.id')
            ->whereIn('farmers.zone_id', $zones->keys())
            ->where('soil_samples.created_at', '>=', $from)
            ->where('soil_samples.created_at', '<=', $to)
            ->groupBy('farmers.zone_id', 'soil_samples.status', 'soil_samples.source')
            ->get()
            ->groupBy('zone_id');

        $this->zoneReportData = $zones->map(function ($zone) use ($stats) {
            $s = $stats->get($zone->id, collect());
            $zone->samples_count = $s->sum('total');
            $zone->collected_count = $s->where('status', 'collected')->sum('total');
            $zone->received_count = $s->where('status', 'received')->sum('total');
            $zone->testing_count = $s->where('status', 'testing')->sum('total');
            $zone->reported_count = $s->where('status', 'reported')->sum('total');
            $zone->self_samples_count = $s->where('source', 'self')->sum('total');
            return $zone;
        })->sortByDesc('samples_count')->values();
    }

    public function loadFieldOfficialReport()
    {
        $from = $this->getDateFrom();
        $to = $this->getDateTo();

        $q = User::has('collectedSamples');
        if ($this->userLabId) {
            $q->where('lab_id', $this->userLabId);
        }
        $users = $q->with('zone')->get();

        $this->fieldOfficialReportData = $users->map(function ($user) use ($from, $to) {
            $samples = SoilSample::where('collected_by', $user->id)
                ->where('collected_at', '>=', $from)
                ->where('collected_at', '<=', $to . ' 23:59:59');
            $all = (clone $samples)->count();
            $user->total_collected = $all;
            $user->collected_count = (clone $samples)->where('status', 'collected')->count();
            $user->received_count = (clone $samples)->where('status', 'received')->count();
            $user->testing_count = (clone $samples)->where('status', 'testing')->count();
            $user->reported_count = (clone $samples)->where('status', 'reported')->count();
            $user->last_collection = (clone $samples)->max('collected_at');
            return $user;
        })->sortByDesc('total_collected')->values();
    }

    public function loadTechnicianReport()
    {
        $from = $this->getDateFrom();
        $to = $this->getDateTo();

        $q = User::has('analyzedSamples');
        if ($this->userLabId) {
            $q->where('lab_id', $this->userLabId);
        }
        $users = $q->with('lab')->get();

        $this->technicianReportData = $users->map(function ($user) use ($from, $to) {
            $samples = SoilSample::where('analyzed_by', $user->id)
                ->where('analyzed_at', '>=', $from)
                ->where('analyzed_at', '<=', $to . ' 23:59:59');
            $all = (clone $samples)->count();
            $user->total_analyzed = $all;
            $user->received_count = (clone $samples)->where('status', 'received')->count();
            $user->testing_count = (clone $samples)->where('status', 'testing')->count();
            $user->reported_count = (clone $samples)->where('status', 'reported')->count();
            $user->last_analysis = (clone $samples)->max('analyzed_at');
            return $user;
        })->sortByDesc('total_analyzed')->values();
    }

    public function loadSelfSampleReport()
    {
        $from = $this->getDateFrom();
        $to = $this->getDateTo() . ' 23:59:59';

        $all = $this->dateFilteredQuery('created_at')->where('source', 'self');
        $total = (clone $all)->count();
        $received = (clone $all)->where('status', 'received')->count();
        $testing = (clone $all)->where('status', 'testing')->count();
        $reported = (clone $all)->where('status', 'reported')->count();

        $this->selfSampleSummary = compact('total', 'received', 'testing', 'reported');

        $this->selfSampleLabData = Lab::withCount([
            'soilSamples as self_samples_count' => fn($q) => $q->where('source', 'self')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as self_received_count' => fn($q) => $q->where('source', 'self')->where('status', 'received')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as self_testing_count' => fn($q) => $q->where('source', 'self')->where('status', 'testing')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
            'soilSamples as self_reported_count' => fn($q) => $q->where('source', 'self')->where('status', 'reported')->where('created_at', '>=', $from)->where('created_at', '<=', $to),
        ])
            ->when($this->userLabId, fn($q) => $q->where('id', $this->userLabId))
            ->when($this->labFilter, fn($q) => $q->where('id', $this->labFilter))
            ->orderBy('self_samples_count', 'desc')
            ->get();

        $collectors = User::whereHas('collectedSamples', fn($q) => $q->where('source', 'self')->where('collected_at', '>=', $from)->where('collected_at', '<=', $to))
            ->when($this->userLabId, fn($q) => $q->where('lab_id', $this->userLabId))
            ->with('lab')
            ->get();

        $this->selfSampleCollectors = $collectors->map(function ($user) use ($from, $to) {
            $samples = SoilSample::where('collected_by', $user->id)
                ->where('source', 'self')
                ->where('collected_at', '>=', $from)
                ->where('collected_at', '<=', $to);
            $user->self_samples_count = (clone $samples)->count();
            $user->last_collection = (clone $samples)->max('collected_at');
            return $user;
        })->filter(fn($u) => $u->self_samples_count > 0)->sortByDesc('self_samples_count')->values();
    }

    public function exportSelfSampleExcel()
    {
        $this->loadAllData();
        return Excel::download(
            new SelfSampleReportExport($this->userLabId ?: $this->labFilter, $this->getDateFrom(), $this->getDateTo()),
            'self-sample-report.xlsx'
        );
    }

    public function exportSelfSamplePdf()
    {
        $this->loadAllData();
        $data = $this->selfSampleLabData;
        $dateFrom = \Carbon\Carbon::parse($this->getDateFrom())->format('d/m/Y');
        $dateTo = \Carbon\Carbon::parse($this->getDateTo())->format('d/m/Y');
        $pdf = Pdf::loadView('pdf.reports.self-sample-report', compact('data', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');
        $output = $pdf->output();
        return response()->streamDownload(fn () => print($output), 'self-sample-report.pdf');
    }

    public function exportLabExcel()
    {
        $this->loadAllData();
        return Excel::download(
            new LabReportExport($this->userLabId ?: $this->labFilter, $this->getDateFrom(), $this->getDateTo()),
            'lab-report.xlsx'
        );
    }

    public function exportZoneExcel()
    {
        $this->loadAllData();
        return Excel::download(
            new ZoneReportExport($this->userLabId ?: $this->labFilter, $this->getDateFrom(), $this->getDateTo()),
            'zone-report.xlsx'
        );
    }

    public function exportFieldOfficialExcel()
    {
        $this->loadAllData();
        return Excel::download(
            new FieldOfficialReportExport($this->userLabId ?: $this->labFilter, $this->getDateFrom(), $this->getDateTo()),
            'field-official-report.xlsx'
        );
    }

    public function exportTechnicianExcel()
    {
        $this->loadAllData();
        return Excel::download(
            new TechnicianReportExport($this->userLabId ?: $this->labFilter, $this->getDateFrom(), $this->getDateTo()),
            'technician-report.xlsx'
        );
    }

    public function exportLabPdf()
    {
        $this->loadAllData();
        $data = $this->labReportData;
        $dateFrom = \Carbon\Carbon::parse($this->getDateFrom())->format('d/m/Y');
        $dateTo = \Carbon\Carbon::parse($this->getDateTo())->format('d/m/Y');
        $pdf = Pdf::loadView('pdf.reports.lab-report', compact('data', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');
        $output = $pdf->output();
        return response()->streamDownload(fn () => print($output), 'lab-report.pdf');
    }

    public function exportZonePdf()
    {
        $this->loadAllData();
        $data = $this->zoneReportData;
        $dateFrom = \Carbon\Carbon::parse($this->getDateFrom())->format('d/m/Y');
        $dateTo = \Carbon\Carbon::parse($this->getDateTo())->format('d/m/Y');
        $pdf = Pdf::loadView('pdf.reports.zone-report', compact('data', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');
        $output = $pdf->output();
        return response()->streamDownload(fn () => print($output), 'zone-report.pdf');
    }

    public function exportFieldOfficialPdf()
    {
        $this->loadAllData();
        $data = $this->fieldOfficialReportData;
        $dateFrom = \Carbon\Carbon::parse($this->getDateFrom())->format('d/m/Y');
        $dateTo = \Carbon\Carbon::parse($this->getDateTo())->format('d/m/Y');
        $pdf = Pdf::loadView('pdf.reports.field-official-report', compact('data', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');
        $output = $pdf->output();
        return response()->streamDownload(fn () => print($output), 'field-official-report.pdf');
    }

    public function exportTechnicianPdf()
    {
        $this->loadAllData();
        $data = $this->technicianReportData;
        $dateFrom = \Carbon\Carbon::parse($this->getDateFrom())->format('d/m/Y');
        $dateTo = \Carbon\Carbon::parse($this->getDateTo())->format('d/m/Y');
        $pdf = Pdf::loadView('pdf.reports.technician-report', compact('data', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');
        $output = $pdf->output();
        return response()->streamDownload(fn () => print($output), 'technician-report.pdf');
    }

    public function render()
    {
        return view('livewire.admin.reports', [
            'labs' => $this->labs,
            'summaryData' => $this->summaryData,
            'labReportData' => $this->labReportData,
            'zoneReportData' => $this->zoneReportData,
            'fieldOfficialReportData' => $this->fieldOfficialReportData,
            'technicianReportData' => $this->technicianReportData,
            'monthlyLabels' => $this->monthlyLabels,
            'monthlyCounts' => $this->monthlyCounts,
            'selfSampleSummary' => $this->selfSampleSummary,
            'selfSampleLabData' => $this->selfSampleLabData,
            'selfSampleCollectors' => $this->selfSampleCollectors,
        ])->layout('layouts.app');
    }
}
