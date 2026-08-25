<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-cogs mr-2"></i>Custom Fields</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add Field</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-4"><div class="form-group"><label>Field Name</label><input wire:model="name" class="form-control rounded-pill">@error('name') <span class="text-danger">{{ $message }}</span> @enderror</div></div>
                    <div class="col-md-2"><div class="form-group"><label>Type</label>
                        <select wire:model="type" class="form-control rounded-pill">
                            <option value="text">Text</option>
                            <option value="select">Select</option>
                            <option value="textarea">Textarea</option>
                        </select>
                    </div></div>
                    <div class="col-md-2"><div class="form-group"><label>Order</label><input wire:model="order_column" type="number" class="form-control rounded-pill"></div></div>
                    <div class="col-md-2"><div class="form-group"><label>Required</label>
                        <select wire:model="required" class="form-control rounded-pill"><option value="1">Yes</option><option value="0">No</option></select>
                    </div></div>
                    <div class="col-md-2"><div class="form-group"><label>Active</label>
                        <select wire:model="is_active" class="form-control rounded-pill"><option value="1">Active</option><option value="0">Inactive</option></select>
                    </div></div>
                    @if($type === 'select')
                    <div class="col-md-12"><div class="form-group"><label>Options (one per line)</label><textarea wire:model="options" class="form-control rounded" rows="3"></textarea></div></div>
                    @endif
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
                            <th>Order</th><th>Name</th><th>Type</th><th>Required</th><th>Active</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $field)
                        <tr>
                            <td>{{ $field->order_column }}</td>
                            <td>{{ $field->name }}</td>
                            <td>{{ $field->type }}</td>
                            <td>{!! $field->required ? '<span class="badge badge-danger rounded-pill px-3 py-1">Yes</span>' : '<span class="badge badge-secondary rounded-pill px-3 py-1">No</span>' !!}</td>
                            <td>{!! $field->is_active ? '<span class="badge badge-success rounded-pill px-3 py-1">Yes</span>' : '<span class="badge badge-danger rounded-pill px-3 py-1">No</span>' !!}</td>
                            <td><button wire:click="edit({{ $field->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
