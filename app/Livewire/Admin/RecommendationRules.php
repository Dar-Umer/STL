<?php

namespace App\Livewire\Admin;

use App\Models\Crop;
use App\Models\SoilTestParameter;
use App\Models\RecommendationRule;
use Livewire\Component;
use Livewire\WithPagination;

class RecommendationRules extends Component
{
    use WithPagination;

    public $ruleId;
    public $name;
    public $description;
    public $conditions = [];
    public $recommendation;
    public $priority = 0;
    public $is_active = true;
    public $showForm = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'conditions' => 'required|array|min:1',
            'conditions.*.type' => 'required|in:parameter_value,parameter_rating,plant_age,crop_is',
            'conditions.*.parameter_id' => 'required_if:conditions.*.type,parameter_value,parameter_rating|nullable|integer',
            'conditions.*.operator' => 'required_if:conditions.*.type,parameter_value,plant_age|nullable|in:lt,lte,eq,gte,gt,between',
            'conditions.*.value' => 'required_if:conditions.*.type,parameter_value,plant_age|nullable|numeric',
            'conditions.*.value_max' => 'nullable|numeric',
            'conditions.*.rating' => 'required_if:conditions.*.type,parameter_rating|nullable|in:Low,Medium,High',
            'conditions.*.crop_id' => 'required_if:conditions.*.type,crop_is|nullable|integer',
            'recommendation' => 'required|string|max:5000',
            'priority' => 'integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function render()
    {
        return view('livewire.admin.recommendation-rules', [
            'rules' => RecommendationRule::orderBy('priority', 'desc')->orderBy('id', 'desc')->paginate(20),
            'parameters' => SoilTestParameter::where('is_active', true)->orderBy('order_column')->get(),
            'crops' => Crop::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $rule = RecommendationRule::findOrFail($id);
        $this->ruleId = $rule->id;
        $this->name = $rule->name;
        $this->description = $rule->description;
        $this->conditions = $rule->conditions ?? [];
        $this->recommendation = $rule->recommendation;
        $this->priority = $rule->priority;
        $this->is_active = $rule->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'conditions' => $this->conditions,
            'recommendation' => $this->recommendation,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
        ];

        if ($this->ruleId) {
            RecommendationRule::findOrFail($this->ruleId)->update($data);
            session()->flash('success', 'Rule updated.');
        } else {
            RecommendationRule::create($data);
            session()->flash('success', 'Rule created.');
        }

        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $rule = RecommendationRule::findOrFail($id);
        $rule->update(['is_active' => !$rule->is_active]);
    }

    public function delete($id)
    {
        RecommendationRule::findOrFail($id)->delete();
        session()->flash('success', 'Rule deleted.');
    }

    public function addCondition()
    {
        $this->conditions[] = ['type' => 'parameter_value', 'parameter_id' => '', 'operator' => 'gte', 'value' => '', 'value_max' => '', 'rating' => '', 'crop_id' => ''];
    }

    public function removeCondition($index)
    {
        unset($this->conditions[$index]);
        $this->conditions = array_values($this->conditions);
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->ruleId = null;
        $this->name = '';
        $this->description = '';
        $this->conditions = [];
        $this->recommendation = '';
        $this->priority = 0;
        $this->is_active = true;
        $this->showForm = false;
        $this->resetErrorBag();
    }
}
