<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-user-plus text-info mr-2"></i> Self-Sample Collection</h4>
            <p class="text-muted mb-0 small">Register farmer and collect sample directly at the lab.</p>
        </div>
        <div>
            <a href="{{ route('lab.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-lg" role="alert">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-lg" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    @if($showForm)
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle bg-info d-flex align-items-center justify-content-center mr-3" style="width:36px;height:36px;">
                <i class="fas fa-file-invoice text-white"></i>
            </div>
            <h5 class="mb-0 font-weight-bold">New Self-Sample</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="font-weight-bold">Farmer Phone <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input wire:model.blur="phone" type="text" class="form-control border-right-0" placeholder="Enter phone to lookup existing farmer">
                    <div class="input-group-append">
                        <button type="button" wire:click="lookupFarmer" class="btn btn-info rounded-pill-right"><i class="fas fa-search"></i> Lookup</button>
                    </div>
                </div>
                @if($foundFarmer)
                <small class="text-success"><i class="fas fa-check-circle mr-1"></i> Existing farmer: {{ $foundFarmer->name }}</small>
                @endif
            </div>

            <hr>
            <h6 class="font-weight-bold text-info"><i class="fas fa-user mr-1"></i> Farmer Details</h6>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input wire:model="name" class="form-control" placeholder="Farmer name">
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Phone <span class="text-danger">*</span></label>
                    <input wire:model="phone" type="text" class="form-control" placeholder="Phone number">
                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Parentage</label>
                    <input wire:model="parentage" class="form-control" placeholder="Father/Husband name">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Aadhaar No</label>
                    <input wire:model="aadhar" class="form-control" placeholder="Optional">
                </div>
                <div class="col-md-4 form-group">
                    <label>State <span class="text-danger">*</span></label>
                    <select wire:model.live="stateId" class="form-control">
                        <option value="">Select State</option>
                        @foreach($selfStates as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('stateId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>District <span class="text-danger">*</span></label>
                    <select wire:model.live="districtId" class="form-control">
                        <option value="">Select District</option>
                        @foreach($selfDistricts as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('districtId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Zone</label>
                    <select wire:model.live="zoneId" class="form-control">
                        <option value="">Select Zone</option>
                        @foreach($selfZones as $z)
                        <option value="{{ $z->id }}">{{ $z->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Panchayat</label>
                    <select wire:model.live="panchayatId" class="form-control">
                        <option value="">Select Panchayat</option>
                        @foreach($selfPanchayats as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Village</label>
                    <select wire:model="villageId" class="form-control">
                        <option value="">Select Village</option>
                        @foreach($selfVillages as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>
            <h6 class="font-weight-bold text-info"><i class="fas fa-map-marked-alt mr-1"></i> Plot Details</h6>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Plot Name <span class="text-danger">*</span></label>
                    <input wire:model="plotName" class="form-control" placeholder="e.g. Main Field">
                    @error('plotName') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Khasra No <span class="text-danger">*</span></label>
                    <input wire:model="khasraNo" class="form-control" placeholder="Survey/khasra number">
                    @error('khasraNo') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Area (Kanals) <span class="text-danger">*</span></label>
                    <input wire:model="areaKanals" type="number" step="0.01" class="form-control" placeholder="0.00">
                    @error('areaKanals') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Soil Type <span class="text-danger">*</span></label>
                    <select wire:model="soilTypeId" class="form-control">
                        <option value="">Select Soil Type</option>
                        @foreach($selfSoilTypes as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                    @error('soilTypeId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Crop <span class="text-danger">*</span></label>
                    <select wire:model="cropId" class="form-control">
                        <option value="">Select Crop</option>
                        @foreach($selfCrops as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('cropId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Crop Age (years) <span class="text-danger">*</span></label>
                    <input wire:model="ageOfPlants" type="number" step="0.1" class="form-control" placeholder="0">
                    @error('ageOfPlants') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="button" wire:click="save" class="btn btn-info rounded-pill px-4 shadow-sm font-weight-bold">
                    <i class="fas fa-save mr-1"></i> Collect Self-Sample
                </button>
                <button type="button" wire:click="resetForm" class="btn btn-outline-secondary rounded-pill px-4 ml-2"><i class="fas fa-undo mr-1"></i> Reset</button>
            </div>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body text-center py-5">
            <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width:64px;height:64px;">
                <i class="fas fa-check-circle text-white fa-2x"></i>
            </div>
            <h4 class="font-weight-bold mb-2">Self-Sample Collected Successfully!</h4>
            <p class="text-muted mb-4">The sample has been registered and is ready for testing.</p>
            <button wire:click="resetForm" class="btn btn-info rounded-pill px-4 shadow-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Collect Another
            </button>
            <a href="{{ route('lab.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
    @endif

    <style>
        .rounded-pill-right { border-radius: 0 50rem 50rem 0 !important; }
    </style>
</div>
