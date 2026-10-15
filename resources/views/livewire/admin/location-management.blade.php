<div>
    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-lg-2 col-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:42px;height:42px;background:rgba(23,162,184,.12);"><i class="fas fa-globe text-info"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Total</p><h3 class="mb-0 font-weight-bold">{{ $stats['total'] }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:42px;height:42px;background:rgba(0,123,255,.12);"><i class="fas fa-flag text-primary"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">States</p><h3 class="mb-0 font-weight-bold">{{ $stats['state'] }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:42px;height:42px;background:rgba(23,162,184,.12);"><i class="fas fa-city text-info"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Districts</p><h3 class="mb-0 font-weight-bold">{{ $stats['district'] }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:42px;height:42px;background:rgba(108,117,125,.12);"><i class="fas fa-map-pin text-secondary"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Zones</p><h3 class="mb-0 font-weight-bold">{{ $stats['zone'] }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:42px;height:42px;background:rgba(255,193,7,.12);"><i class="fas fa-building text-warning"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Panchayats</p><h3 class="mb-0 font-weight-bold">{{ $stats['panchayat'] }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:42px;height:42px;background:rgba(40,167,69,.12);"><i class="fas fa-home text-success"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Villages</p><h3 class="mb-0 font-weight-bold">{{ $stats['village'] }}</h3></div>
            </div></div>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm mr-1"><i class="fas fa-plus"></i> Add Location</button>
                    <button wire:click="toggleImport" class="btn btn-info rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-file-upload"></i> Import CSV/Excel</button>
                    @if($search)
                    <button wire:click="$set('search', '')" class="btn btn-outline-secondary rounded-pill btn-sm px-3 ml-1"><i class="fas fa-times"></i> Clear</button>
                    @endif
                </div>
                <input wire:model.live.debounce.300ms="search" placeholder="Search locations..." class="form-control form-control-sm rounded-pill" style="min-width:220px;max-width:320px">
            </div>

            {{-- Filter Tabs --}}
            <ul class="nav nav-pills mb-3">
                <li class="nav-item"><a wire:click="$set('filterType', 'all')" class="nav-link {{ $filterType === 'all' ? 'active' : '' }}" href="#">All</a></li>
                <li class="nav-item"><a wire:click="$set('filterType', 'state')" class="nav-link {{ $filterType === 'state' ? 'active' : '' }}" href="#">States</a></li>
                <li class="nav-item"><a wire:click="$set('filterType', 'district')" class="nav-link {{ $filterType === 'district' ? 'active' : '' }}" href="#">Districts</a></li>
                <li class="nav-item"><a wire:click="$set('filterType', 'zone')" class="nav-link {{ $filterType === 'zone' ? 'active' : '' }}" href="#">Zones</a></li>
                <li class="nav-item"><a wire:click="$set('filterType', 'panchayat')" class="nav-link {{ $filterType === 'panchayat' ? 'active' : '' }}" href="#">Panchayats</a></li>
                <li class="nav-item"><a wire:click="$set('filterType', 'village')" class="nav-link {{ $filterType === 'village' ? 'active' : '' }}" href="#">Villages</a></li>
            </ul>

            {{-- Content --}}
            @if($search || $filterType !== 'all')
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light"><tr class="small text-uppercase text-muted"><th>Name</th><th>Type</th><th>Parent</th><th class="text-right">Actions</th></tr></thead>
                        <tbody>
                            @forelse($allLocations as $loc)
                            <tr>
                                <td class="font-weight-medium">{{ $loc->name }} <span class="text-muted small">{{ $loc->code ? "($loc->code)" : '' }}</span></td>
                                <td><span class="badge badge-{{ $loc->type === 'state' ? 'primary' : ($loc->type === 'district' ? 'info' : ($loc->type === 'zone' ? 'secondary' : ($loc->type === 'panchayat' ? 'warning' : 'success'))) }} rounded-pill px-3 py-1 text-uppercase">{{ $loc->type }}</span></td>
                                <td>{{ $loc->parent?->name ?? '—' }}</td>
                                <td class="text-right">
                                    <button wire:click="edit({{ $loc->id }})" class="btn btn-sm btn-info rounded-pill px-2" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button wire:click="confirmDelete({{ $loc->id }})" class="btn btn-sm btn-danger rounded-pill px-2" title="Delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No locations found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                @forelse($states as $state)
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between py-2 px-3 bg-light border rounded-top">
                        <div><i class="fas fa-flag text-primary mr-2"></i><strong>{{ $state->name }}</strong> <span class="text-muted small">{{ $state->code ? "($state->code)" : '' }}</span></div>
                        <div>
                            <button wire:click="edit({{ $state->id }})" class="btn btn-sm btn-info rounded-pill px-2" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="confirmDelete({{ $state->id }})" class="btn btn-sm btn-danger rounded-pill px-2" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    @forelse($state->children as $district)
                    <div class="d-flex align-items-center justify-content-between py-1 px-3 border-left border-right" style="padding-left:2.5rem !important">
                        <div><i class="fas fa-city text-info mr-2"></i><strong>{{ $district->name }}</strong></div>
                        <div>
                            <button wire:click="edit({{ $district->id }})" class="btn btn-sm btn-info rounded-pill px-2" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="confirmDelete({{ $district->id }})" class="btn btn-sm btn-danger rounded-pill px-2" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    @forelse($district->children as $zone)
                    <div class="d-flex align-items-center justify-content-between py-1 px-3 border-left border-right" style="padding-left:4.5rem !important">
                        <div><i class="fas fa-map-pin text-secondary mr-2"></i>{{ $zone->name }}</div>
                        <div>
                            <button wire:click="edit({{ $zone->id }})" class="btn btn-sm btn-info rounded-pill px-2" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="confirmDelete({{ $zone->id }})" class="btn btn-sm btn-danger rounded-pill px-2" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    @forelse($zone->children as $panchayat)
                    <div class="d-flex align-items-center justify-content-between py-1 px-3 border-left border-right" style="padding-left:6.5rem !important">
                        <div><i class="fas fa-building text-warning mr-2"></i><span class="text-muted">{{ $panchayat->name }}</span></div>
                        <div>
                            <button wire:click="edit({{ $panchayat->id }})" class="btn btn-sm btn-info rounded-pill px-2" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="confirmDelete({{ $panchayat->id }})" class="btn btn-sm btn-danger rounded-pill px-2" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    @forelse($panchayat->children as $village)
                    <div class="d-flex align-items-center justify-content-between py-1 px-3 border-left border-right" style="padding-left:8.5rem !important">
                        <div><i class="fas fa-home text-success mr-2"></i><span class="text-muted">{{ $village->name }}</span></div>
                        <div>
                            <button wire:click="edit({{ $village->id }})" class="btn btn-sm btn-info rounded-pill px-2" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="confirmDelete({{ $village->id }})" class="btn btn-sm btn-danger rounded-pill px-2" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    @empty
                    @endforelse
                    @empty
                    @endforelse
                    @empty
                    @endforelse
                    @empty
                    @endforelse
                    <div style="height:2px;background:#e9ecef" class="border-left border-right border-bottom rounded-bottom"></div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                    <p>No locations defined yet. Click <strong>Add</strong> or <strong>Import</strong> above to get started.</p>
                </div>
                @endforelse
            @endif
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    @if($showFormModal)
    <div class="modal-backdrop fade show"></div>
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-{{ $locationId ? 'edit' : 'plus' }} mr-1"></i> {{ $locationId ? 'Edit' : 'Add' }} Location</h5>
                    <button type="button" class="close" wire:click="cancel">&times;</button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type</label>
                            <select wire:model.live="type" class="form-control">
                                <option value="state">State</option>
                                <option value="district">District</option>
                                <option value="zone">Zone</option>
                                <option value="panchayat">Panchayat</option>
                                <option value="village">Village</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input wire:model="name" class="form-control" placeholder="Location name">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Code <span class="text-muted small">(optional)</span></label>
                            <input wire:model="code" class="form-control" placeholder="Auto-generated if blank">
                        </div>
                        @if($type !== 'state')
                        <div class="form-group">
                            <label>Parent</label>
                            <select wire:model="parent_id" class="form-control">
                                <option value="">— Select Parent —</option>
                                @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" wire:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Import Modal --}}
    @if($showImportModal)
    <div class="modal-backdrop fade show"></div>
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-excel mr-1"></i> Import Locations</h5>
                    <button type="button" class="close" wire:click="toggleImport">&times;</button>
                </div>
                <form wire:submit="import">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Upload Excel/CSV File</label>
                            <input type="file" wire:model="importFile" class="form-control-file" accept=".xlsx,.xls,.csv">
                            @error('importFile') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <hr>
                        <h6>Expected Format</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" style="max-width:600px">
                                <thead class="thead-light"><tr><th>State</th><th>District</th><th>Zone</th><th>Panchayat</th><th>Village</th></tr></thead>
                                <tbody>
                                    <tr><td>Jammu &amp; Kashmir</td><td>Rajouri</td><td>North Zone</td><td>Panchayat A</td><td>Village 1</td></tr>
                                    <tr><td>Jammu &amp; Kashmir</td><td>Rajouri</td><td>North Zone</td><td>Panchayat A</td><td>Village 2</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-2"><i class="fas fa-info-circle"></i> Each row = one village. Parents are auto-created. Only State &amp; District required per row.</p>
                        <a href="{{ route('admin.locations.template') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fas fa-download"></i> Download Template</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" wire:click="toggleImport">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" wire:loading.attr="disabled" wire:target="import">
                            <i class="fas fa-upload"></i> Import
                            <span wire:loading wire:target="import"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($deletingId)
    <div class="modal-backdrop fade show"></div>
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-1"></i> Confirm Delete</h5>
                    <button type="button" class="close text-white" wire:click="cancelDelete">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Delete <strong>{{ $deletingName }}</strong>? This cannot be undone.</p>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" wire:click="cancelDelete">Cancel</button>
                        <button type="button" wire:click="delete" class="btn btn-danger rounded-pill px-4 shadow-sm"><i class="fas fa-trash"></i> Delete</button>
                    </div>
            </div>
        </div>
    </div>
    @endif
</div>