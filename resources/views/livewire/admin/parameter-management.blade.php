<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Test Parameters</h4>
        <div>
            <button wire:click="$toggle('showInactive')" class="btn btn-sm rounded-pill px-3 shadow-sm mr-1 {{ $showInactive ? 'btn-warning' : 'btn-outline-secondary' }}">
                <i class="fas fa-eye"></i> {{ $showInactive ? 'Hiding Inactive' : 'Show Inactive' }}
            </button>
            <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add Parameter</button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-4"><div class="form-group"><label>Name</label><input wire:model="name" class="form-control rounded-pill">@error('name') <span class="text-danger">{{ $message }}</span> @enderror</div></div>
                    <div class="col-md-2"><div class="form-group"><label>Unit</label><input wire:model="unit" class="form-control rounded-pill"></div></div>
                    <div class="col-md-2"><div class="form-group"><label>Order</label><input wire:model="order_column" type="number" class="form-control rounded-pill"></div></div>
                    <div class="col-md-4"><div class="form-group"><label>Method</label><input wire:model="method" class="form-control rounded-pill"></div></div>
                    <div class="col-md-3"><div class="form-group"><label>Min Range</label><input wire:model="min_range" type="number" step="0.01" class="form-control rounded-pill"></div></div>
                    <div class="col-md-3"><div class="form-group"><label>Max Range</label><input wire:model="max_range" type="number" step="0.01" class="form-control rounded-pill"></div></div>
                    <div class="col-md-2"><div class="form-group"><label>Low Max</label><input wire:model="rating_low_max" type="number" step="0.01" class="form-control rounded-pill"></div></div>
                    <div class="col-md-2"><div class="form-group"><label>Med Max</label><input wire:model="rating_medium_max" type="number" step="0.01" class="form-control rounded-pill"></div></div>
                    <div class="col-md-2"><div class="form-group"><label>High Min</label><input wire:model="rating_high_min" type="number" step="0.01" class="form-control rounded-pill"></div></div>
                    <div class="col-md-4"><div class="form-group"><label>Low Label</label><input wire:model="rating_label_low" class="form-control rounded-pill" placeholder="Low"></div></div>
                    <div class="col-md-4"><div class="form-group"><label>Medium Label</label><input wire:model="rating_label_medium" class="form-control rounded-pill" placeholder="Medium"></div></div>
                    <div class="col-md-4"><div class="form-group"><label>High Label</label><input wire:model="rating_label_high" class="form-control rounded-pill" placeholder="High"></div></div>
                    <div class="col-md-4"><div class="form-group"><label>Low Color</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white rounded-pill-left p-0" style="width:34px"><input wire:model="rating_color_low" type="color" style="width:32px;height:28px;padding:0;border:none;cursor:pointer"></span></div><input wire:model="rating_color_low" class="form-control rounded-pill-right" placeholder="#ffc107"></div></div></div>
                    <div class="col-md-4"><div class="form-group"><label>Medium Color</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white rounded-pill-left p-0" style="width:34px"><input wire:model="rating_color_medium" type="color" style="width:32px;height:28px;padding:0;border:none;cursor:pointer"></span></div><input wire:model="rating_color_medium" class="form-control rounded-pill-right" placeholder="#17a2b8"></div></div></div>
                    <div class="col-md-4"><div class="form-group"><label>High Color</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white rounded-pill-left p-0" style="width:34px"><input wire:model="rating_color_high" type="color" style="width:32px;height:28px;padding:0;border:none;cursor:pointer"></span></div><input wire:model="rating_color_high" class="form-control rounded-pill-right" placeholder="#28a745"></div></div></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Assign to Labs</label>
                            <select wire:model="selectedLabs" multiple class="form-control rounded" size="4">
                                @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }} ({{ $lab->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><input wire:model="is_active" type="checkbox"> Active</label>
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
                            <th>#</th><th>Name</th><th>Unit</th><th>Range</th><th>Ratings</th><th>Labs</th><th>Active</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parameters as $param)
                        <tr>
                            <td>{{ $param->order_column }}</td>
                            <td>{{ $param->name }}</td>
                            <td>{{ $param->unit }}</td>
                            <td>{{ $param->min_range }} - {{ $param->max_range }}</td>
                            <td style="white-space:nowrap">
                                <span style="background:{{ $param->getRatingColor('low') }};color:#fff;padding:0 4px;font-size:11px;border-radius:3px">{{ $param->getRatingLabel('low') }}</span>
                                <span style="background:{{ $param->getRatingColor('medium') }};color:#fff;padding:0 4px;font-size:11px;border-radius:3px">{{ $param->getRatingLabel('medium') }}</span>
                                <span style="background:{{ $param->getRatingColor('high') }};color:#fff;padding:0 4px;font-size:11px;border-radius:3px">{{ $param->getRatingLabel('high') }}</span>
                                <small class="text-muted d-block" style="font-size:10px">L≤{{ $param->rating_low_max }} M≤{{ $param->rating_medium_max }} H≥{{ $param->rating_high_min }}</small>
                            </td>
                            <td>
                                @if($param->relationLoaded('labs') && $param->labs->count())
                                    @foreach($param->labs as $lab)
                                    <span class="badge badge-info rounded-pill px-3 py-1">{{ $lab->code }}</span>
                                    @endforeach
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{!! $param->is_active ? '<span class="badge badge-success rounded-pill px-3 py-1">Yes</span>' : '<span class="badge badge-danger rounded-pill px-3 py-1">No</span>' !!}</td>
                            <td>
                                <button wire:click="edit({{ $param->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $param->id }})" onclick="return confirm('Delete this parameter? Existing results will be preserved.')" class="btn btn-sm btn-danger rounded-pill px-3"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>