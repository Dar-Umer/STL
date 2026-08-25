<?php

namespace App\Livewire\Admin;

use App\Models\Lab;
use App\Models\SoilTestParameter;
use Livewire\Component;

class LabManagement extends Component
{
    public $labId;
    public $name, $code, $address, $phone, $email, $contact_person, $is_active = true;
    public $selectedParameters = [];
    public $showForm = false;

    protected function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:labs,code,' . $this->labId,
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'contact_person' => 'nullable',
            'is_active' => 'boolean',
            'selectedParameters' => 'array',
        ];
    }

    public function render()
    {
        $labs = Lab::orderBy('created_at', 'desc')->get();
        try {
            $labs->load('parameters');
        } catch (\Exception $e) {
        }
        return view('livewire.admin.lab-management', [
            'labs' => $labs,
            'allParameters' => SoilTestParameter::where('is_active', true)->orderBy('order_column')->get(),
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $lab = Lab::findOrFail($id);
        $this->labId = $lab->id;
        $this->name = $lab->name;
        $this->code = $lab->code;
        $this->address = $lab->address;
        $this->phone = $lab->phone;
        $this->email = $lab->email;
        $this->contact_person = $lab->contact_person;
        $this->is_active = $lab->is_active;
        try {
            $this->selectedParameters = $lab->parameters->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } catch (\Exception $e) {
            $this->selectedParameters = [];
        }
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();
        $lab = Lab::updateOrCreate(['id' => $this->labId], [
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'is_active' => $this->is_active,
        ]);
        try {
            $lab->parameters()->sync($this->selectedParameters);
        } catch (\Exception $e) {
        }
        session()->flash('success', $this->labId ? 'Lab updated.' : 'Lab created.');
        $this->reset();
    }

    public function toggleActive($id)
    {
        $lab = Lab::findOrFail($id);
        $lab->update(['is_active' => !$lab->is_active]);
    }

    public function cancel()
    {
        $this->reset();
    }
}
