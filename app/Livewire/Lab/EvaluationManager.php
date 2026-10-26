<?php

namespace App\Livewire\Lab;

use App\Exports\EvaluationExport;
use App\Models\EvaluationBatch;
use App\Models\SoilSample;
use App\Models\SoilSampleResult;
use App\Models\SoilTestParameter;
use App\Services\LabEvaluationService;
use App\Services\RecommendationEngine;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class EvaluationManager extends Component
{
    use WithPagination;

    public string $activeTab = 'samples'; // 'samples' or 'batches'

    // Filters
    public string $search = '';
    public string $statusFilter = ''; // '', 'received', 'testing', 'reported'
    public string $codeFilter = ''; // '', 'with_code', 'no_code'
    public string $batchFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    // Multi-select
    public array $selected = [];

    // Quick Result Entry Modal
    public bool $showQuickEntryModal = false;
    public ?int $editingSampleId = null;
    public ?SoilSample $editingSample = null;
    public array $quickValues = [];
    public array $quickRatings = [];
    public string $quickRemarks = '';
    public bool $autoGenerateRecommendations = true;

    protected $queryString = [
        'activeTab' => ['except' => 'samples'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'codeFilter' => ['except' => ''],
        'batchFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingCodeFilter() { $this->resetPage(); }
    public function updatingBatchFilter() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->selected = [];
    }

    public function toggleSelectAll()
    {
        $pageIds = $this->currentPageIds();
        $allSelected = !empty($pageIds) && count(array_intersect($pageIds, $this->selected)) === count($pageIds);

        if ($allSelected) {
            $this->selected = array_values(array_diff($this->selected, $pageIds));
        } else {
            $this->selected = array_values(array_unique(array_merge($this->selected, $pageIds)));
        }
    }

    protected function currentPageIds(): array
    {
        return $this->getSamplesQuery()->forPage($this->getPage(), 15)->pluck('id')->all();
    }

    protected function getSamplesQuery()
    {
        $query = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.zone', 'farmer.village',
            'plot.crop', 'plot.soilType',
            'lab', 'analyst', 'evaluationBatch',
            'results.parameter',
        ])->whereIn('status', ['received', 'testing', 'reported']);

        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', auth()->user()->lab_id);
        }

        if ($this->codeFilter === 'with_code') {
            $query->whereNotNull('lab_number');
        } elseif ($this->codeFilter === 'no_code') {
            $query->whereNull('lab_number');
        }

        if ($this->search) {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('lab_number', 'like', "%{$s}%")
                  ->orWhere('sample_code', 'like', "%{$s}%")
                  ->orWhereHas('farmer', fn($f) => $f->where('name', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%"))
                  ->orWhereHas('evaluationBatch', fn($b) => $b->where('batch_code', 'like', "%{$s}%"));
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->batchFilter) {
            $query->where('evaluation_batch_id', $this->batchFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('received_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('received_at', '<=', $this->dateTo);
        }

        return $query->orderByRaw('CASE WHEN lab_number IS NULL THEN 0 ELSE 1 END, id desc');
    }

    protected function getBatchesQuery()
    {
        $query = EvaluationBatch::with(['lab', 'receivedBy', 'soilSamples']);

        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', auth()->user()->lab_id);
        }

        if ($this->search) {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('batch_code', 'like', "%{$s}%")
                  ->orWhere('lab_number_from', 'like', "%{$s}%")
                  ->orWhere('lab_number_to', 'like', "%{$s}%")
                  ->orWhere('notes', 'like', "%{$s}%");
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    // Quick Result Entry
    public function openQuickEntry(int $sampleId)
    {
        $this->editingSampleId = $sampleId;
        $this->editingSample = SoilSample::with(['farmer', 'plot', 'results.parameter'])->findOrFail($sampleId);

        $params = SoilTestParameter::where('is_active', true)->orderBy('order_column')->get();
        $existing = $this->editingSample->results->keyBy('parameter_id');

        $this->quickValues = [];
        $this->quickRatings = [];
        foreach ($params as $param) {
            $val = $existing->get($param->id)?->value;
            $this->quickValues[$param->id] = $val !== null ? (string)$val : '';
            $this->quickRatings[$param->id] = $existing->get($param->id)?->rating;
        }

        $this->quickRemarks = $this->editingSample->evaluation_remarks ?? '';
        $this->showQuickEntryModal = true;
    }

    public function updatedQuickValues($value, $paramId)
    {
        $param = SoilTestParameter::find($paramId);
        if ($param && $value !== null && $value !== '') {
            $this->quickRatings[$paramId] = $param->calculateRating((float) $value);
        } else {
            $this->quickRatings[$paramId] = null;
        }
    }

    public function closeQuickEntry()
    {
        $this->showQuickEntryModal = false;
        $this->editingSampleId = null;
        $this->editingSample = null;
        $this->quickValues = [];
        $this->quickRatings = [];
    }

    public function saveQuickResults()
    {
        if (!$this->editingSample) return;

        foreach ($this->quickValues as $paramId => $val) {
            if ($val !== null && $val !== '') {
                SoilSampleResult::updateOrCreate(
                    ['soil_sample_id' => $this->editingSample->id, 'parameter_id' => $paramId],
                    ['value' => (float)$val, 'rating' => $this->quickRatings[$paramId] ?? null]
                );
            }
        }

        $sample = $this->editingSample->fresh(['results']);

        $recommendations = $sample->recommendations;
        if ($this->autoGenerateRecommendations) {
            try {
                $engine = app(RecommendationEngine::class);
                $gen = $engine->generate($sample);
                if ($gen) {
                    $recommendations = $gen;
                }
            } catch (\Exception $e) {}
        }

        $sample->update([
            'status' => 'reported',
            'analyzed_by' => auth()->id(),
            'analyzed_at' => now(),
            'recommendations' => $recommendations,
            'evaluation_remarks' => $this->quickRemarks,
        ]);

        try {
            $sample->consumeChemicals();
        } catch (\Exception $e) {}

        session()->flash('success', "Test results saved successfully for Lab Code {$sample->lab_number} ({$sample->sample_code}).");
        $this->closeQuickEntry();
    }

    // Exporting & Printing
    public function exportAllExcel()
    {
        $ids = $this->getSamplesQuery()->pluck('id')->all();
        return Excel::download(new EvaluationExport($ids), 'soil-evaluations-' . date('Ymd') . '.xlsx');
    }

    public function exportSelectedExcel()
    {
        if (empty($this->selected)) {
            session()->flash('warning', 'Please select at least one sample to export.');
            return null;
        }

        return Excel::download(new EvaluationExport($this->selected), 'soil-evaluations-selected.xlsx');
    }

    public function printSelectedWorksheet()
    {
        if (empty($this->selected)) {
            session()->flash('warning', 'Please select at least one sample to print.');
            return;
        }

        $idsString = implode(',', $this->selected);
        return redirect()->route('lab.evaluations.selected.pdf', ['ids' => $idsString]);
    }

    public function filterByBatch(int $batchId)
    {
        $this->batchFilter = (string)$batchId;
        $this->activeTab = 'samples';
        $this->resetPage();
    }

    public function assignLabCode(int $sampleId)
    {
        $sample = SoilSample::findOrFail($sampleId);
        if ($sample->lab_number) {
            session()->flash('warning', "Sample {$sample->sample_code} already has Lab Code: {$sample->lab_number}");
            return;
        }

        $codes = LabEvaluationService::allocateLabNumbers(1);
        $sample->update(['lab_number' => $codes[0]]);
        session()->flash('success', "Assigned Lab Code {$codes[0]} to {$sample->sample_code}.");
    }

    public function assignCodesToSelected()
    {
        if (empty($this->selected)) {
            session()->flash('warning', 'Please select at least one sample.');
            return;
        }

        $unassigned = SoilSample::whereIn('id', $this->selected)->whereNull('lab_number')->orderBy('id', 'asc')->get();
        if ($unassigned->isEmpty()) {
            session()->flash('warning', 'All selected samples already have Lab Codes assigned.');
            return;
        }

        $codes = LabEvaluationService::allocateLabNumbers($unassigned->count());
        foreach ($unassigned as $i => $s) {
            $s->update(['lab_number' => $codes[$i]]);
        }

        session()->flash('success', "Assigned Lab Codes to {$unassigned->count()} selected sample(s) ({$codes[0]} to " . end($codes) . ").");
        $this->selected = [];
    }

    public function backfillMissingCodes()
    {
        $count = LabEvaluationService::backfillMissingLabNumbers();
        session()->flash('success', "Assigned sequential Lab Codes to {$count} sample(s).");
    }

    public function render()
    {
        $parameters = SoilTestParameter::where('is_active', true)->orderBy('order_column')->get();
        $batches = EvaluationBatch::orderBy('id', 'desc')->get();

        $samples = $this->activeTab === 'samples' ? $this->getSamplesQuery()->paginate(15) : null;
        $batchesList = $this->activeTab === 'batches' ? $this->getBatchesQuery()->paginate(15) : null;

        $stats = [
            'totalEvaluated' => SoilSample::whereNotNull('lab_number')->count(),
            'pendingTesting' => SoilSample::whereNotNull('lab_number')->where('status', 'received')->count(),
            'reported' => SoilSample::whereNotNull('lab_number')->where('status', 'reported')->count(),
            'totalBatches' => EvaluationBatch::count(),
            'unassignedCount' => SoilSample::whereNull('lab_number')->whereIn('status', ['received', 'testing', 'reported'])->count(),
            'nextLabNumber' => LabEvaluationService::peekNextLabNumber(),
        ];

        return view('livewire.lab.evaluation-manager', [
            'samples' => $samples,
            'batchesList' => $batchesList,
            'parameters' => $parameters,
            'batches' => $batches,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
