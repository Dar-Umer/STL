<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-flask text-primary mr-2"></i>Testing Laboratories
            </h4>
            <p class="text-muted small mb-0">Configure district testing laboratories, contact points, and assignable test parameters.</p>
        </div>
        <div class="mt-3 mt-md-0">
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add Laboratory
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
                <i class="fas {{ $labId ? 'fa-edit text-warning' : 'fa-plus text-success' }} mr-2"></i>
                {{ $labId ? 'Edit Laboratory: ' . $name : 'Create New Testing Laboratory' }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Lab Name <span class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="e.g. District Soil Testing Lab Pulwama">
                        @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Laboratory Code <span class="text-danger">*</span></label>
                        <input wire:model="code" type="text" class="form-control rounded-pill @error('code') is-invalid @enderror" placeholder="e.g. STL-PUL">
                        @error('code') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Email Address</label>
                        <input wire:model="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" placeholder="lab.pulwama@jk.gov.in">
                        @error('email') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Phone / Helpline</label>
                        <input wire:model="phone" type="text" class="form-control rounded-pill @error('phone') is-invalid @enderror" placeholder="+91 1933 260234">
                        @error('phone') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Contact Officer / Manager</label>
                        <input wire:model="contact_person" type="text" class="form-control rounded-pill" placeholder="Name of officer in-charge">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Operational Status</label>
                        <select wire:model="is_active" class="form-control rounded-pill">
                            <option value="1">Active Center</option>
                            <option value="0">Inactive / Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold text-dark">Physical Address</label>
                        <textarea wire:model="address" class="form-control rounded-lg" rows="2" placeholder="Full laboratory location address..."></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold text-dark">Assigned Test Parameters</label>
                        <select wire:model="selectedParameters" multiple class="form-control rounded-lg" style="min-height: 120px;">
                            @foreach($allParameters as $p)
                                <option value="{{ $p->id }}" class="py-1">
                                    {{ $p->name }} {{ $p->unit ? "($p->unit)" : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> Hold Ctrl (Windows) or Cmd (Mac) to select multiple parameters.</small>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> {{ $labId ? 'Update Lab' : 'Save Laboratory' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Labs Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-list text-muted mr-2"></i>Registered Laboratories</span>
                <span class="badge badge-light border text-muted">{{ count($labs) }} total</span>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Laboratory Name</th>
                        <th>Code</th>
                        <th>Assigned Parameters</th>
                        <th>Contact Info</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($labs as $lab)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center text-info shadow-xs" 
                                         style="width: 38px; height: 38px; min-width: 38px; background: #e0f2fe;">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark d-block">{{ $lab->name }}</span>
                                        @if($lab->contact_person)
                                            <small class="text-muted"><i class="fas fa-user-tie mr-1"></i>{{ $lab->contact_person }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light border font-monospace px-3 py-1 font-weight-bold text-dark">
                                    {{ $lab->code }}
                                </span>
                            </td>
                            <td>
                                @if($lab->relationLoaded('parameters') && $lab->parameters->count())
                                    <div class="d-flex flex-wrap" style="gap: 4px; max-width: 320px;">
                                        @foreach($lab->parameters as $p)
                                            <span class="badge badge-light border text-secondary px-2 py-1" style="font-size: 0.72rem;">
                                                {{ $p->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">None assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($lab->phone)
                                    <div class="small text-dark"><i class="fas fa-phone-alt text-muted mr-1" style="font-size: 0.7rem;"></i>{{ $lab->phone }}</div>
                                @endif
                                @if($lab->email)
                                    <div class="small text-muted"><i class="fas fa-envelope text-muted mr-1" style="font-size: 0.7rem;"></i>{{ $lab->email }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lab->is_active)
                                    <span class="badge badge-success px-3 py-1">Active</span>
                                @else
                                    <span class="badge badge-danger px-3 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $lab->id }})" class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-1" title="Edit Lab">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button wire:click="toggleActive({{ $lab->id }})" 
                                        class="btn btn-sm rounded-pill px-3 btn-{{ $lab->is_active ? 'outline-danger' : 'outline-success' }}" 
                                        title="{{ $lab->is_active ? 'Deactivate Lab' : 'Activate Lab' }}">
                                    <i class="fas {{ $lab->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-flask fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No laboratories found</h6>
                                <p class="small text-muted mb-0">Click "Add Laboratory" above to create the first test center.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>