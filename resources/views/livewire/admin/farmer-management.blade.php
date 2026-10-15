<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-tractor text-warning mr-2"></i>Farmer Directory
            </h4>
            <p class="text-muted small mb-0">Registered agricultural beneficiaries, farm locations, and contact records.</p>
        </div>
        <div class="mt-3 mt-md-0">
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-user-plus mr-1"></i> Register Farmer
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-lg d-flex align-items-center mb-4">
        <i class="fas fa-check-circle fa-lg mr-3 text-success"></i>
        <div class="flex-grow-1">
            <span class="font-weight-medium">{{ session('success') }}</span>
        </div>
        <button type="button" class="close text-success" data-dismiss="alert">&times;</button>
    </div>
    @endif

    {{-- Form Card --}}
    @if($showForm)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fas {{ $farmerId ? 'fa-edit text-warning' : 'fa-user-plus text-success' }} mr-2"></i>
                {{ $farmerId ? 'Edit Farmer: ' . $name : 'Register New Farmer' }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Farmer Name <span class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="e.g. Ghulam Mohammad Lone">
                        @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Parentage / Guardian Name</label>
                        <input wire:model="parentage" type="text" class="form-control rounded-pill" placeholder="Father or guardian's name">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                        <input wire:model="phone" type="text" class="form-control rounded-pill @error('phone') is-invalid @enderror" placeholder="+91 9876543210">
                        @error('phone') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Aadhaar Card No</label>
                        <input wire:model="aadhar_no" type="text" class="form-control rounded-pill @error('aadhar_no') is-invalid @enderror" placeholder="12-digit Aadhaar number">
                        @error('aadhar_no') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>

                    @if($isLabManager ?? false)
                        <input type="hidden" wire:model="lab_id">
                    @else
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Assigned Laboratory <span class="text-danger">*</span></label>
                            <select wire:model="lab_id" class="form-control rounded-pill @error('lab_id') is-invalid @enderror">
                                <option value="">— Select Lab —</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                @endforeach
                            </select>
                            @error('lab_id') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                        </div>
                    @endif

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">State / UT <span class="text-danger">*</span></label>
                        <select wire:model.live="state_id" class="form-control rounded-pill @error('state_id') is-invalid @enderror">
                            <option value="">— Select State —</option>
                            @foreach($states as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('state_id') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">District <span class="text-danger">*</span></label>
                        <select wire:model.live="district_id" class="form-control rounded-pill @error('district_id') is-invalid @enderror">
                            <option value="">— Select District —</option>
                            @foreach($this->districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('district_id') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Zone / Block</label>
                        <select wire:model.live="zone_id" class="form-control rounded-pill">
                            <option value="">— Select Zone —</option>
                            @foreach($this->zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Panchayat</label>
                        <select wire:model.live="panchayat_id" class="form-control rounded-pill">
                            <option value="">— Select Panchayat —</option>
                            @foreach($this->panchayats as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Village</label>
                        <select wire:model="village_id" class="form-control rounded-pill">
                            <option value="">— Select Village —</option>
                            @foreach($this->villages as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> {{ $farmerId ? 'Update Farmer' : 'Register Farmer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Farmer Directory Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-list text-muted mr-2"></i>Beneficiaries</span>
                <span class="badge badge-light border text-muted">{{ $farmers->total() }} total</span>
            </div>
            <div style="width: 100%; max-width: 320px;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0 rounded-left" style="border-radius: 9999px 0 0 9999px;">
                            <i class="fas fa-search text-muted small"></i>
                        </span>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" 
                           class="form-control bg-light border-left-0" 
                           style="border-radius: 0 9999px 9999px 0;"
                           placeholder="Search farmer name, phone...">
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Farmer Details</th>
                        <th>Contact Number</th>
                        <th>Assigned Lab</th>
                        <th>Geographic Location</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($farmers as $farmer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center text-warning shadow-xs" 
                                         style="width: 38px; height: 38px; min-width: 38px; background: #fffbeb;">
                                        <i class="fas fa-tractor"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark d-block">{{ $farmer->name }}</span>
                                        @if($farmer->parentage)
                                            <small class="text-muted">S/o {{ $farmer->parentage }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark small font-weight-medium">
                                    <i class="fas fa-phone-alt text-muted mr-1" style="font-size: 0.7rem;"></i>{{ $farmer->phone }}
                                </span>
                            </td>
                            <td>
                                @if($farmer->lab)
                                    <span class="badge badge-light border text-dark">
                                        <i class="fas fa-flask text-info mr-1"></i>{{ $farmer->lab->code }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="small">
                                <span class="text-dark font-weight-medium">{{ $farmer->district?->name ?? '—' }}</span>
                                @if($farmer->zone)
                                    <span class="text-muted"> &bull; {{ $farmer->zone->name }}</span>
                                @endif
                                @if($farmer->village)
                                    <span class="text-muted d-block">{{ $farmer->village->name }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $farmer->id }})" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none" title="Edit Farmer">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-tractor fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No farmers found</h6>
                                <p class="small text-muted mb-0">Try adjusting your search criteria or register a new farmer.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($farmers->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $farmers->links() }}
        </div>
        @endif
    </div>
</div>