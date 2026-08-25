<?php

namespace App\Livewire\Lab;

use App\Models\SoilSample;
use App\Models\SoilSampleResult;
use App\Models\SoilTestParameter;
use App\Services\NotificationService;
use App\Services\RecommendationEngine;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SampleTestingForm extends Component
{
    use AuthorizesRequests;

    public $sample;
    public $parameters;
    public $values = [];
    public $ratings = [];
    public $recommendations;
    public $rangeErrors = [];

    public function mount($sampleId)
    {
        $user = auth()->user();
        $this->sample = SoilSample::with(['farmer', 'plot'])->findOrFail($sampleId);

        if (!$user->hasRole('Admin') && $this->sample->lab_id !== $user->lab_id) {
            abort(403);
        }
        try {
            $this->parameters = SoilTestParameter::where('is_active', true)
                ->whereHas('labs', fn($q) => $q->where('lab_id', $this->sample->lab_id))
                ->orderBy('order_column')->get();
        } catch (\Exception $e) {
            $this->parameters = SoilTestParameter::where('is_active', true)
                ->orderBy('order_column')->get();
        }
        $existingResults = SoilSampleResult::where('soil_sample_id', $sampleId)->get()->keyBy('parameter_id');

        foreach ($this->parameters as $param) {
            $this->values[$param->id] = $existingResults->get($param->id)?->value;
            $this->ratings[$param->id] = $existingResults->get($param->id)?->rating;
        }
        $this->recommendations = $this->sample->recommendations;
    }

    public function updatedValues($value, $key)
    {
        $param = $this->parameters->firstWhere('id', $key);
        if ($param) {
            $this->ratings[$key] = $param->calculateRating($value);

            if ($value !== null && $value !== '' && is_numeric($value)) {
                if ($param->min_range !== null && (float) $value < (float) $param->min_range) {
                    $this->rangeErrors[$key] = 'Below range (' . $param->range . ')';
                } elseif ($param->max_range !== null && (float) $value > (float) $param->max_range) {
                    $this->rangeErrors[$key] = 'Above range (' . $param->range . ')';
                } else {
                    unset($this->rangeErrors[$key]);
                }
            } else {
                unset($this->rangeErrors[$key]);
            }
        }
    }

    public function generateRecommendations()
    {
        foreach ($this->parameters as $param) {
            if ($this->values[$param->id] !== null && $this->values[$param->id] !== '') {
                SoilSampleResult::updateOrCreate(
                    ['soil_sample_id' => $this->sample->id, 'parameter_id' => $param->id],
                    ['value' => $this->values[$param->id], 'rating' => $this->ratings[$param->id] ?? null]
                );
            }
        }
        $this->sample->load('results');
        $engine = app(RecommendationEngine::class);
        $generated = $engine->generate($this->sample);
        if ($generated) {
            $this->recommendations = $generated;
            session()->flash('info', 'Recommendations auto-generated based on test results.');
        } else {
            session()->flash('warning', 'No matching recommendation rules found.');
        }
    }

    public function save()
    {
        foreach ($this->parameters as $param) {
            if ($this->values[$param->id] !== null && $this->values[$param->id] !== '') {
                SoilSampleResult::updateOrCreate(
                    ['soil_sample_id' => $this->sample->id, 'parameter_id' => $param->id],
                    ['value' => $this->values[$param->id], 'rating' => $this->ratings[$param->id] ?? null]
                );
            }
        }
        $this->sample->update([
            'status' => 'reported',
            'analyzed_by' => auth()->id(),
            'analyzed_at' => now(),
            'recommendations' => $this->recommendations,
        ]);

        $this->sample->consumeChemicals();

        $triggers = json_decode(setting('notification_triggers', '{}'), true);
        if (($triggers['sample_reported']['enabled'] ?? true) && $this->sample->collected_by) {
            $body = resolve_notification_message(
                $triggers['sample_reported']['message'] ?? 'Sample {sample_code} has been tested and the report is ready.',
                ['sample_code' => $this->sample->sample_code, 'farmer_name' => $this->sample->farmer?->name ?? 'Unknown']
            );
            NotificationService::send(
                $this->sample->collected_by,
                'sample_reported',
                'Sample Reported',
                $body,
                ['sample_id' => $this->sample->id, 'sample_code' => $this->sample->sample_code]
            );
        }

        session()->flash('success', 'Test results saved. Report is ready.');
        $this->dispatch('openPdf', url: route('field.samples.report', $this->sample->id));
        return redirect()->route('samples.index');
    }

    public function render()
    {
        return view('livewire.lab.sample-testing-form')->layout('layouts.app');
    }
}
