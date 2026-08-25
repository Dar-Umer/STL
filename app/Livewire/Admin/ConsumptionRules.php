<?php

namespace App\Livewire\Admin;

use App\Models\ChemicalParameterConsumption;
use App\Models\LabChemical;
use App\Models\SoilTestParameter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ConsumptionRules extends Component
{
    use AuthorizesRequests;

    public $chemical;
    public $rules = [];

    public function mount($chemical)
    {
        $this->chemical = LabChemical::with('consumptionRules')->findOrFail($chemical);

        $parameters = SoilTestParameter::where('is_active', true)->orderBy('order_column')->get();
        $existing = $this->chemical->consumptionRules->keyBy('soil_test_parameter_id');

        foreach ($parameters as $param) {
            $this->rules[$param->id] = [
                'enabled' => $existing->has($param->id),
                'amount' => $existing->get($param->id)?->consumption_per_test ?? 0,
            ];
        }
    }

    public function render()
    {
        $this->authorize('manage-inventory');

        return view('livewire.admin.consumption-rules', [
            'parameters' => SoilTestParameter::where('is_active', true)->orderBy('order_column')->get(),
        ])->layout('layouts.app');
    }

    public function save()
    {
        foreach ($this->rules as $paramId => $data) {
            if ($data['enabled'] && $data['amount'] > 0) {
                ChemicalParameterConsumption::updateOrCreate(
                    ['lab_chemical_id' => $this->chemical->id, 'soil_test_parameter_id' => $paramId],
                    ['consumption_per_test' => $data['amount']]
                );
            } elseif (!$data['enabled']) {
                ChemicalParameterConsumption::where('lab_chemical_id', $this->chemical->id)
                    ->where('soil_test_parameter_id', $paramId)
                    ->delete();
            }
        }

        session()->flash('success', 'Consumption rules saved.');
    }
}
