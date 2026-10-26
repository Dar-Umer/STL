<?php

namespace App\Livewire\Lab;

use App\Models\SoilSample;
use Livewire\Component;
use Livewire\WithPagination;

class SampleTesting extends Component
{
    use WithPagination;

    public $search = '';
    public $scanInput = '';
    public $dateFilter = '';

    protected function labQuery()
    {
        $query = SoilSample::query();
        $labId = auth()->user()->lab_id;
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', $labId);
        }
        return $query;
    }

    public function addScannedCode()
    {
        $code = trim($this->scanInput);
        if (!$code) return;

        $sample = (clone $this->labQuery())
            ->where(function ($q) use ($code) {
                $q->where('lab_number', $code)
                  ->orWhere('sample_code', $code)
                  ->orWhere('qr_code_path', $code)
                  ->orWhereHas('qrCode', fn($qr) => $qr->where('code', $code));
            })
            ->where('status', 'received')
            ->first();

        if (!$sample) {
            $this->addError('scanInput', "No sample waiting for testing with Lab Code or QR code '{$code}'.");
            $this->scanInput = '';
            return;
        }

        $this->scanInput = '';
        $this->js("window.location.href='" . route('lab.testing', $sample->id) . "'");
    }

    public function render()
    {
        $query = SoilSample::with([
            'farmer', 'farmer.state', 'farmer.district', 'farmer.village', 'farmer.zone',
            'plot', 'plot.crop', 'plot.soilType',
            'lab', 'collector', 'qrCode',
            'results.parameter',
        ])->where('status', 'received');

        $labId = auth()->user()->lab_id;
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', $labId);
        }

        if ($this->search) {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('lab_number', 'like', "%{$s}%")
                  ->orWhere('sample_code', 'like', "%{$s}%")
                  ->orWhereHas('farmer', fn($f) => $f->where('name', 'like', "%{$s}%"));
            });
        }

        $overdueDays = (int) setting('overdue_days', 2);

        if ($this->dateFilter === 'today') {
            $query->whereDate('received_at', today());
        } elseif ($this->dateFilter === 'week') {
            $query->whereBetween('received_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateFilter === 'older') {
            $query->where('received_at', '<', now()->subDays($overdueDays));
        }

        $samples = $query->orderBy('received_at', 'asc')->paginate(15);
        $pendingCount = (clone $this->labQuery())->where('status', 'received')->count();

        $overdueCount = (clone $this->labQuery())
            ->where('status', 'received')
            ->where(function ($q) use ($overdueDays) {
                $q->whereNull('received_at')
                  ->orWhere('received_at', '<', now()->subDays($overdueDays));
            })
            ->count();

        return view('livewire.lab.sample-testing', [
            'samples' => $samples,
            'pendingCount' => $pendingCount,
            'overdueCount' => $overdueCount,
            'overdueDays' => $overdueDays,
        ])->layout('layouts.app');
    }
}
