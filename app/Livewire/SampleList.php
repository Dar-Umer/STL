<?php

namespace App\Livewire;

use App\Enums\SampleStatus;
use App\Models\SoilSample;
use App\Exports\SoilSamplesExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\WithPagination;

class SampleList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    public function exportExcel()
    {
        $this->resetPage();

        return Excel::download(
            new SoilSamplesExport(auth()->user(), $this->statusFilter, $this->dateFrom, $this->dateTo, $this->search),
            'soil-samples.xlsx'
        );
    }

    public function render()
    {
        $user = auth()->user();

        $query = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.village', 'farmer.zone',
            'plot.crop', 'plot.soilType',
            'collector', 'lab',
        ]);

        if ($user->hasRole('Farmer')) {
            $farmer = $user->farmer;
            if ($farmer) {
                $query->where('farmer_id', $farmer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif (!$user->hasRole('Admin')) {
            $query->where('lab_id', $user->lab_id);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('sample_code', 'like', "%{$this->search}%")
                  ->orWhereHas('farmer', fn($f) => $f->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('plot', fn($p) => $p->where('plot_name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->dateFrom && $this->dateTo && $this->dateFrom > $this->dateTo) {
            [$this->dateFrom, $this->dateTo] = [$this->dateTo, $this->dateFrom];
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $samples = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [];
        foreach (SampleStatus::cases() as $status) {
            $q = SoilSample::where('status', $status->value);
            if (!$user->hasRole('Admin') && !$user->hasRole('Farmer')) {
                $q->where('lab_id', $user->lab_id);
            }
            if ($user->hasRole('Farmer')) {
                $farmer = $user->farmer;
                if ($farmer) {
                    $q->where('farmer_id', $farmer->id);
                } else {
                    $q->whereRaw('1 = 0');
                }
            }
            $stats[] = [
                'label' => $status->label(),
                'value' => $status->value,
                'count' => $q->count(),
            ];
        }

        $totalQ = SoilSample::query();
        if (!$user->hasRole('Admin') && !$user->hasRole('Farmer')) {
            $totalQ->where('lab_id', $user->lab_id);
        }
        if ($user->hasRole('Farmer')) {
            $farmer = $user->farmer;
            if ($farmer) {
                $totalQ->where('farmer_id', $farmer->id);
            } else {
                $totalQ->whereRaw('1 = 0');
            }
        }

        return view('livewire.sample-list', [
            'samples' => $samples,
            'stats' => $stats,
            'statuses' => SampleStatus::cases(),
            'totalCount' => $totalQ->count(),
        ])->layout('layouts.app');
    }
}
