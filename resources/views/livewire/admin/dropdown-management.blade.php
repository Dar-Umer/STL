<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-list text-primary mr-2"></i>Classification Dropdowns
            </h4>
            <p class="text-muted small mb-0">Manage reusable dropdown options for soil types, soil colors, and target crop varieties.</p>
        </div>
        <div class="mt-3 mt-md-0">
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add Item
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

    {{-- Tabs --}}
    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a wire:click="$set('activeTab', 'soil_types')" class="nav-link rounded-pill font-weight-medium px-4 mr-2 {{ $activeTab === 'soil_types' ? 'active' : '' }}" href="#">
                <i class="fas fa-layer-group mr-1"></i> Soil Types
            </a>
        </li>
        <li class="nav-item">
            <a wire:click="$set('activeTab', 'soil_colors')" class="nav-link rounded-pill font-weight-medium px-4 mr-2 {{ $activeTab === 'soil_colors' ? 'active' : '' }}" href="#">
                <i class="fas fa-palette mr-1"></i> Soil Colors
            </a>
        </li>
        <li class="nav-item">
            <a wire:click="$set('activeTab', 'crops')" class="nav-link rounded-pill font-weight-medium px-4 {{ $activeTab === 'crops' ? 'active' : '' }}" href="#">
                <i class="fas fa-seedling mr-1"></i> Crops
            </a>
        </li>
    </ul>

    {{-- Form Card --}}
    @if($showForm)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fas {{ $editId ? 'fa-edit text-warning' : 'fa-plus text-success' }} mr-2"></i>
                {{ $editId ? 'Edit Item' : 'Add Item to ' . ucwords(str_replace('_', ' ', $activeTab)) }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Name / Value <span class="text-danger">*</span></label>
                    <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="Enter option name...">
                    @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                </div>
                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @php
        $items = match($activeTab) {
            'soil_types' => $soilTypes,
            'soil_colors' => $soilColors,
            'crops' => $crops,
        };
    @endphp

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-list text-muted mr-2"></i>{{ ucwords(str_replace('_', ' ', $activeTab)) }}</span>
                <span class="badge badge-light border text-muted">{{ count($items) }} options</span>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Option Value</th>
                        <th class="text-right" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td class="text-muted font-weight-medium">{{ $i + 1 }}</td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $item->name }}</span>
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $item->id }}, '{{ $activeTab }}')" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-list fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No dropdown options defined</h6>
                                <p class="small text-muted mb-0">Click "Add Item" above to add your first option.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
