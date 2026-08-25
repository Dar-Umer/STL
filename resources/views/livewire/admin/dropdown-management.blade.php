<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-list mr-2"></i>Dropdown Management</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <ul class="nav nav-pills mb-3">
                <li class="nav-item"><a wire:click="$set('activeTab', 'soil_types')" class="nav-link {{ $activeTab === 'soil_types' ? 'active' : '' }}" href="#">Soil Types</a></li>
                <li class="nav-item"><a wire:click="$set('activeTab', 'soil_colors')" class="nav-link {{ $activeTab === 'soil_colors' ? 'active' : '' }}" href="#">Soil Colors</a></li>
                <li class="nav-item"><a wire:click="$set('activeTab', 'crops')" class="nav-link {{ $activeTab === 'crops' ? 'active' : '' }}" href="#">Crops</a></li>
            </ul>

            @if($showForm)
            <form wire:submit="save" class="mb-3">
                <div class="input-group">
                    <input wire:model="name" class="form-control rounded-pill" placeholder="Enter name">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Save</button>
                        <button type="button" wire:click="cancel" class="btn btn-secondary rounded-pill px-3">Cancel</button>
                    </div>
                </div>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </form>
            @endif

            @php
                $items = match($activeTab) {
                    'soil_types' => $soilTypes,
                    'soil_colors' => $soilColors,
                    'crops' => $crops,
                };
            @endphp

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th>Name</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td><button wire:click="edit({{ $item->id }}, '{{ $activeTab }}')" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
