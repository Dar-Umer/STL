<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-shield-alt mr-2"></i>Roles & Permissions</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add Role</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="form-group">
                    <label>Role Name</label>
                    <input wire:model="name" class="form-control rounded-pill" placeholder="e.g. Lab Assistant">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Permissions</label>
                    <div class="row">
                        @foreach($allPermissions as $perm)
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" wire:model="permissions" value="{{ $perm->name }}" id="perm_{{ $perm->id }}" class="custom-control-input">
                                <label class="custom-control-label" for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Save</button>
                <button type="button" wire:click="cancel" class="btn btn-secondary rounded-pill px-3">Cancel</button>
            </form>
            <hr>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th>Role</th><th>Permissions</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                            <td>
                                <button wire:click="edit({{ $role->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button>
                                @if($role->name !== 'Admin')
                                <button wire:click="delete({{ $role->id }})" wire:confirm="Delete role '{{ $role->name }}'? Users assigned this role will lose its permissions." class="btn btn-sm btn-danger rounded-pill px-3"><i class="fas fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>