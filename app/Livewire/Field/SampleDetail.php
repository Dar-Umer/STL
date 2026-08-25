<?php

namespace App\Livewire\Field;

use App\Models\SoilSample;
use Livewire\Component;

class SampleDetail extends Component
{
    public $sample;

    public function mount($sampleId)
    {
        $user = auth()->user();

        $this->sample = SoilSample::with([
            'farmer.state',
            'farmer.district',
            'farmer.zone',
            'farmer.panchayat',
            'farmer.village',
            'plot.soilType',
            'plot.crop',
            'plot.state',
            'plot.district',
            'plot.zone',
            'plot.panchayat',
            'plot.village',
            'collector',
            'analyst',
            'lab',
            'results.parameter',
            'qrCode',
        ])->findOrFail($sampleId);

        if ($user->hasRole('Farmer')) {
            $farmer = $user->farmer;
            if (!$farmer || $this->sample->farmer_id !== $farmer->id) {
                abort(403);
            }
        }

        if ($user->hasRole('Lab Manager') && $this->sample->lab_id !== $user->lab_id) {
            abort(403);
        }

        if ($user->hasRole('Field Official') && $this->sample->lab_id !== $user->lab_id) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.field.sample-detail', [
            'sample' => $this->sample,
        ])->layout('layouts.app');
    }
}
