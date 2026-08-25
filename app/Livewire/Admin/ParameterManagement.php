<?php

namespace App\Livewire\Admin;

use App\Models\Lab;
use App\Models\SoilTestParameter;
use Livewire\Component;
use Livewire\WithPagination;

class ParameterManagement extends Component
{
    public $parameterId;
    public $name, $unit, $min_range, $max_range, $method, $rating_low_max, $rating_medium_max, $rating_high_min, $order_column, $is_active = true;
    public $rating_label_low = 'Low', $rating_label_medium = 'Medium', $rating_label_high = 'High';
    public $rating_color_low = '#ffc107', $rating_color_medium = '#17a2b8', $rating_color_high = '#28a745';
    public $selectedLabs = [];
    public $showForm = false;
    public $showInactive = false;

    protected function rules()
    {
        return ['name' => 'required', 'unit' => 'nullable', 'method' => 'nullable', 'is_active' => 'boolean', 'order_column' => 'integer', 'selectedLabs' => 'array'];
    }

    public function render()
    {
        $query = SoilTestParameter::orderBy('order_column');
        if (!$this->showInactive) {
            $query->where('is_active', true);
        }
        return view('livewire.admin.parameter-management', [
            'parameters' => $query->get(),
            'labs' => Lab::where('is_active', true)->get(),
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset();
        $this->rating_label_low = 'Low';
        $this->rating_label_medium = 'Medium';
        $this->rating_label_high = 'High';
        $this->rating_color_low = '#ffc107';
        $this->rating_color_medium = '#17a2b8';
        $this->rating_color_high = '#28a745';
        $this->showForm = true;
    }

    public function edit($id)
    {
        $param = SoilTestParameter::findOrFail($id);
        foreach ($param->getAttributes() as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
        $labels = $param->rating_labels ?? [];
        $this->rating_label_low = $labels['low'] ?? 'Low';
        $this->rating_label_medium = $labels['medium'] ?? 'Medium';
        $this->rating_label_high = $labels['high'] ?? 'High';
        $colors = $param->rating_colors ?? [];
        $this->rating_color_low = $colors['low'] ?? '#ffc107';
        $this->rating_color_medium = $colors['medium'] ?? '#17a2b8';
        $this->rating_color_high = $colors['high'] ?? '#28a745';
        $this->parameterId = $param->id;
        try {
            $this->selectedLabs = $param->labs->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } catch (\Exception $e) {
            $this->selectedLabs = [];
        }
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();
        $param = SoilTestParameter::updateOrCreate(['id' => $this->parameterId], [
            'name' => $this->name, 'unit' => $this->unit,
            'min_range' => $this->min_range, 'max_range' => $this->max_range,
            'method' => $this->method,
            'rating_low_max' => $this->rating_low_max, 'rating_medium_max' => $this->rating_medium_max, 'rating_high_min' => $this->rating_high_min,
            'rating_labels' => [
                'low' => $this->rating_label_low ?? 'Low',
                'medium' => $this->rating_label_medium ?? 'Medium',
                'high' => $this->rating_label_high ?? 'High',
            ],
            'rating_colors' => [
                'low' => $this->rating_color_low ?? '#ffc107',
                'medium' => $this->rating_color_medium ?? '#17a2b8',
                'high' => $this->rating_color_high ?? '#28a745',
            ],
            'order_column' => $this->order_column ?? 0, 'is_active' => $this->is_active,
        ]);
        try {
            $param->labs()->sync($this->selectedLabs);
        } catch (\Exception $e) {
        }
        session()->flash('success', 'Parameter saved.');
        $this->reset();
    }

    public function delete($id)
    {
        $param = SoilTestParameter::withCount('results')->findOrFail($id);
        if ($param->results_count > 0) {
            $param->update(['is_active' => false]);
            session()->flash('warning', "Parameter \"{$param->name}\" deactivated — it has {$param->results_count} existing result(s).");
        } else {
            $param->delete();
            session()->flash('success', "Parameter \"{$param->name}\" deleted.");
        }
    }

    public function cancel()
    {
        $this->reset();
    }
}
