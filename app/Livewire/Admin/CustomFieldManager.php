<?php

namespace App\Livewire\Admin;

use App\Models\CustomField;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CustomFieldManager extends Component
{
    use AuthorizesRequests;

    public $fieldId;
    public $name, $type = 'text', $options, $required = false, $order_column = 0, $is_active = true;
    public $showForm = false;

    protected function rules()
    {
        return [
            'name' => 'required',
            'type' => 'required|in:text,select,textarea',
            'options' => 'nullable',
            'required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function render()
    {
        return view('livewire.admin.custom-field-manager', [
            'fields' => CustomField::orderBy('order_column')->get(),
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $field = CustomField::findOrFail($id);
        $this->fieldId = $field->id;
        $this->name = $field->name;
        $this->type = $field->type;
        $this->options = is_array($field->options) ? implode("\n", $field->options) : $field->options;
        $this->required = $field->required;
        $this->order_column = $field->order_column;
        $this->is_active = $field->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();
        $optionsArr = $this->type === 'select'
            ? array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->options ?? '')))))
            : null;
        CustomField::updateOrCreate(['id' => $this->fieldId], [
            'name' => trim($this->name),
            'type' => $this->type,
            'options' => $optionsArr,
            'required' => (bool)$this->required,
            'order_column' => ($this->order_column !== '' && $this->order_column !== null) ? (int)$this->order_column : 0,
            'is_active' => (bool)$this->is_active,
        ]);
        session()->flash('success', 'Custom field saved successfully.');
        $this->reset(['fieldId', 'name', 'type', 'options', 'required', 'order_column', 'is_active', 'showForm']);
    }

    public function cancel()
    {
        $this->reset();
    }
}
