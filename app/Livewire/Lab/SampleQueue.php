<?php

namespace App\Livewire\Lab;

use App\Models\DispatchBatch;
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
                $q->where('sample_code', $code)
                  ->orWhere('qr_code_path', $code)
                  ->orWhereHas('qrCode', fn($qr) => $qr->where('code', $code));
            })
            ->with('farmer')
            ->first();
    }

    public function addScannedCode()
    {
        $code = trim($this->scanInput);
        if (!$code) return;

        // Check if scanned code is a Dispatch Batch (e.g. BAT501)
        $dispatchBatch = DispatchBatch::where('batch_no', $code)->first();
        if ($dispatchBatch) {
            $this->scanInput = '';
            if ($dispatchBatch->status === 'received') {
                $this->addError('scanInput', "Batch {$code} was already received on " . ($dispatchBatch->received_at ? $dispatchBatch->received_at->format('d M Y') : 'earlier') . ".");
                $this->dispatch('scan-duplicate');
                return;
            }
            $this->receiveDispatchBatch($dispatchBatch->id);
            return;
        }

        $sample = $this->findByCode($code);

        if (!$sample) {
            $this->addError('scanInput', "No pending sample or batch found with code '{$code}'.");
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

    /**
     * Receive an entire field dispatch batch at once.
     */
    public function receiveDispatchBatch(int $batchId)
    {
        $batch = DispatchBatch::with('soilSamples')->findOrFail($batchId);

        if ($batch->status === 'received') {
            session()->flash('warning', "Batch {$batch->batch_no} has already been received.");
            return;
        }

        $samples = $batch->soilSamples()->where('status', 'collected')->get();

        if ($samples->isEmpty()) {
            session()->flash('warning', "No eligible collected samples found in Batch {$batch->batch_no}.");
            return;
        }

        try {
            $now = now();
            $userId = auth()->id();

            $evalBatch = LabEvaluationService::receiveSamplesIntoBatch(
                $samples->pluck('id')->all(),
                auth()->user()->lab_id ?? $batch->lab_id,
                $userId,
                "Field Dispatch Batch {$batch->batch_no}"
            );

            $batch->update([
                'status' => 'received',
                'received_by' => $userId,
                'received_at' => $now,
                'evaluation_batch_id' => $evalBatch->id,
            ]);

            session()->flash('receivedBatch', [
                'id' => $evalBatch->id,
                'batch_code' => $evalBatch->batch_code,
                'from' => $evalBatch->lab_number_from,
                'to' => $evalBatch->lab_number_to,
                'count' => $evalBatch->sample_count,
            ]);

            session()->flash('success', "Batch {$batch->batch_no} ({$evalBatch->sample_count} sample(s)) received into Evaluation Batch {$evalBatch->batch_code} (Lab Codes: {$evalBatch->lab_number_from} to {$evalBatch->lab_number_to}).");
            $this->dispatch('scan-added');
        } catch (\Exception $e) {
            session()->flash('error', 'Error receiving batch: ' . $e->getMessage());
        }
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
        $eligible = $this->pendingQuery()->whereIn('id', $ids)->get();

        if ($eligible->isEmpty()) {
            session()->flash('warning', 'No eligible samples found (they may have been received already).');
            return;
        }

        try {
            $now = now();
            $userId = auth()->id();

            $batch = LabEvaluationService::receiveSamplesIntoBatch(
                $eligible->pluck('id')->all(),
                auth()->user()->lab_id,
                $userId
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

        $pendingBatchesQuery = DispatchBatch::with(['zone', 'creator', 'lab', 'soilSamples'])
            ->where('status', 'dispatched');

        if (!auth()->user()->hasRole('Admin')) {
            $labId = auth()->user()->lab_id;
            if ($labId) {
                $pendingBatchesQuery->where(function ($q) use ($labId) {
                    $q->where('lab_id', $labId)->orWhereNull('lab_id');
                });
            }
        }

        $pendingBatches = $pendingBatchesQuery->orderBy('id', 'desc')->get();

        return view('livewire.lab.sample-queue', [
            'samples' => $samples,
            'pendingBatches' => $pendingBatches,
            'stats' => $stats,
            'overdueDays' => (int) setting('overdue_days', 2),
            'pageIds' => $samples->getCollection()->pluck('id')->all(),
        ])->layout('layouts.app');
    }
}
