<?php

namespace App\Livewire\Admin;

use App\Models\Lab;
use App\Models\LabChemical;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryManager extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';
    public $labId = '';
    public $showAddForm = false;
    public $showStockForm = false;
    public $showHistory = false;
    public $historyChemical = null;

    public $editId = null;
    public $name = '';
    public $sku = '';
    public $unit = '';
    public $minimumStock = '';
    public $selectedLab = '';

    public $stockChemicalId = '';
    public $stockQuantity = '';
    public $stockNotes = '';

    public function mount()
    {
        $user = auth()->user();
        if (!$user->hasRole('Admin')) {
            $this->labId = $user->lab_id;
        }
    }

    public function render()
    {
        $this->authorize('manage-inventory');

        $query = LabChemical::with('lab')
            ->when($this->labId, fn($q) => $q->where('lab_id', $this->labId))
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%");
            }))
            ->latest();

        return view('livewire.admin.inventory-manager', [
            'chemicals' => $query->paginate(15),
            'labs' => Lab::orderBy('name')->get(),
        ])->layout('layouts.app');
    }

    public function openAdd()
    {
        $this->resetForm();
        $this->showAddForm = true;
    }

    public function edit($id)
    {
        $c = LabChemical::findOrFail($id);
        $this->editId = $c->id;
        $this->name = $c->name;
        $this->sku = $c->sku;
        $this->unit = $c->unit;
        $this->minimumStock = $c->minimum_stock;
        $this->selectedLab = $c->lab_id;
        $this->showAddForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:lab_chemicals,sku,' . ($this->editId ?? ''),
            'unit' => 'required|string|max:50',
            'minimumStock' => 'nullable|numeric|min:0',
            'selectedLab' => auth()->user()->hasRole('Admin') ? 'required|exists:labs,id' : '',
        ]);

        if ($this->editId) {
            $c = LabChemical::findOrFail($this->editId);
            $c->update([
                'name' => $this->name,
                'sku' => $this->sku,
                'unit' => $this->unit,
                'minimum_stock' => $this->minimumStock ?: null,
                'lab_id' => auth()->user()->hasRole('Admin') ? $this->selectedLab : auth()->user()->lab_id,
            ]);
            session()->flash('success', 'Chemical updated.');
        } else {
            LabChemical::create([
                'lab_id' => auth()->user()->hasRole('Admin') ? $this->selectedLab : auth()->user()->lab_id,
                'name' => $this->name,
                'sku' => $this->sku,
                'unit' => $this->unit,
                'minimum_stock' => $this->minimumStock ?: null,
            ]);
            session()->flash('success', 'Chemical added.');
        }

        $this->showAddForm = false;
        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $c = LabChemical::findOrFail($id);
        $c->update(['is_active' => !$c->is_active]);
    }

    public function openStockForm($chemicalId)
    {
        $this->stockChemicalId = $chemicalId;
        $this->stockQuantity = '';
        $this->stockNotes = '';
        $this->showStockForm = true;
    }

    public function addStock()
    {
        $this->validate([
            'stockQuantity' => 'required|numeric|min:0.01',
            'stockNotes' => 'nullable|string|max:500',
        ]);

        $chemical = LabChemical::findOrFail($this->stockChemicalId);
        $before = $chemical->current_stock;
        $quantity = (float) $this->stockQuantity;
        $after = $before + $quantity;

        \DB::transaction(function () use ($chemical, $before, $after, $quantity) {
            $chemical->increment('current_stock', $quantity);
            $chemical->stockMovements()->create([
                'type' => 'in',
                'quantity' => $quantity,
                'balance_before' => $before,
                'balance_after' => $after,
                'notes' => $this->stockNotes ?: 'Manual stock addition',
                'created_by' => auth()->id(),
            ]);
        });

        session()->flash('success', 'Stock added.');
        $this->showStockForm = false;
    }

    public function openHistory($chemicalId)
    {
        $this->historyChemical = LabChemical::with('stockMovements.creator')->findOrFail($chemicalId);
        $this->showHistory = true;
    }

    public function closeHistory()
    {
        $this->showHistory = false;
        $this->historyChemical = null;
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->name = '';
        $this->sku = '';
        $this->unit = '';
        $this->minimumStock = '';
        $this->selectedLab = '';
    }
}
