<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-users-cog text-primary mr-2"></i>User Management
            </h4>
            <p class="text-muted small mb-0">Manage system users, laboratory staff, field officials, and their role permissions.</p>
        </div>
        <div class="mt-3 mt-md-0">
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-user-plus mr-1"></i> Add New User
                </button>
            @endif
        </div>
    </div>

    {{-- User Form (Create / Edit) --}}
    @if($showForm)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fas {{ $userId ? 'fa-user-edit text-warning' : 'fa-user-plus text-success' }} mr-2"></i>
                {{ $userId ? 'Edit User: ' . $name : 'Create New User Account' }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="e.g. Zahoor Ahmad">
                        @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                        <input wire:model="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" placeholder="user@soillab.com">
                        @error('email') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Password {{ $userId ? '(leave blank to keep current)' : '*' }}</label>
                        <input wire:model="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" placeholder="{{ $userId ? '••••••••' : 'Min. 6 characters' }}">
                        @error('password') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Phone Number</label>
                        <input wire:model="phone" type="text" class="form-control rounded-pill @error('phone') is-invalid @enderror" placeholder="e.g. +91 9876543210">
                        @error('phone') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">System Role <span class="text-danger">*</span></label>
                        <select wire:model="role" class="form-control rounded-pill @error('role') is-invalid @enderror">
                            <option value="">— Select Role —</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('role') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    
                    @if($isLabManager ?? false)
                        <input type="hidden" wire:model="lab_id">
                    @else
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Assigned Laboratory</label>
                            <select wire:model="lab_id" class="form-control rounded-pill">
                                <option value="">No Lab Assigned</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->name }} ({{ $lab->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Assigned Zone <small class="text-muted">(Field Officials)</small></label>
                        <select wire:model="zone_id" class="form-control rounded-pill">
                            <option value="">No Zone Assigned</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-dark">Account Status</label>
                        <select wire:model="is_active" class="form-control rounded-pill">
                            <option value="1">Active Account</option>
                            <option value="0">Suspended / Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> {{ $userId ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Users Directory Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-list text-muted mr-2"></i>User Accounts</span>
                <span class="badge badge-light border text-muted">{{ $users->total() }} total</span>
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
                           placeholder="Search by name, email...">
                    @if($search)
                    <div class="input-group-append">
                        <button wire:click="$set('search', '')" class="btn btn-light border-left-0" style="border-radius: 0 9999px 9999px 0;">
                            <i class="fas fa-times text-muted small"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email & Contact</th>
                        <th>Role</th>
                        <th>Laboratory / Zone</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        @php
                            $primaryRole = $u->roles->first()?->name ?? 'User';
                            $roleBadgeClass = match($primaryRole) {
                                'Admin' => 'badge-danger',
                                'Lab Manager' => 'badge-warning',
                                'Lab Technician' => 'badge-primary',
                                'Field Official' => 'badge-info',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center shadow-xs font-weight-bold text-white" 
                                         style="width: 38px; height: 38px; min-width: 38px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); font-size: 0.9rem;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark d-block">{{ $u->name }}</span>
                                        <small class="text-muted">ID: #{{ $u->id }} &bull; Registered {{ $u->created_at?->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark small"><i class="fas fa-envelope mr-1 text-muted"></i> {{ $u->email }}</div>
                                @if($u->phone)
                                    <div class="text-muted small mt-1"><i class="fas fa-phone mr-1 text-muted"></i> {{ $u->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $roleBadgeClass }} px-3 py-1">
                                    {{ $primaryRole }}
                                </span>
                            </td>
                            <td>
                                @if($u->lab)
                                    <span class="d-block small text-dark"><i class="fas fa-flask mr-1 text-info"></i> {{ $u->lab->name }}</span>
                                    <small class="text-muted">Code: {{ $u->lab->code }}</small>
                                @elseif($u->zone)
                                    <span class="d-block small text-dark"><i class="fas fa-map-marker-alt mr-1 text-danger"></i> {{ $u->zone->name }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($u->is_active)
                                    <span class="badge badge-success px-3 py-1">Active</span>
                                @else
                                    <span class="badge badge-danger px-3 py-1">Suspended</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $u->id }})" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none mr-1" title="Edit User">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button wire:click="toggleActive({{ $u->id }})" 
                                        class="btn btn-sm rounded-pill px-3 shadow-none btn-{{ $u->is_active ? 'outline-danger' : 'outline-success' }}" 
                                        title="{{ $u->is_active ? 'Deactivate User' : 'Activate User' }}">
                                    <i class="fas {{ $u->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No users found</h6>
                                <p class="small text-muted mb-0">Try adjusting your search criteria or add a new user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>