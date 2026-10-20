<?php

namespace App\Livewire\Admin;

use App\Models\Farmer;
use App\Models\Lab;
use App\Models\Location;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class FarmerManagement extends Component
{
    use AuthorizesRequests, WithPagination;

    public $farmerId;
    public $name, $parentage, $phone, $aadhar_no, $lab_id;
    public $state_id, $district_id, $zone_id, $panchayat_id, $village_id;
    public $showForm = false;
    public $search = '';

    protected function rules()
    {
        return [
            'name' => 'required',
            'parentage' => 'nullable',
            'phone' => 'required',
            'aadhar_no' => 'nullable|unique:farmers,aadhar_no,' . $this->farmerId,
            'lab_id' => 'required|exists:labs,id',
            'state_id' => 'required|exists:locations,id',
            'district_id' => 'required|exists:locations,id',
            'zone_id' => 'nullable|exists:locations,id',
            'panchayat_id' => 'nullable|exists:locations,id',
            'village_id' => 'nullable|exists:locations,id',
        ];
    }

    public function render()
    {
        $user = auth()->user();
        $isLabManager = $user->hasRole('Lab Manager');

        $farmers = Farmer::with(['lab', 'state', 'district', 'zone', 'panchayat', 'village'])
            ->where(function($q) use ($user, $isLabManager) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });

        if ($isLabManager) {
            $farmers->where('lab_id', $user->lab_id);
        }

        $farmers = $farmers->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.farmer-management', [
            'farmers' => $farmers,
            'labs' => $isLabManager ? collect() : Lab::where('is_active', true)->get(),
            'states' => Location::where('type', 'state')->orderBy('name')->get(),
            'isLabManager' => $isLabManager,
        ])->layout('layouts.app');
    }

    public function updatedStateId($value)
    {
        $this->district_id = $this->zone_id = $this->panchayat_id = $this->village_id = null;
    }

    public function updatedDistrictId($value)
    {
        $this->zone_id = $this->panchayat_id = $this->village_id = null;
    }

    public function updatedZoneId($value)
    {
        $this->panchayat_id = null;
        $this->village_id = null;
    }

    public function updatedPanchayatId($value)
    {
        $this->village_id = null;
    }

    public function getDistrictsProperty()
    {
        return $this->state_id ? Location::where('type', 'district')->where('parent_id', $this->state_id)->get() : collect();
    }

    public function getZonesProperty()
    {
        return $this->district_id ? Location::where('type', 'zone')->where('parent_id', $this->district_id)->get() : collect();
    }

    public function getPanchayatsProperty()
    {
        return $this->zone_id ? Location::where('type', 'panchayat')->where('parent_id', $this->zone_id)->get() : collect();
    }

    public function getVillagesProperty()
    {
        return $this->panchayat_id ? Location::where('type', 'village')->where('parent_id', $this->panchayat_id)->get() : collect();
    }

    public function create()
    {
        $user = auth()->user();
        $this->reset();
        if ($user->hasRole('Lab Manager')) {
            $this->lab_id = $user->lab_id;
        }
        $this->showForm = true;
    }

    public function edit($id)
    {
        $user = auth()->user();
        $farmer = Farmer::findOrFail($id);

        if ($user->hasRole('Lab Manager') && $farmer->lab_id !== $user->lab_id) {
            abort(403);
        }

        $this->farmerId = $farmer->id;
        $this->name = $farmer->name;
        $this->parentage = $farmer->parentage;
        $this->phone = $farmer->phone;
        $this->aadhar_no = $farmer->aadhar_no;
        $this->lab_id = $farmer->lab_id;
        $this->state_id = $farmer->state_id;
        $this->district_id = $farmer->district_id;
        $this->zone_id = $farmer->zone_id;
        $this->panchayat_id = $farmer->panchayat_id;
        $this->village_id = $farmer->village_id;
        $this->showForm = true;
    }

    public function save()
    {
        $user = auth()->user();

        if ($user->hasRole('Lab Manager')) {
            $this->lab_id = $user->lab_id;
        }

        $this->zone_id = $this->zone_id ?: null;
        $this->panchayat_id = $this->panchayat_id ?: null;
        $this->village_id = $this->village_id ?: null;
        $this->aadhar_no = $this->aadhar_no ? trim($this->aadhar_no) : null;
        $this->parentage = $this->parentage ? trim($this->parentage) : null;

        $this->validate();

        if ($this->farmerId) {
            $farmer = Farmer::findOrFail($this->farmerId);
            if ($user->hasRole('Lab Manager') && $farmer->lab_id !== $user->lab_id) {
                abort(403);
            }
        }

        Farmer::updateOrCreate(['id' => $this->farmerId], [
            'name' => trim($this->name),
            'parentage' => $this->parentage,
            'phone' => trim($this->phone),
            'aadhar_no' => $this->aadhar_no,
            'lab_id' => $this->lab_id,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'zone_id' => $this->zone_id,
            'panchayat_id' => $this->panchayat_id,
            'village_id' => $this->village_id,
            'created_by' => auth()->id(),
        ]);
        session()->flash('success', $this->farmerId ? 'Farmer updated.' : 'Farmer registered successfully.');
        $this->reset(['farmerId', 'name', 'parentage', 'phone', 'aadhar_no', 'lab_id', 'state_id', 'district_id', 'zone_id', 'panchayat_id', 'village_id', 'showForm']);
    }

    public function cancel()
    {
        $this->reset(['farmerId', 'name', 'parentage', 'phone', 'aadhar_no', 'lab_id', 'state_id', 'district_id', 'zone_id', 'panchayat_id', 'village_id', 'showForm']);
    }
}
