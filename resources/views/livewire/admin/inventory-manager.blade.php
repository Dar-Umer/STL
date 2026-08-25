<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Inventory Management</h5>
        <button class="btn btn-success rounded-pill shadow-sm" wire:click="openAdd">
            <i class="fas fa-plus"></i> Add Chemical
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control rounded-pill" placeholder="Search by name or SKU..."
                           wire:model.live.debounce.300ms="search">
                </div>
                @can('manage-users')
                <div class="col-md-3">
                    <select class="form-control rounded-pill" wire:model.live="labId">
                        <option value="">All Labs</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            @can('manage-users')<th>Lab</th>@endcan
                            <th>Unit</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chemicals as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td><code>{{ $c->sku }}</code></td>
                                @can('manage-users')<td>{{ $c->lab?->name }}</td>@endcan
                                <td>{{ $c->unit }}</td>
                                <td>
                                    {{ number_format((float) $c->current_stock, 2) }}
                                    @if($c->minimum_stock)
                                        <small class="text-muted">/ {{ number_format((float) $c->minimum_stock, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php $status = $c->stockStatus(); @endphp
                                    <span class="badge rounded-pill px-3 py-1
                                        @if($status === 'normal') badge-success
                                        @elseif($status === 'low') badge-warning
                                        @elseif($status === 'critical') badge-danger
                                        @else badge-dark @endif">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-outline-success rounded-pill px-3"
                                            wire:click="openStockForm({{ $c->id }})"
                                            title="Add Stock">
                                        <i class="fas fa-plus-circle"></i> Stock
                                    </button>
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3"
                                            wire:click="openHistory({{ $c->id }})"
                                            title="History">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    <a href="{{ route('admin.inventory.consumption', $c->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                       title="Consumption Rules">
                                        <i class="fas fa-flask"></i> Rules
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                            wire:click="edit({{ $c->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-{{ $c->is_active ? 'danger' : 'success' }} rounded-pill px-3"
                                            wire:click="toggleActive({{ $c->id }})" title="{{ $c->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $c->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No chemicals found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $chemicals->links() }}
            </div>
        </div>
    </div>

    @if($showAddForm)
    <div class="modal-backdrop fade show" style="z-index:1040"></div>
    <div class="modal d-block" tabindex="-1" style="z-index:1050">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editId ? 'Edit' : 'Add' }} Chemical</h5>
                    <button type="button" class="close" wire:click="$set('showAddForm', false)">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control rounded-pill" wire:model="name">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" class="form-control rounded-pill" wire:model="sku">
                        @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Unit</label>
                        <input type="text" class="form-control rounded-pill" placeholder="ml, g, units..."
                               wire:model="unit">
                        @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Minimum Stock (optional)</label>
                        <input type="number" step="0.01" min="0" class="form-control rounded-pill"
                               wire:model="minimumStock">
                        @error('minimumStock') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    @can('manage-users')
                    <div class="form-group">
                        <label>Lab</label>
                        <select class="form-control rounded-pill" wire:model="selectedLab">
                            <option value="">Select Lab</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedLab') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    @endcan
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary rounded-pill" wire:click="$set('showAddForm', false)">Cancel</button>
                    <button class="btn btn-success rounded-pill shadow-sm" wire:click="save">
                        <i class="fas fa-save"></i> {{ $editId ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showStockForm)
    <div class="modal-backdrop fade show" style="z-index:1040"></div>
    <div class="modal d-block" tabindex="-1" style="z-index:1050">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock</h5>
                    <button type="button" class="close" wire:click="$set('showStockForm', false)">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" step="0.01" min="0.01" class="form-control rounded-pill"
                               wire:model="stockQuantity">
                        @error('stockQuantity') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Notes (optional)</label>
                        <textarea class="form-control rounded-pill" wire:model="stockNotes" rows="2"></textarea>
                        @error('stockNotes') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary rounded-pill" wire:click="$set('showStockForm', false)">Cancel</button>
                    <button class="btn btn-success rounded-pill shadow-sm" wire:click="addStock">
                        <i class="fas fa-plus"></i> Add Stock
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showHistory && $historyChemical)
    <div class="modal-backdrop fade show" style="z-index:1040"></div>
    <div class="modal d-block" tabindex="-1" style="z-index:1050">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock History — {{ $historyChemical->name }}</h5>
                    <button type="button" class="close" wire:click="closeHistory">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Reference</th>
                                    <th>Notes</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historyChemical->stockMovements as $m)
                                    <tr>
                                        <td>{{ $m->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $m->type === 'in' ? 'success' : 'danger' }} rounded-pill px-2">
                                                {{ strtoupper($m->type) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format((float) $m->quantity, 2) }}</td>
                                        <td>{{ number_format((float) $m->balance_before, 2) }}</td>
                                        <td>{{ number_format((float) $m->balance_after, 2) }}</td>
                                        <td><small class="text-muted">{{ $m->reference_type ? class_basename($m->reference_type) . '#' . $m->reference_id : '-' }}</small></td>
                                        <td><small>{{ $m->notes ?? '-' }}</small></td>
                                        <td><small>{{ $m->creator?->name ?? '-' }}</small></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted">No movements.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary rounded-pill" wire:click="closeHistory">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
