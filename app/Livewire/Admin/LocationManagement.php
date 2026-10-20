<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Imports\LocationsImport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class LocationManagement extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $locationId;
    public $name, $type = 'state', $parent_id, $code;
    public $parents = [];
    public $search = '';
    public $filterType = 'all';
    public $importFile;
    public $showFormModal = false;
    public $showImportModal = false;
    public $deletingId = null;
    public $deletingName = '';

    protected function rules()
    {
        $rules = [
            'name' => 'required',
            'type' => 'required|in:state,district,zone,panchayat,village',
            'parent_id' => 'nullable|exists:locations,id',
            'code' => 'nullable',
        ];
        if ($this->type !== 'state') {
            $rules['parent_id'] = 'required|exists:locations,id';
        }
        return $rules;
    }

    public function render()
    {
        $query = Location::with('parent')->orderBy('type')->orderBy('name');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        } elseif ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $states = null;
        if (!$this->search && $this->filterType === 'all') {
            $states = Location::where('type', 'state')
                ->with('children.children.children.children')
                ->orderBy('name')
                ->get();
        }

        $stats = [
            'total' => Location::count(),
            'state' => Location::where('type', 'state')->count(),
            'district' => Location::where('type', 'district')->count(),
            'zone' => Location::where('type', 'zone')->count(),
            'panchayat' => Location::where('type', 'panchayat')->count(),
            'village' => Location::where('type', 'village')->count(),
        ];

        return view('livewire.admin.location-management', [
            'states' => $states,
            'allLocations' => $query->get(),
            'stats' => $stats,
        ])->layout('layouts.app');
    }

    public function updatedType($value)
    {
        $this->parent_id = null;
        if ($value === 'state') {
            $this->parents = collect();
            return;
        }
        $this->parents = Location::where('type', '!=', 'village')
            ->when($value === 'district', fn($q) => $q->where('type', 'state'))
            ->when($value === 'zone', fn($q) => $q->where('type', 'district'))
            ->when($value === 'panchayat', fn($q) => $q->where('type', 'zone'))
            ->when($value === 'village', fn($q) => $q->where('type', 'panchayat'))
            ->orderBy('name')
            ->get();
    }

    public function create()
    {
        $this->reset(['locationId', 'name', 'type', 'parent_id', 'code']);
        $this->type = 'state';
        $this->showFormModal = true;
        $this->showImportModal = false;
        $this->reset(['deletingId', 'deletingName']);
    }

    public function edit($id)
    {
        $loc = Location::findOrFail($id);
        $this->locationId = $loc->id;
        $this->name = $loc->name;
        $this->type = $loc->type;
        $this->parent_id = $loc->parent_id;
        $this->code = $loc->code;
        $this->updatedType($loc->type);
        $this->showFormModal = true;
        $this->showImportModal = false;
        $this->reset(['deletingId', 'deletingName']);
    }

    public function save()
    {
        $this->parent_id = ($this->type !== 'state' && $this->parent_id) ? $this->parent_id : null;
        $this->validate();
        Location::updateOrCreate(['id' => $this->locationId], [
            'name' => trim($this->name),
            'type' => $this->type,
            'parent_id' => $this->type !== 'state' ? ($this->parent_id ?: null) : null,
            'code' => $this->code ? trim($this->code) : null,
        ]);
        session()->flash('success', $this->locationId ? 'Location updated.' : 'Location created.');
        $this->reset(['locationId', 'name', 'type', 'parent_id', 'code']);
        $this->showFormModal = false;
    }

    public function confirmDelete($id)
    {
        $loc = Location::findOrFail($id);
        if ($loc->children()->exists()) {
            session()->flash('error', "Cannot delete '{$loc->name}' — it has sub-locations. Delete those first.");
            return;
        }
        $this->deletingId = $loc->id;
        $this->deletingName = $loc->name;
        $this->showFormModal = false;
        $this->showImportModal = false;
    }

    public function delete()
    {
        $loc = Location::findOrFail($this->deletingId);
        $loc->delete();
        $this->reset(['deletingId', 'deletingName']);
        session()->flash('success', 'Location deleted.');
    }

    public function cancelDelete()
    {
        $this->reset(['deletingId', 'deletingName']);
    }

    public function cancel()
    {
        $this->reset(['locationId', 'name', 'type', 'parent_id', 'code']);
        $this->showFormModal = false;
    }

    public function toggleImport()
    {
        $this->showImportModal = !$this->showImportModal;
        $this->importFile = null;
        if ($this->showImportModal) {
            $this->showFormModal = false;
            $this->reset(['deletingId', 'deletingName']);
        }
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new LocationsImport();
            $path = $this->importFile->store('imports');
            Excel::import($import, storage_path('app/private/' . $path));

            $this->importFile = null;
            session()->flash('success', 'Locations imported successfully.');
            $this->showImportModal = false;
        } catch (\Exception $e) {
            logger()->error('Location import failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Import failed due to a server error. Check the logs for details.');
        }
    }
}
