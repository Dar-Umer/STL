<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-shield-alt text-primary mr-2"></i>Roles & Permissions
            </h4>
            <p class="text-muted small mb-0">Manage platform access levels, authorization policies, and system capabilities.</p>
        </div>
        <div class="mt-3 mt-md-0">
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add Role
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
                <i class="fas {{ $roleId ? 'fa-edit text-warning' : 'fa-plus text-success' }} mr-2"></i>
                {{ $roleId ? 'Edit Role: ' . $name : 'Define New System Role' }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-dark">Role Name <span class="text-danger">*</span></label>
                    <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="e.g. Quality Analyst">
                    @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label class="small font-weight-bold text-dark d-block mb-3">Assignable Permissions</label>
                    <div class="row">
                        @foreach($allPermissions as $perm)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="p-2 rounded bg-light border d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-2">
                                    <input type="checkbox" wire:model="permissions" value="{{ $perm->name }}" id="perm_{{ $perm->id }}" class="custom-control-input">
                                    <label class="custom-control-label font-weight-medium small text-dark" for="perm_{{ $perm->id }}">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> {{ $roleId ? 'Update Role' : 'Create Role' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Roles Directory Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-list text-muted mr-2"></i>System Roles</span>
                <span class="badge badge-light border text-muted">{{ count($roles) }} configured</span>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 220px;">Role Designation</th>
                        <th>Associated Permissions</th>
                        <th class="text-right" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        @php
                            $badgeClass = match($role->name) {
                                'Admin' => 'badge-danger',
                                'Lab Manager' => 'badge-warning',
                                'Lab Technician' => 'badge-primary',
                                'Field Official' => 'badge-info',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="badge {{ $badgeClass }} px-3 py-1 font-weight-bold" style="font-size: 0.8rem;">
                                    <i class="fas fa-user-shield mr-1"></i> {{ $role->name }}
                                </span>
                            </td>
                            <td>
                                @if($role->permissions->count())
                                    <div class="d-flex flex-wrap" style="gap: 4px;">
                                        @foreach($role->permissions as $p)
                                            <span class="badge badge-light border text-dark px-2 py-1" style="font-size: 0.72rem;">
                                                {{ $p->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">No explicit permissions assigned</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $role->id }})" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none mr-1" title="Edit Permissions">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                @if($role->name !== 'Admin')
                                    <button wire:click="delete({{ $role->id }})" wire:confirm="Delete role '{{ $role->name }}'? Users assigned this role will lose its permissions." class="btn btn-sm btn-outline-danger rounded-pill px-2 shadow-none" title="Delete Role">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>