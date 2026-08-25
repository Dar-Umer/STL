<div class="container-fluid py-3">
<style>
:root { --step-color: #28a745; }
.wizard-step { transition: all .35s ease; }
.wizard-step .step-num { width: 38px; height: 38px; font-size: .95rem; font-weight: 700; transition: all .3s; }
.wizard-step.active .step-num { box-shadow: 0 0 0 5px rgba(40,167,69,.2); }
.wizard-step.completed .step-num { background: var(--step-color) !important; }
.wizard-connector { flex: 1; height: 3px; background: #e9ecef; margin: 0 8px; margin-bottom: 22px; border-radius: 2px; transition: background .3s; }
.wizard-connector.done { background: var(--step-color); }
.list-farmer { border-left: 4px solid transparent; transition: all .2s; }
.list-farmer:hover { border-left-color: #28a745; background: #f8fff8; }
.list-farmer.active { border-left-color: #28a745; background: #28a745 !important; }
.plot-card { transition: all .25s; border: 2px solid transparent; }
.plot-card:hover { border-color: #28a745; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.08) !important; }
.plot-card.selected { border-color: #28a745; background: #f0fdf4 !important; }
.plot-card.selected .card-header-icon { background: #28a745 !important; color: #fff !important; }
.qr-dropzone { border: 2px dashed #dee2e6; border-radius: 12px; padding: 32px; text-align: center; transition: all .2s; cursor: pointer; }
.qr-dropzone:hover { border-color: #28a745; background: #f8fff8; }
</style>


    {{-- Success banner --}}
    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-lg d-flex align-items-center justify-content-between mb-4">
        <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
        <a href="{{ route('field.collect-sample') }}" class="btn btn-light btn-sm rounded-pill px-3">
            <i class="fas fa-plus-circle mr-1"></i> Collect Another
        </a>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-lg mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
    </div>
    @endif

    @if(!session('success'))

    {{-- Admin lab selector --}}
    @if(auth()->user()->hasRole('Admin'))
    <div class="row mb-4">
        <div class="col-md-4 mx-auto">
            <select wire:model.live="labId" class="form-control form-control-lg rounded-pill shadow-sm border-0 bg-white">
                <option value="">— Select Lab —</option>
                @foreach($labs as $l)
                <option value="{{ $l->id }}">{{ $l->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @endif

    {{-- No active target warning --}}
    @if($hasNoActiveTarget)
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="alert alert-warning border-0 shadow-sm rounded-lg mb-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3 text-warning"></i>
                    <div>
                        <h6 class="font-weight-bold mb-1"><i class="fas fa-bullseye mr-2"></i>No Active Target</h6>
                        <p class="mb-0 small">No active target set for your zone. Sample collection is currently blocked. Contact your administrator to set a target.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Zone target banner for Field Official --}}
    @if($zoneTarget)
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-lg bg-gradient-success">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <small class="text-uppercase font-weight-bold text-muted"><i class="fas fa-bullseye mr-1"></i> Your Zone Target</small>
                            <h5 class="mb-0 font-weight-bold">{{ $zoneTarget->zone->name ?? 'N/A' }}</h5>
                        </div>
                        <div class="d-flex align-items-center text-center mx-3">
                            <div class="px-3 border-right">
                                <div class="font-weight-bold h5 mb-0 text-success">{{ number_format($zoneTarget->target_count) }}</div>
                                <small class="text-muted">Target</small>
                            </div>
                            <div class="px-3 border-right">
                                <div class="font-weight-bold h5 mb-0 text-primary">{{ number_format($zoneTarget->achievedCount()) }}</div>
                                <small class="text-muted">Achieved</small>
                            </div>
                            <div class="px-3">
                                @php $remaining = $zoneTarget->remainingCount(); @endphp
                                <div class="font-weight-bold h5 mb-0 {{ $remaining > 0 ? 'text-info' : 'text-danger' }}">{{ number_format($remaining) }}</div>
                                <small class="text-muted">Left</small>
                            </div>
                        </div>
                        <div class="flex-fill mx-3" style="min-width:120px">
                            @php $pct = $zoneTarget->progressPercent(); @endphp
                            <div class="progress" style="height:10px">
                                <div class="progress-bar bg-{{ $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger') }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <small class="text-muted mt-1 d-block text-right">{{ $pct }}% complete</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Step indicator --}}
    <div class="d-flex align-items-center justify-content-center mb-4 px-3">
        @foreach([1 => ['Farmer','fa-user'], 2 => ['Plot','fa-map-marked-alt'], 3 => ['Sample','fa-flask']] as $num => list($label,$icon))
        <div class="wizard-step d-flex align-items-center {{ $step > $num ? 'completed' : ($step === $num ? 'active' : '') }}" style="opacity:{{ $step < $num ? '.5' : '1' }}">
            <div class="step-num rounded-circle d-flex align-items-center justify-content-center
                {{ $step > $num ? 'bg-success text-white' : ($step === $num ? 'bg-success text-white' : 'bg-light text-muted') }}">
                @if($step > $num) <i class="fas fa-check" style="font-size:.75rem"></i>
                @else <i class="fas {{ $icon }}" style="font-size:.85rem"></i>
                @endif
            </div>
            <span class="ml-2 small font-weight-bold {{ $step >= $num ? 'text-dark' : 'text-muted' }} d-none d-md-inline">{{ $label }}</span>
        </div>
        @if($num < 3)
        <div class="wizard-connector {{ $step > $num ? 'done' : '' }}"></div>
        @endif
        @endforeach
    </div>

    {{-- Step title --}}
    <div class="text-center mb-4">
        <h4 class="font-weight-bold mb-1">
            @switch($step)
                @case(1) <i class="fas fa-user text-success mr-2"></i> Select Farmer @break
                @case(2) <i class="fas fa-map-marked-alt text-success mr-2"></i> Select Plot @break
                @case(3) <i class="fas fa-flask text-success mr-2"></i> Sample Details @break
            @endswitch
        </h4>
        <p class="text-muted mb-0 small">
            @switch($step)
                @case(1) Search and select a farmer or register a new one @break
                @case(2) Choose a plot for this farmer or add a new plot @break
                @case(3) Scan QR code, set collection date, and save @break
            @endswitch
        </p>
    </div>

    {{-- ===== STEP 1: FARMER ===== --}}
    @if($step === 1)
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4">
                    {{-- Search --}}
                    <div class="input-group input-group-lg mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-success"></i></span>
                        </div>
                        <input wire:model.live="search" class="form-control border-left-0 pl-0" placeholder="Search farmer by name or phone..." autofocus>
                        @if(strlen($search))
                        <div class="input-group-append">
                            <button wire:click="$set('search','')" class="btn btn-outline-secondary border-left-0"><i class="fas fa-times"></i></button>
                        </div>
                        @endif
                    </div>

                    {{-- Results --}}
                    @if(strlen($search) >= 1 && $farmers->count())
                    <div style="max-height:300px;overflow-y:auto">
                        @foreach($farmers as $farmer)
                        <a href="#" wire:click.prevent="selectFarmer({{ $farmer->id }})"
                           class="list-farmer d-flex align-items-center p-3 mb-2 rounded-lg text-decoration-none
                           {{ $selectedFarmerId === $farmer->id ? 'active text-white' : 'bg-white border' }}">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold
                                {{ $selectedFarmerId === $farmer->id ? 'bg-white text-success' : 'bg-success text-white' }}"
                                style="width:44px;height:44px;font-size:1.1rem;flex-shrink:0">
                                {{ substr($farmer->name,0,1) }}
                            </div>
                            <div class="flex-fill">
                                <strong>{{ $farmer->name }}</strong>
                                <small class="d-block {{ $selectedFarmerId === $farmer->id ? 'text-white-50' : 'text-muted' }}">
                                    <i class="fas fa-phone mr-1"></i>{{ $farmer->phone }}
                                    {{ $farmer->parentage ? '| '.$farmer->parentage : '' }}
                                </small>
                            </div>
                            <span class="badge badge-pill {{ $selectedFarmerId === $farmer->id ? 'badge-light' : 'badge-success' }} ml-2">
                                {{ $farmer->plots_count ?? 0 }} plots
                            </span>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- No results --}}
                    @if(strlen($search) >= 1 && !$farmers->count() && !$showFarmerForm)
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted" style="font-size:3rem"><i class="fas fa-user-plus"></i></div>
                        <p class="text-muted mb-1">No farmer found for <strong>"{{ $search }}"</strong></p>
                        <button wire:click="$set('showFarmerForm', true)" class="btn btn-success rounded-pill px-4 mt-2 shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Register New Farmer
                        </button>
                    </div>
                    @endif

                    {{-- Selected farmer --}}
                    @if($selectedFarmer && !$showFarmerForm)
                    <div class="bg-light rounded-lg p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width:48px;height:48px;flex-shrink:0">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <strong class="d-block">{{ $selectedFarmer->name }}</strong>
                            <small class="text-muted">{{ $selectedFarmer->phone }} {{ $selectedFarmer->parentage ? '/ '.$selectedFarmer->parentage : '' }}</small>
                        </div>
                        <button wire:click="$set('selectedFarmerId', null)" class="btn btn-outline-secondary btn-sm rounded-pill ml-auto">
                            <i class="fas fa-redo mr-1"></i> Change
                        </button>
                        <button wire:click="editFarmer({{ $selectedFarmer->id }})" class="btn btn-outline-primary btn-sm rounded-pill ml-1">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                    </div>
                    @endif

                    {{-- Register / Edit farmer form --}}
                    @if($showFarmerForm && (!$selectedFarmerId || $editingFarmerId))
                    <hr>
                    <h6 class="font-weight-bold mb-4"><i class="fas fa-user text-success mr-2"></i> {{ $editingFarmerId ? 'Edit' : 'Register New' }} Farmer</h6>
                    <form wire:submit="saveFarmer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Farmer Name <span class="text-danger">*</span></label>
                                    <input wire:model="newFarmerName" class="form-control form-control-lg" placeholder="Full name">
                                    @error('newFarmerName') <small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Parentage</label>
                                    <input wire:model="newFarmerParentage" class="form-control form-control-lg" placeholder="Father / Husband name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Phone <span class="text-danger">*</span></label>
                                    <input wire:model="newFarmerPhone" class="form-control form-control-lg" placeholder="Phone number">
                                    @error('newFarmerPhone') <small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Aadhar No</label>
                                    <input wire:model="newFarmerAadhar" class="form-control form-control-lg" placeholder="Aadhar number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">State</label>
                                    <select wire:model.live="newFarmerStateId" class="form-control form-control-lg">
                                        <option value="">Select State</option>
                                        @foreach($states as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">District</label>
                                    <select wire:model.live="newFarmerDistrictId" class="form-control form-control-lg">
                                        <option value="">Select District</option>
                                        @foreach($this->newDistricts as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Zone</label>
                                    <select wire:model.live="newFarmerZoneId" class="form-control form-control-lg">
                                        <option value="">Select Zone</option>
                                        @foreach($this->newZones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Panchayat</label>
                                    <select wire:model.live="newFarmerPanchayatId" class="form-control form-control-lg">
                                        <option value="">Select Panchayat</option>
                                        @foreach($this->newPanchayats as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Village</label>
                                    <select wire:model="newFarmerVillageId" class="form-control form-control-lg">
                                        <option value="">Select Village</option>
                                        @foreach($this->newVillages as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="button" wire:click="$set('showFarmerForm', false)" class="btn btn-outline-secondary btn-block rounded-pill">Cancel</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm"><i class="fas fa-save mr-1"></i> {{ $editingFarmerId ? 'Update' : 'Save' }}</button>
                            </div>
                        </div>
                    </form>
                    @endif

                    {{-- Register button when results visible --}}
                    @if(strlen($search) >= 1 && $farmers->count() && !$showFarmerForm && !$selectedFarmerId)
                    <div class="text-center mt-3">
                        <button wire:click="$set('showFarmerForm', true)" class="btn btn-outline-success rounded-pill px-4">
                            <i class="fas fa-plus mr-1"></i> Register New Farmer
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== STEP 2: PLOT ===== --}}
    @if($step === 2)
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Farmer badge --}}
            @if($selectedFarmer)
            <div class="bg-light rounded-lg p-3 d-flex align-items-center mb-4 shadow-sm">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width:40px;height:40px;flex-shrink:0;font-size:.8rem">
                    {{ substr($selectedFarmer->name,0,1) }}
                </div>
                <div class="flex-fill">
                    <strong>{{ $selectedFarmer->name }}</strong>
                    <small class="text-muted d-block"><i class="fas fa-phone mr-1"></i>{{ $selectedFarmer->phone }}</small>
                </div>
                <button wire:click="previousStep" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="fas fa-chevron-left mr-1"></i> Change</button>
                <button wire:click="editFarmer({{ $selectedFarmer->id }})" class="btn btn-sm btn-outline-primary rounded-pill ml-1"><i class="fas fa-edit mr-1"></i></button>
            </div>
            @endif

            {{-- Plot grid --}}
            @if($plots->count())
            <div class="row">
                @foreach($plots as $plot)
                <div class="col-md-6 mb-3">
                    <div class="card plot-card h-100 border-0 shadow-sm rounded-lg {{ $selectedPlotId === $plot->id ? 'selected' : '' }}"
                        style="cursor:pointer" wire:click="selectPlot({{ $plot->id }})">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="card-header-icon rounded-circle d-flex align-items-center justify-content-center mr-3
                                    {{ $selectedPlotId === $plot->id ? 'bg-success text-white' : 'bg-light text-muted' }}"
                                    style="width:42px;height:42px;flex-shrink:0;font-size:1.1rem">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="font-weight-bold mb-1">{{ $plot->plot_name }}</h6>
                                    <small class="text-muted d-block">
                                        {{ $plot->khasra_no ? 'Khasra: '.$plot->khasra_no : '' }}
                                        {{ $plot->area_kanals ? ($plot->khasra_no ? ' | ' : '').$plot->area_kanals.' kanals' : '' }}
                                    </small>
                                </div>
                                @if($selectedPlotId === $plot->id)
                                <span class="badge badge-success rounded-pill"><i class="fas fa-check mr-1"></i>Selected</span>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap">
                                @if($plot->soilType)
                                <span class="badge badge-warning rounded-pill mr-2 px-3 py-1"><i class="fas fa-droplet mr-1"></i>{{ $plot->soilType->name }}</span>
                                @endif
                                @if($plot->crop)
                                <span class="badge badge-info rounded-pill mr-2 px-3 py-1"><i class="fas fa-seedling mr-1"></i>{{ $plot->crop->name }}</span>
                                @endif
                                @if($plot->age_of_plants)
                                <span class="badge badge-secondary rounded-pill mr-2 px-3 py-1"><i class="fas fa-clock mr-1"></i>{{ $plot->age_of_plants }} yrs</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted mb-3" style="font-size:3rem"><i class="fas fa-map"></i></div>
                <p class="text-muted">No plots yet for this farmer.</p>
            </div>
            @endif

            {{-- Selected plot badge --}}
            @if($selectedPlot && !$showPlotForm)
            <div class="bg-light rounded-lg p-3 d-flex align-items-center mt-3 shadow-sm">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width:40px;height:40px;flex-shrink:0">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <strong class="d-block">{{ $selectedPlot->plot_name }}</strong>
                    <small class="text-muted">{{ $selectedPlot->khasra_no ? 'Khasra: '.$selectedPlot->khasra_no : '' }} {{ $selectedPlot->area_kanals ? '/ '.$selectedPlot->area_kanals.' kanals' : '' }}</small>
                </div>
                <button wire:click="$set('selectedPlotId', null)" class="btn btn-outline-secondary btn-sm rounded-pill ml-auto"><i class="fas fa-redo mr-1"></i> Change</button>
                <button wire:click="editPlot({{ $selectedPlot->id }})" class="btn btn-outline-primary btn-sm rounded-pill ml-1"><i class="fas fa-edit mr-1"></i> Edit</button>
            </div>
            @endif

            {{-- Add plot --}}
            @if(!$showPlotForm && !$selectedPlotId)
            <div class="text-center mt-3">
                <button wire:click="$set('showPlotForm', true)" class="btn btn-outline-success rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add New Plot
                </button>
            </div>
            @endif

            {{-- Add plot form --}}
            @if($showPlotForm)
            <div class="card border-0 shadow-sm rounded-lg mt-3">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-4"><i class="fas fa-plus-circle text-success mr-2"></i> {{ $editingPlotId ? 'Edit' : 'Add New' }} Plot</h6>
                    <form wire:submit="savePlot">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label class="small font-weight-bold text-secondary">Plot Name *</label><input wire:model="newPlotName" class="form-control form-control-lg" placeholder="e.g. North Field">@error('newPlotName') <small class="text-danger">{{ $message }}</small> @enderror</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label class="small font-weight-bold text-secondary">Khasra No</label><input wire:model="newPlotKhasra" class="form-control form-control-lg"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label class="small font-weight-bold text-secondary">Area (kanals)</label><input wire:model="newPlotArea" type="number" step="0.01" class="form-control form-control-lg"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label class="small font-weight-bold text-secondary">Soil Type</label>
                                    <select wire:model="newPlotSoilTypeId" class="form-control form-control-lg">
                                        <option value="">Select</option>
                                        @foreach($soilTypes as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label class="small font-weight-bold text-secondary">Crop</label>
                                    <select wire:model="newPlotCropId" class="form-control form-control-lg">
                                        <option value="">Select</option>
                                        @foreach($crops as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label class="small font-weight-bold text-secondary">Crop Age (yrs)</label><input wire:model="newPlotAge" type="number" step="0.1" class="form-control form-control-lg" placeholder="e.g. 2"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="button" wire:click="$set('showPlotForm', false)" class="btn btn-outline-secondary btn-block rounded-pill">Cancel</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm"><i class="fas fa-save mr-1"></i> {{ $editingPlotId ? 'Update' : 'Save' }} &amp; Select</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ===== STEP 3: SAMPLE ===== --}}
    @if($step === 3)
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Farmer + Plot summary --}}
            @if($selectedFarmer && $selectedPlot)
            <div class="bg-light rounded-lg p-3 d-flex align-items-center mb-4 shadow-sm">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width:40px;height:40px;flex-shrink:0;font-size:.8rem">
                    {{ substr($selectedFarmer->name,0,1) }}
                </div>
                <div class="flex-fill">
                    <strong>{{ $selectedFarmer->name }}</strong>
                    <small class="text-muted d-block"><i class="fas fa-map-marker-alt mr-1"></i>{{ $selectedPlot->plot_name }}</small>
                </div>
                <button wire:click="previousStep" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="fas fa-chevron-left mr-1"></i> Back</button>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4">
                    {{-- QR Code --}}
                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-secondary">QR Code <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <input wire:model.live="qr_code" class="form-control" placeholder="Scan or type QR code..." id="qr_input">
                            <div class="input-group-append">
                                <button type="button" id="scannerToggleBtn" onclick="toggleScanner()" class="btn btn-success px-3" title="Scan QR">
                                    <i class="fas fa-camera mr-1"></i> Scan
                                </button>
                                <button type="button" wire:click="validateQr" class="btn btn-outline-info px-3" title="Validate">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <div wire:ignore id="qr-reader" style="display:none;max-width:280px;margin-top:12px;background:#fff;border-radius:8px;padding:8px;"></div>
                        <div class="mt-2">
                            @if($qrValid === true)
                            <span class="badge badge-success rounded-pill px-3 py-1"><i class="fas fa-check-circle mr-1"></i> Valid QR</span>
                            @endif
                            @if($qrValid === false)
                            <span class="badge badge-danger rounded-pill px-3 py-1"><i class="fas fa-times-circle mr-1"></i> {{ $qrError }}</span>
                            @endif
                            @error('qr_code') <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Date + Sample Code --}}
                    <div class="row">
                        @if(auth()->user()->hasRole('Admin'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-secondary">Collection Date</label>
                                <input wire:model="collected_at" type="datetime-local" class="form-control form-control-lg">
                                @error('collected_at') <small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small> @enderror
                            </div>
                        </div>
                        @endif
                        <div class="{{ auth()->user()->hasRole('Admin') ? 'col-md-6' : 'col-md-12' }}">
                            <div class="form-group">
                                <label class="small font-weight-bold text-secondary">Sample Code</label>
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-barcode text-muted"></i></span>
                                    </div>
                                    <input class="form-control bg-light border-0 text-muted" value="Auto-generated on save" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Navigation footer --}}
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
                @if($step > 1)
                <button wire:click="previousStep" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left mr-1"></i> Previous
                </button>
                @else
                <div></div>
                @endif

                <small class="text-muted">Step {{ $step }} of 3</small>

                @if($step < 3)
                <button wire:click="nextStep" class="btn btn-success rounded-pill px-5 shadow-sm"
                    @if($step === 1 && !$selectedFarmerId) disabled @endif
                    @if($step === 2 && !$selectedPlotId) disabled @endif>
                    Next <i class="fas fa-arrow-right ml-1"></i>
                </button>
                @else
                <button wire:click="save" class="btn btn-success rounded-pill px-5 shadow-sm"
                    @if($qrValid !== true) disabled @endif>
                    <i class="fas fa-save mr-1"></i> Save Sample
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif

<script src="https://unpkg.com/html5-qrcode" defer></script>
<script>
let beepCtx = null;
function beep() {
    try {
        if (!beepCtx) {
            beepCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (beepCtx.state === 'suspended') {
            beepCtx.resume();
        }
        const osc = beepCtx.createOscillator();
        const gain = beepCtx.createGain();
        osc.connect(gain);
        gain.connect(beepCtx.destination);
        osc.frequency.value = 1200;
        gain.gain.value = 0.15;
        osc.start(beepCtx.currentTime);
        osc.stop(beepCtx.currentTime + 0.12);
    } catch(e) {}
}

document.addEventListener('livewire:initialized', () => {
    let html5QrCode = null;
    let scannerActive = false;

    window.toggleScanner = function() {
        const container = document.getElementById('qr-reader');
        const btn = document.getElementById('scannerToggleBtn');

        if (!container || !btn) return;

        btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();

        if (scannerActive) {
            if (html5QrCode) { html5QrCode.stop().catch(() => {}); }
            html5QrCode = null;
            container.style.display = 'none';
            scannerActive = false;
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Scan';
            return;
        }

        container.style.display = 'block';
        btn.classList.add('btn-danger');
        btn.innerHTML = '<i class="fas fa-stop mr-1"></i> Stop';

        html5QrCode = new Html5Qrcode('qr-reader');

        html5QrCode.start(
            { facingMode: 'environment' },
            {
                fps: 20,
                qrbox: { width: 280, height: 280 },
                aspectRatio: 1.333,
                disableFlip: true,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            },
            (decodedText) => {
                html5QrCode.stop().catch(() => {});
                html5QrCode = null;
                scannerActive = false;
                container.style.display = 'none';
                btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();
                btn.classList.add('btn-success');
                btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Scan';

                beep();

                const inp = document.getElementById('qr_input');
                if (inp) {
                    inp.value = decodedText;
                    inp.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        ).catch((err) => {
            container.style.display = 'none';
            btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Scan';
            scannerActive = false;
        });

        scannerActive = true;
    };

    window.addEventListener('beforeunload', () => {
        if (html5QrCode) html5QrCode.stop().catch(() => {});
    });
});
</script>
</div>