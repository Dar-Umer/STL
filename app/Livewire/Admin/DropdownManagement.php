<?php

namespace App\Livewire\Admin;

use App\Models\SoilType;
use App\Models\SoilColor;
use App\Models\Crop;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DropdownManagement extends Component
{
    use AuthorizesRequests;

    public $activeTab = 'soil_types';
    public $itemId;
    public $name;
    public $showForm = false;

    protected function rules()
    {
        return ['name' => 'required'];
    }

    public function render()
    {
        return view('livewire.admin.dropdown-management', [
            'soilTypes' => SoilType::orderBy('name')->get(),
            'soilColors' => SoilColor::orderBy('name')->get(),
            'crops' => Crop::orderBy('name')->get(),
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset(['name', 'itemId']);
        $this->showForm = true;
    }

    public function edit($id, $type)
    {
        $model = match($type) {
            'soil_types' => SoilType::findOrFail($id),
            'soil_colors' => SoilColor::findOrFail($id),
            'crops' => Crop::findOrFail($id),
        };
        $this->itemId = $model->id;
        $this->name = $model->name;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();
        $modelClass = match($this->activeTab) {
            'soil_types' => SoilType::class,
            'soil_colors' => SoilColor::class,
            'crops' => Crop::class,
        };
        $modelClass::updateOrCreate(['id' => $this->itemId], ['name' => $this->name]);
        session()->flash('success', 'Saved.');
        $this->reset();
    }

    public function cancel()
    {
        $this->reset();
    }
}
