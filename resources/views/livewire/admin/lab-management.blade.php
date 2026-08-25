<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-flask mr-2"></i>Labs</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add Lab</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label>Name</label><input wire:model="name" class="form-control rounded-pill">@error('name') <span class="text-danger">{{ $message }}</span> @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Code</label><input wire:model="code" class="form-control rounded-pill">@error('code') <span class="text-danger">{{ $message }}</span> @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Email</label><input wire:model="email" type="email" class="form-control rounded-pill"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Phone</label><input wire:model="phone" class="form-control rounded-pill"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Contact Person</label><input wire:model="contact_person" class="form-control rounded-pill"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Active</label>
                            <select wire:model="is_active" class="form-control rounded-pill"><option value="1">Active</option><option value="0">Inactive</option></select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group"><label>Address</label><textarea wire:model="address" class="form-control rounded" rows="2"></textarea></div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Assigned Parameters</label>
                            <select wire:model="selectedParameters" multiple class="form-control rounded" size="5">
                                @foreach($allParameters as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->unit ?? 'no unit' }})</option>
                                @endforeach
                            </select>
                            @if(!count($allParameters))
                            <small class="text-muted">No active parameters available. Create parameters first.</small>
                            @endif
                        </div>
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
                            <th>Name</th><th>Code</th><th>Parameters</th><th>Contact</th><th>Active</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labs as $lab)
                        <tr>
                            <td>{{ $lab->name }}</td>
                            <td>{{ $lab->code }}</td>
                            <td>
                                @if($lab->relationLoaded('parameters') && $lab->parameters->count())
                                    @foreach($lab->parameters as $p)
                                    <span class="badge badge-info rounded-pill px-3 py-1 mr-1">{{ $p->name }}</span>
                                    @endforeach
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $lab->phone }}<br><small>{{ $lab->email }}</small></td>
                            <td>{!! $lab->is_active ? '<span class="badge badge-success rounded-pill px-3 py-1">Active</span>' : '<span class="badge badge-danger rounded-pill px-3 py-1">Inactive</span>' !!}</td>
                            <td>
                                <button wire:click="edit({{ $lab->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button>
                                <button wire:click="toggleActive({{ $lab->id }})" class="btn btn-sm rounded-pill px-3 btn-{{ $lab->is_active ? 'warning' : 'success' }}">
                                    <i class="fas {{ $lab->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>