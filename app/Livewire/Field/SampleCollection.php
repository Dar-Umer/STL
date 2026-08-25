<?php

namespace App\Livewire\Field;

use App\Models\Farmer;
use App\Models\Lab;
use App\Models\Location;
use App\Models\Plot;
use App\Models\QrCode;
use App\Models\SoilSample;
use App\Models\SoilType;
use App\Models\Crop;
use App\Models\SampleRequest;
use App\Models\Target;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SampleCollection extends Component
{
    use AuthorizesRequests;

    public $step = 1;
    public $search = '';
    public $selectedFarmerId;
    public $newFarmerName, $newFarmerPhone, $newFarmerParentage, $newFarmerAadhar, $newFarmerStateId, $newFarmerDistrictId, $newFarmerZoneId, $newFarmerPanchayatId, $newFarmerVillageId;
    public $showFarmerForm = false;
    public $editingFarmerId;
    public $selectedPlotId;
    public $newPlotName, $newPlotKhasra, $newPlotArea, $newPlotSoilTypeId, $newPlotCropId, $newPlotAge;
    public $showPlotForm = false;
    public $editingPlotId;
    public $qr_code;
    public $collected_at;
    public $sample_code;
    public $qrValid;
    public $qrError;
    public $labId;
    public $requestId;

    public function mount()
    {
        $this->collected_at = now()->format('Y-m-d\TH:i');
        $this->labId = auth()->user()->lab_id;

        $this->requestId = request('request_id');
        if ($this->requestId) {
            $sampleRequest = SampleRequest::where('request_id', $this->requestId)
                ->whereIn('status', ['pending', 'assigned'])
                ->first();

            if ($sampleRequest) {
                $farmer = Farmer::where('phone', $sampleRequest->farmer_phone)->first();

                if ($farmer) {
                    $this->selectedFarmerId = $farmer->id;
                } else {
                    $this->newFarmerName = $sampleRequest->farmer_name;
                    $this->newFarmerPhone = $sampleRequest->farmer_phone;

                    if ($sampleRequest->zone_id) {
                        $zone = Location::with('parent.parent')->find($sampleRequest->zone_id);
                        if ($zone && $zone->parent && $zone->parent->parent) {
                            $this->newFarmerStateId = $zone->parent->parent->id;
                            $this->newFarmerDistrictId = $zone->parent->id;
                            $this->newFarmerZoneId = $zone->id;
                        }
                    }

                    $this->showFarmerForm = true;
                }
            }
        }
    }

    public function updatedQrCode()
    {
        $this->validateQr();
    }

    public function render()
    {
        $farmers = collect();
        if ($this->step === 1 && strlen($this->search) >= 1) {
            $farmers = Farmer::where('lab_id', $this->labId)
                ->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%")
                      ->orWhere('aadhar_no', 'like', "%{$this->search}%");
                })
                ->orderBy('name')
                ->take(10)
                ->get();
        }

        $plots = collect();
        if ($this->selectedFarmerId) {
            $plots = Plot::with(['soilType', 'crop'])
                ->where('farmer_id', $this->selectedFarmerId)
                ->orderBy('plot_name')
                ->get();
        }

        $selectedFarmer = $this->selectedFarmerId ? Farmer::find($this->selectedFarmerId) : null;
        $selectedPlot = $this->selectedPlotId ? Plot::with(['soilType', 'crop'])->find($this->selectedPlotId) : null;

        $userZoneId = auth()->user()->zone_id;
        $zoneTarget = null;
        if ($userZoneId) {
            $zoneTarget = Target::active()->forZone($userZoneId)->first();
        }

        return view('livewire.field.sample-collection', [
            'farmers' => $farmers,
            'plots' => $plots,
            'selectedFarmer' => $selectedFarmer,
            'selectedPlot' => $selectedPlot,
            'soilTypes' => SoilType::where('is_active', true)->get(),
            'crops' => Crop::where('is_active', true)->get(),
            'labs' => auth()->user()->hasRole('Admin') ? Lab::orderBy('name')->get() : collect(),
            'states' => Location::where('type', 'state')->orderBy('name')->get(),
            'zoneTarget' => $zoneTarget,
            'hasNoActiveTarget' => $userZoneId && !$zoneTarget,
        ])->layout('layouts.app');
    }

    public function updatedLabId()
    {
        $this->reset(['search', 'selectedFarmerId', 'selectedPlotId', 'showFarmerForm', 'editingFarmerId', 'showPlotForm', 'editingPlotId',
            'newFarmerName', 'newFarmerPhone', 'newFarmerParentage', 'newFarmerAadhar', 'newFarmerStateId', 'newFarmerDistrictId', 'newFarmerZoneId', 'newFarmerPanchayatId', 'newFarmerVillageId',
            'newPlotName', 'newPlotKhasra', 'newPlotArea', 'newPlotSoilTypeId', 'newPlotCropId', 'newPlotAge',
            'qr_code', 'qrValid', 'qrError']);
        $this->step = 1;
    }

    public function updatedSearch()
    {
        $this->selectedFarmerId = null;
        $this->selectedPlotId = null;
        $this->showFarmerForm = false;
        $this->step = 1;
    }

    public function updatedNewFarmerStateId($value)
    {
        $this->newFarmerDistrictId = $this->newFarmerZoneId = $this->newFarmerPanchayatId = $this->newFarmerVillageId = null;
    }

    public function updatedNewFarmerDistrictId($value)
    {
        $this->newFarmerZoneId = $this->newFarmerPanchayatId = $this->newFarmerVillageId = null;
    }

    public function updatedNewFarmerZoneId($value)
    {
        $this->newFarmerPanchayatId = null;
        $this->newFarmerVillageId = null;
    }

    public function updatedNewFarmerPanchayatId($value)
    {
        $this->newFarmerVillageId = null;
    }

    public function updatedShowFarmerForm($value)
    {
        if (!$value) {
            $this->editingFarmerId = null;
        }
    }

    public function updatedShowPlotForm($value)
    {
        if (!$value) {
            $this->editingPlotId = null;
        }
    }

    public function getNewDistrictsProperty()
    {
        return $this->newFarmerStateId ? Location::where('type', 'district')->where('parent_id', $this->newFarmerStateId)->get() : collect();
    }

    public function getNewZonesProperty()
    {
        return $this->newFarmerDistrictId ? Location::where('type', 'zone')->where('parent_id', $this->newFarmerDistrictId)->get() : collect();
    }

    public function getNewPanchayatsProperty()
    {
        return $this->newFarmerZoneId ? Location::where('type', 'panchayat')->where('parent_id', $this->newFarmerZoneId)->get() : collect();
    }

    public function getNewVillagesProperty()
    {
        return $this->newFarmerPanchayatId ? Location::where('type', 'village')->where('parent_id', $this->newFarmerPanchayatId)->get() : collect();
    }

    public function selectFarmer($id)
    {
        $this->selectedFarmerId = $id;
        $this->selectedPlotId = null;
        $this->showFarmerForm = false;
        $this->editingFarmerId = null;
    }

    public function editFarmer($id)
    {
        $farmer = Farmer::findOrFail($id);
        $this->newFarmerName = $farmer->name;
        $this->newFarmerPhone = $farmer->phone;
        $this->newFarmerParentage = $farmer->parentage;
        $this->newFarmerAadhar = $farmer->aadhar_no;
        $this->newFarmerStateId = $farmer->state_id;
        $this->newFarmerDistrictId = $farmer->district_id;
        $this->newFarmerZoneId = $farmer->zone_id;
        $this->newFarmerPanchayatId = $farmer->panchayat_id;
        $this->newFarmerVillageId = $farmer->village_id;
        $this->editingFarmerId = $id;
        $this->showFarmerForm = true;
        $this->search = '';
    }

    public function saveFarmer()
    {
        if (!$this->labId) {
            session()->flash('error', 'Please select a lab first.');
            return;
        }

        $this->validate([
            'newFarmerName' => 'required|min:2',
            'newFarmerPhone' => 'required|min:10',
            'newFarmerParentage' => 'nullable',
            'newFarmerAadhar' => 'nullable',
            'newFarmerStateId' => 'required|exists:locations,id',
            'newFarmerDistrictId' => 'required|exists:locations,id',
            'newFarmerZoneId' => 'nullable|exists:locations,id',
            'newFarmerPanchayatId' => 'nullable|exists:locations,id',
            'newFarmerVillageId' => 'nullable|exists:locations,id',
        ]);

        $data = [
            'name' => $this->newFarmerName,
            'phone' => $this->newFarmerPhone,
            'parentage' => $this->newFarmerParentage,
            'aadhar_no' => $this->newFarmerAadhar,
            'state_id' => $this->newFarmerStateId,
            'district_id' => $this->newFarmerDistrictId,
            'zone_id' => $this->newFarmerZoneId,
            'panchayat_id' => $this->newFarmerPanchayatId,
            'village_id' => $this->newFarmerVillageId,
        ];

        if ($this->editingFarmerId) {
            $farmer = Farmer::findOrFail($this->editingFarmerId);
            $farmer->update($data);
        } else {
            $data['lab_id'] = $this->labId;
            $data['created_by'] = auth()->id();
            $farmer = Farmer::create($data);
        }

        $this->selectedFarmerId = $farmer->id;
        $this->selectedPlotId = null;
        $this->reset(['newFarmerName', 'newFarmerPhone', 'newFarmerParentage', 'newFarmerAadhar', 'newFarmerStateId', 'newFarmerDistrictId', 'newFarmerZoneId', 'newFarmerPanchayatId', 'newFarmerVillageId', 'showFarmerForm', 'editingFarmerId', 'search']);
    }

    public function selectPlot($id)
    {
        $this->selectedPlotId = $id;
        $this->showPlotForm = false;
        $this->editingPlotId = null;
    }

    public function editPlot($id)
    {
        $plot = Plot::findOrFail($id);
        $this->newPlotName = $plot->plot_name;
        $this->newPlotKhasra = $plot->khasra_no;
        $this->newPlotArea = $plot->area_kanals;
        $this->newPlotSoilTypeId = $plot->soil_type_id;
        $this->newPlotCropId = $plot->crop_id;
        $this->newPlotAge = $plot->age_of_plants;
        $this->editingPlotId = $id;
        $this->showPlotForm = true;
    }

    public function savePlot()
    {
        $this->validate([
            'newPlotName' => 'required|min:2',
            'newPlotKhasra' => 'nullable',
            'newPlotArea' => 'nullable|numeric',
            'newPlotSoilTypeId' => 'nullable|exists:soil_types,id',
            'newPlotCropId' => 'nullable|exists:crops,id',
            'newPlotAge' => 'nullable|numeric|min:0',
        ]);

        $data = [
            'plot_name' => $this->newPlotName,
            'khasra_no' => $this->newPlotKhasra,
            'area_kanals' => $this->newPlotArea,
            'soil_type_id' => $this->newPlotSoilTypeId,
            'crop_id' => $this->newPlotCropId,
            'age_of_plants' => $this->newPlotAge,
        ];

        if ($this->editingPlotId) {
            $plot = Plot::findOrFail($this->editingPlotId);
            $plot->update($data);
        } else {
            $farmer = Farmer::find($this->selectedFarmerId);
            $data['farmer_id'] = $this->selectedFarmerId;
            $data['state_id'] = $farmer?->state_id;
            $data['district_id'] = $farmer?->district_id;
            $data['zone_id'] = $farmer?->zone_id;
            $data['panchayat_id'] = $farmer?->panchayat_id;
            $data['village_id'] = $farmer?->village_id;
            $plot = Plot::create($data);
        }

        $this->selectedPlotId = $plot->id;
        $this->reset(['newPlotName', 'newPlotKhasra', 'newPlotArea', 'newPlotSoilTypeId', 'newPlotCropId', 'newPlotAge', 'showPlotForm', 'editingPlotId']);
    }

    public function validateQr()
    {
        $this->qrError = '';
        $this->qrValid = null;

        if (!$this->qr_code) {
            return;
        }

        $qr = QrCode::where('code', $this->qr_code)->first();
        if (!$qr) {
            $this->qrValid = false;
            $this->qrError = 'QR code not found in system.';
            return;
        }
        if ($qr->is_used) {
            $this->qrValid = false;
            $this->qrError = 'QR code already used.';
            return;
        }
        if ($qr->lab_id != $this->labId) {
            $this->qrValid = false;
            $this->qrError = 'QR code belongs to a different lab.';
            return;
        }
        $this->qrValid = true;
    }

    public function nextStep()
    {
        if (!$this->labId) {
            session()->flash('error', 'Please select a lab.');
            return;
        }
        if ($this->step === 1) {
            if (!$this->selectedFarmerId) {
                session()->flash('error', 'Please select or register a farmer.');
                return;
            }
            $this->step = 2;
        } elseif ($this->step === 2) {
            if (!$this->selectedPlotId) {
                session()->flash('error', 'Please select or add a plot.');
                return;
            }
            $this->collected_at = now()->format('Y-m-d\TH:i');
            $this->step = 3;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function save()
    {
        if (!$this->labId) {
            session()->flash('error', 'Please select a lab.');
            return;
        }
        if (!$this->selectedFarmerId || !$this->selectedPlotId) {
            session()->flash('error', 'Missing farmer or plot selection.');
            return;
        }

        $this->validate([
            'qr_code' => 'required',
            'collected_at' => 'required|date',
        ]);

        $qr = QrCode::where('code', $this->qr_code)->first();
        if (!$qr || $qr->is_used) {
            session()->flash('error', 'Invalid or already used QR code.');
            return;
        }

        $farmer = Farmer::find($this->selectedFarmerId);
        if ($farmer) {
            if (!$farmer->zone_id) {
                session()->flash('error', 'Farmer has no zone assigned. Cannot determine target.');
                return;
            }
            $activeTarget = Target::active()->forZone($farmer->zone_id)->first();
            if (!$activeTarget) {
                session()->flash('error', 'No active target set for this zone period. Contact your administrator.');
                return;
            }
            if ($activeTarget->isExceeded()) {
                session()->flash('error', 'Collection limit reached for this zone. Target of ' . $activeTarget->target_count . ' samples has been met (' . $activeTarget->collectedCount() . ' collected).');
                return;
            }
        }

        $sampleCode = DB::transaction(function () use ($farmer, $qr) {
            $lab = Lab::where('id', $this->labId)->lockForUpdate()->firstOrFail();
            $zoneCode = $farmer->zone?->code ?? 'XX';
            $count = SoilSample::where('lab_id', $this->labId)
                ->whereHas('farmer', fn($q) => $q->where('zone_id', $farmer->zone_id))
                ->count() + 1;
            $code = $lab->code . '-' . $zoneCode . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $sample = SoilSample::create([
                'farmer_id' => $this->selectedFarmerId,
                'plot_id' => $this->selectedPlotId,
                'sample_code' => $code,
                'source' => 'field',
                'collected_by' => auth()->id(),
                'collected_at' => $this->collected_at,
                'status' => 'collected',
                'lab_id' => $this->labId,
                'qr_code_path' => $this->qr_code,
            ]);

            $qr->update([
                'is_used' => true,
                'used_by' => auth()->id(),
                'used_at' => now(),
                'soil_sample_id' => $sample->id,
            ]);

            return $sample;
        });

        if ($this->requestId) {
            $sampleRequest = SampleRequest::where('request_id', $this->requestId)
                ->whereIn('status', ['pending', 'assigned'])
                ->first();
            if ($sampleRequest) {
                $sampleRequest->update([
                    'soil_sample_id' => $sampleCode->id,
                    'status' => 'collected',
                ]);
            }
        }

        session()->flash('success', "Sample {$sampleCode->sample_code} collected successfully.");
        $this->reset();
        $this->mount();
    }
}
