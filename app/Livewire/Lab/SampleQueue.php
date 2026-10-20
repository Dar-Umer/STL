<?php

namespace App\Livewire\Lab;

use App\Models\SoilSample;
use App\Models\SampleStatusHistory;
use App\Services\LabEvaluationService;
use Livewire\Component;
use Livewire\WithPagination;

class SampleQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $scanInput = '';

    /** @var array<int, array{code:string, id:int, sample_code:string, farmer:string}> */
    public $scannedSamples = [];

    /** @var array<int, int> */
    public $selected = [];

    public $sourceFilter = '';
    public $sort = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sourceFilter' => ['except' => ''],
        'sort' => ['except' => 'desc'],
    ];

    protected function labQuery()
    {
        $query = SoilSample::query();
        $labId = auth()->user()->lab_id;
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', $labId);
        }
        return $query;
    }

    protected function pendingQuery()
    {
        return (clone $this->labQuery())->where('status', 'collected');
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSourceFilter() { $this->resetPage(); }
    public function updatingSort() { $this->resetPage(); }

    protected function findByCode(string $code)
    {
        return $this->pendingQuery()
            ->where(function ($q) use ($code) {
                $q->where('qr_code_path', $code)
                  ->orWhereHas('qrCode', fn($qr) => $qr->where('code', $code));
            })
            ->with('farmer')
            ->first();
    }

    public function addScannedCode()
    {
        $code = trim($this->scanInput);
        if (!$code) return;

        $sample = $this->findByCode($code);

        if (!$sample) {
            $this->addError('scanInput', "No pending sample with QR code '{$code}'.");
            $this->scanInput = '';
            $this->dispatch('scan-rejected');
            return;
        }

        if (collect($this->scannedSamples)->firstWhere('id', $sample->id)) {
            $this->addError('scanInput', "{$sample->sample_code} ({$sample->farmer->name}) is already in the receive queue.");
            $this->scanInput = '';
            $this->dispatch('scan-duplicate');
            return;
        }

        $this->scannedSamples[] = [
            'code' => $code,
            'id' => $sample->id,
            'sample_code' => $sample->sample_code,
            'farmer' => $sample->farmer->name ?? '—',
        ];

        $this->scanInput = '';
        $this->dispatch('scan-added');
    }

    public function removeScannedSample($index)
    {
        if (isset($this->scannedSamples[$index])) {
            unset($this->scannedSamples[$index]);
            $this->scannedSamples = array_values($this->scannedSamples);
        }
    }

    public function clearScanned()
    {
        $this->scannedSamples = [];
        $this->scanInput = '';
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
        return $this->pendingQuery()
            ->when($this->search, fn($q) => $this->applySearch($q))
            ->when($this->sourceFilter, fn($q) => $q->where('source', $this->sourceFilter))
            ->orderBy('created_at', $this->sort)
            ->forPage($this->getPage(), 15)
            ->pluck('id')
            ->all();
    }

    protected function applySearch($query)
    {
        $s = $this->search;
        return $query->where(function ($q) use ($s) {
            $q->where('sample_code', 'like', "%{$s}%")
              ->orWhereHas('farmer', fn($f) => $f->where('name', 'like', "%{$s}%"));
        });
    }

    public function receiveScanned()
    {
        if (empty($this->scannedSamples)) return;

        $this->receiveMany(collect($this->scannedSamples)->pluck('id')->all());
        $this->clearScanned();
    }

    public function receiveSelected()
    {
        if (empty($this->selected)) return;

        $this->receiveMany($this->selected);
        $this->selected = [];
    }

    public function markReceived($sampleId)
    {
        $this->receiveMany([$sampleId]);
    }

    public function receiveAllPending()
    {
        $ids = $this->pendingQuery()->pluck('id')->all();

        if (empty($ids)) return;

        $this->receiveMany($ids);
    }

    protected function receiveMany(array $ids): void
    {
        $eligible = $this->pendingQuery()->whereIn('id', $ids)->pluck('id')->all();

        if (empty($eligible)) {
            session()->flash('warning', 'No eligible samples found (they may have been received already).');
            return;
        }

        try {
            $batch = LabEvaluationService::receiveSamplesIntoBatch(
                $eligible,
                auth()->user()->lab_id,
                auth()->id()
            );

            session()->flash('receivedBatch', [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'from' => $batch->lab_number_from,
                'to' => $batch->lab_number_to,
                'count' => $batch->sample_count,
            ]);

            session()->flash('success', "{$batch->sample_count} sample(s) received successfully into Evaluation Batch {$batch->batch_code} (Lab Codes: {$batch->lab_number_from} to {$batch->lab_number_to}).");
        } catch (\Exception $e) {
            session()->flash('error', 'Error receiving samples: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = $this->pendingQuery()
            ->with(['farmer.state', 'farmer.district', 'farmer.village', 'plot', 'qrCode']);

        if ($this->search) {
            $query = $this->applySearch($query);
        }

        if ($this->sourceFilter) {
            $query->where('source', $this->sourceFilter);
        }

        $samples = $query->orderBy('created_at', $this->sort)->paginate(15);

        $stats = [
            'toReceive' => (clone $this->labQuery())->where('status', 'collected')->count(),
            'fieldPending' => (clone $this->labQuery())->where('status', 'collected')->where('source', 'field')->count(),
            'selfPending' => (clone $this->labQuery())->where('status', 'collected')->where('source', 'self')->count(),
            'receivedToday' => (clone $this->labQuery())
                ->where('status', '!=', 'collected')
                ->whereDate('received_at', today())
                ->count(),
        ];

        return view('livewire.lab.sample-queue', [
            'samples' => $samples,
            'stats' => $stats,
            'overdueDays' => (int) setting('overdue_days', 2),
            'pageIds' => $samples->getCollection()->pluck('id')->all(),
        ])->layout('layouts.app');
    }
}
