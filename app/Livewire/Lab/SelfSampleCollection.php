<?php

namespace App\Livewire\Lab;

use App\Models\Crop;
use App\Models\Farmer;
use App\Models\Lab;
use App\Models\Location;
use App\Models\Plot;
use App\Models\SoilSample;
use App\Models\SampleStatusHistory;
use App\Models\SoilType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SelfSampleCollection extends Component
{
    public $showForm = true;

    public $phone = '';
    public $foundFarmer = null;
    public $name = '';
    public $parentage = '';
    public $aadhar = '';
    public $stateId = '';
    public $districtId = '';
    public $zoneId = '';
    public $panchayatId = '';
    public $villageId = '';
    public $plotName = '';
    public $khasraNo = '';
    public $areaKanals = '';
    public $soilTypeId = '';
    public $cropId = '';
    public $ageOfPlants = '';
    public $plotId = '';

    public $states = [];
    public $districts = [];
    public $zones = [];
    public $panchayats = [];
    public $villages = [];

    public function mount()
    {
        $this->states = Location::where('type', 'state')->orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->reset([
            'phone', 'foundFarmer',
            'name', 'parentage', 'aadhar',
            'stateId', 'districtId', 'zoneId',
            'panchayatId', 'villageId',
            'plotName', 'khasraNo', 'areaKanals',
            'soilTypeId', 'cropId', 'ageOfPlants',
            'plotId',
            'districts', 'zones', 'panchayats', 'villages',
        ]);
        $this->showForm = true;
    }

    public function lookupFarmer()
    {
        $this->reset([
            'foundFarmer', 'name', 'parentage', 'aadhar',
            'stateId', 'districtId', 'zoneId',
            'panchayatId', 'villageId', 'plotId',
            'plotName', 'khasraNo', 'areaKanals',
            'soilTypeId', 'cropId', 'ageOfPlants',
            'districts', 'zones', 'panchayats', 'villages',
        ]);

        if (!trim($this->phone)) return;

        $farmer = Farmer::where('phone', $this->phone)->first();
        if ($farmer) {
            $this->foundFarmer = $farmer;
            $this->name = $farmer->name;
            $this->parentage = $farmer->parentage;
            $this->aadhar = $farmer->aadhar_no;
            $this->stateId = (string) $farmer->state_id;
            $this->districtId = (string) $farmer->district_id;
            $this->zoneId = (string) $farmer->zone_id;
            $this->panchayatId = (string) $farmer->panchayat_id;
            $this->villageId = (string) $farmer->village_id;
            $this->updatedStateId();
            $this->updatedDistrictId();
            $this->updatedZoneId();
            $this->updatedPanchayatId();
        }
    }

    public function updatedStateId($value = null)
    {
        $value ??= $this->stateId;
        $this->districts = $value
            ? Location::where('type', 'district')->where('parent_id', $value)->orderBy('name')->get()
            : collect();
        $this->districtId = '';
        $this->zoneId = '';
        $this->panchayatId = '';
        $this->villageId = '';
    }

    public function updatedDistrictId($value = null)
    {
        $value ??= $this->districtId;
        $this->zones = $value
            ? Location::where('type', 'zone')->where('parent_id', $value)->orderBy('name')->get()
            : collect();
        $this->zoneId = '';
        $this->panchayatId = '';
        $this->villageId = '';
    }

    public function updatedZoneId($value = null)
    {
        $value ??= $this->zoneId;
        $this->panchayats = $value
            ? Location::where('type', 'panchayat')->where('parent_id', $value)->orderBy('name')->get()
            : collect();
        $this->panchayatId = '';
        $this->villageId = '';
    }

    public function updatedPanchayatId($value = null)
    {
        $value ??= $this->panchayatId;
        $this->villages = $value
            ? Location::where('type', 'village')->where('parent_id', $value)->orderBy('name')->get()
            : collect();
        $this->villageId = '';
    }

    public function save()
    {
        $this->validate([
            'phone' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'stateId' => 'required|exists:locations,id',
            'districtId' => 'required|exists:locations,id',
            'plotName' => 'required|string|max:255',
            'khasraNo' => 'required|string|max:255',
            'areaKanals' => 'required|numeric|min:0',
            'soilTypeId' => 'required|exists:soil_types,id',
            'cropId' => 'required|exists:crops,id',
            'ageOfPlants' => 'required|numeric|min:0',
        ]);

        $labId = auth()->user()->lab_id;
        if (!$labId) {
            session()->flash('error', 'No lab assigned to your account.');
            return;
        }

        try {
            DB::beginTransaction();

            $farmer = Farmer::where('phone', $this->phone)->first();
            if (!$farmer) {
                $farmer = Farmer::create([
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'parentage' => $this->parentage ?: null,
                    'aadhar_no' => $this->aadhar ?: null,
                    'state_id' => $this->stateId,
                    'district_id' => $this->districtId,
                    'zone_id' => $this->zoneId ?: null,
                    'panchayat_id' => $this->panchayatId ?: null,
                    'village_id' => $this->villageId ?: null,
                    'lab_id' => $labId,
                    'created_by' => auth()->id(),
                ]);
            }

            $plot = Plot::create([
                'farmer_id' => $farmer->id,
                'plot_name' => $this->plotName,
                'khasra_no' => $this->khasraNo,
                'area_kanals' => $this->areaKanals,
                'soil_type_id' => $this->soilTypeId,
                'crop_id' => $this->cropId,
                'age_of_plants' => $this->ageOfPlants,
                'state_id' => $this->stateId,
                'district_id' => $this->districtId,
                'zone_id' => $this->zoneId ?: null,
                'panchayat_id' => $this->panchayatId ?: null,
                'village_id' => $this->villageId ?: null,
            ]);

            $lab = Lab::where('id', $labId)->lockForUpdate()->firstOrFail();
            $count = SoilSample::where('lab_id', $labId)->where('source', 'self')->count() + 1;
            $code = 'SELF-' . $lab->code . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $sample = SoilSample::create([
                'farmer_id' => $farmer->id,
                'plot_id' => $plot->id,
                'sample_code' => $code,
                'source' => 'self',
                'collected_by' => auth()->id(),
                'collected_at' => now(),
                'status' => 'received',
                'received_at' => now(),
                'lab_id' => $labId,
            ]);

            SampleStatusHistory::create([
                'soil_sample_id' => $sample->id,
                'from_status' => null,
                'to_status' => 'received',
                'changed_by' => auth()->id(),
                'notes' => 'Self-sample collected at lab',
            ]);

            DB::commit();

            $this->resetForm();
            $this->showForm = false;
            session()->flash('success', "Self-sample {$code} collected successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Self-sample failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Self-sample collection failed due to a server error.');
        }
    }

    public function render()
    {
        return view('livewire.lab.self-sample-collection', [
            'selfStates' => $this->states,
            'selfDistricts' => $this->districts,
            'selfZones' => $this->zones,
            'selfPanchayats' => $this->panchayats,
            'selfVillages' => $this->villages,
            'selfSoilTypes' => SoilType::where('is_active', true)->orderBy('name')->get(),
            'selfCrops' => Crop::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
