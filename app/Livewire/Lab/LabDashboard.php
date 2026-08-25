<?php

namespace App\Livewire\Lab;

use App\Models\SoilSample;
use App\Models\SampleStatusHistory;
use App\Models\Target;
use App\Models\Farmer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LabDashboard extends Component
{
    use AuthorizesRequests;

    public int $todayReceived = 0;
    public int $collectedCount = 0;
    public int $pendingTesting = 0;
    public int $completedThisWeek = 0;
    public int $totalReported = 0;
    public int $overdueCount = 0;
    public $zoneTargets;
    public int $totalTargetCount = 0;
    public int $totalAchievedCount = 0;

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFilter = '';

    public $collectedSamples;
    public $pendingSamples;
    public $recentCompleted;
    public $searchResults;

    protected function labQuery()
    {
        $query = SoilSample::query();
        $labId = auth()->user()->lab_id;
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', $labId);
        }
        return $query;
    }

    public function mount()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->loadStats();
        $this->loadData();
    }

    protected function loadStats()
    {
        $this->todayReceived = (clone $this->labQuery())
            ->where('status', 'received')
            ->whereDate('received_at', today())
            ->count();

        $this->collectedCount = (clone $this->labQuery())
            ->where('status', 'collected')
            ->count();

        $this->pendingTesting = (clone $this->labQuery())
            ->where('status', 'received')
            ->count();

        $this->completedThisWeek = (clone $this->labQuery())
            ->where('status', 'reported')
            ->whereBetween('analyzed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $this->totalReported = (clone $this->labQuery())
            ->where('status', 'reported')
            ->count();

        $labId = auth()->user()->lab_id;
        if ($labId) {
            $zoneIds = Farmer::where('lab_id', $labId)->whereNotNull('zone_id')->distinct()->pluck('zone_id');
            $this->zoneTargets = Target::with('zone')->active()->whereIn('zone_id', $zoneIds)->orderBy('zone_id')->get();
        } else {
            $this->zoneTargets = collect();
        }
        $overdueDays = (int) setting('overdue_days', 2);
        $this->overdueCount = (clone $this->labQuery())
            ->where('status', 'received')
            ->where(function ($q) use ($overdueDays) {
                $q->whereNull('received_at')
                  ->orWhere('received_at', '<', now()->subDays($overdueDays));
            })
            ->count();

        $this->totalTargetCount = $this->zoneTargets->sum('target_count');
        $this->totalAchievedCount = $this->zoneTargets->sum(fn($t) => $t->achievedCount());
    }

    protected function loadData()
    {
        $this->collectedSamples = (clone $this->labQuery())
            ->with(['farmer'])
            ->where('status', 'collected')
            ->latest('collected_at')
            ->take(10)
            ->get();

        $this->pendingSamples = (clone $this->labQuery())
            ->with(['farmer'])
            ->where('status', 'received')
            ->latest('received_at')
            ->take(10)
            ->get();

        $this->recentCompleted = (clone $this->labQuery())
            ->with(['farmer'])
            ->where('status', 'reported')
            ->latest('analyzed_at')
            ->take(5)
            ->get();

        $this->searchResults = collect();
    }

    public function updatedSearch()
    {
        $this->searchSamples();
    }

    public function updatedStatusFilter()
    {
        $this->searchSamples();
    }

    public function updatedDateFilter()
    {
        $this->searchSamples();
    }

    protected function searchSamples()
    {
        $query = (clone $this->labQuery())
            ->with(['farmer', 'plot', 'lab']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('sample_code', 'like', "%{$this->search}%")
                  ->orWhereHas('farmer', fn($f) => $f->where('name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFilter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->dateFilter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateFilter === 'month') {
            $query->whereMonth('created_at', now()->month);
        }

        $this->searchResults = $query->latest()->take(20)->get();
    }

    public function markReceived($sampleId)
    {
        $sample = SoilSample::findOrFail($sampleId);
        $oldStatus = $sample->status->value;
        $sample->update([
            'status' => 'received',
            'received_at' => now(),
        ]);

        SampleStatusHistory::create([
            'soil_sample_id' => $sample->id,
            'from_status' => $oldStatus,
            'to_status' => 'received',
            'changed_by' => auth()->id(),
        ]);

        $this->loadStats();
        $this->loadData();
        session()->flash('success', "Sample {$sample->sample_code} marked as received.");
    }

    public function receiveAll()
    {
        $samples = (clone $this->labQuery())
            ->where('status', 'collected')
            ->get();

        foreach ($samples as $sample) {
            $oldStatus = $sample->status->value;
            $sample->update([
                'status' => 'received',
                'received_at' => now(),
            ]);
            SampleStatusHistory::create([
                'soil_sample_id' => $sample->id,
                'from_status' => $oldStatus,
                'to_status' => 'received',
                'changed_by' => auth()->id(),
            ]);
        }

        $this->loadStats();
        $this->loadData();
        session()->flash('success', count($samples) . ' samples marked as received.');
    }

    public function render()
    {
        return view('livewire.lab.lab-dashboard')->layout('layouts.app');
    }
}
