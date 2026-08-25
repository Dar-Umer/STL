<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-users mr-2"></i>Users</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add User</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input wire:model="name" class="form-control rounded-pill">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input wire:model="email" type="email" class="form-control rounded-pill">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Password {{ $userId ? '(leave blank to keep)' : '' }}</label>
                            <input wire:model="password" type="password" class="form-control rounded-pill">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone</label>
                            <input wire:model="phone" class="form-control rounded-pill">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Role</label>
                            <select wire:model="role" class="form-control rounded-pill">
                                <option value="">Select Role</option>
                                @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @if($isLabManager ?? false)
                    <input type="hidden" wire:model="lab_id">
                    @else
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Lab</label>
                            <select wire:model="lab_id" class="form-control rounded-pill">
                                <option value="">No Lab</option>
                                @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }} ({{ $lab->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Zone (Field Officials only)</label>
                            <select wire:model="zone_id" class="form-control rounded-pill">
                                <option value="">No Zone</option>
                                @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Active</label>
                            <select wire:model="is_active" class="form-control rounded-pill">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Save</button>
                <button type="button" wire:click="cancel" class="btn btn-secondary rounded-pill px-3">Cancel</button>
            </form>
            <hr>
            @endif

            <div class="mb-3">
                <input wire:model.live="search" placeholder="Search users..." class="form-control rounded-pill">
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th>Name</th><th>Email</th><th>Role</th><th>Lab</th><th>Active</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                            <td>{{ $user->lab?->code ?? '-' }}</td>
                            <td>{!! $user->is_active ? '<span class="badge badge-success rounded-pill px-3 py-1">Yes</span>' : '<span class="badge badge-danger rounded-pill px-3 py-1">No</span>' !!}</td>
                            <td>
                                <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button>
                                <button wire:click="toggleActive({{ $user->id }})" class="btn btn-sm rounded-pill px-3 btn-{{ $user->is_active ? 'warning' : 'success' }}">
                                    <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">{{ $users->links() }}</div>
        </div>
    </div>
</div>