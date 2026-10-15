<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-bullseye text-danger mr-2"></i>District & Zone Targets
            </h4>
            <p class="text-muted small mb-0">Set sample collection quotas and track testing progress by geographic zones.</p>
        </div>
        <div class="mt-3 mt-md-0">
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Set New Target
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
                <i class="fas {{ $targetId ? 'fa-edit text-warning' : 'fa-plus text-success' }} mr-2"></i>
                {{ $targetId ? 'Edit Zone Target' : 'Establish New Zone Target' }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Target Zone <span class="text-danger">*</span></label>
                        <select wire:model="zone_id" class="form-control rounded-pill @error('zone_id') is-invalid @enderror">
                            <option value="">— Select Zone —</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        @error('zone_id') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Sample Target Count <span class="text-danger">*</span></label>
                        <input wire:model="target_count" type="number" min="1" class="form-control rounded-pill @error('target_count') is-invalid @enderror" placeholder="e.g. 500">
                        @error('target_count') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Target Period Start <span class="text-danger">*</span></label>
                        <input wire:model="start_date" type="date" class="form-control rounded-pill @error('start_date') is-invalid @enderror">
                        @error('start_date') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Target Period End <span class="text-danger">*</span></label>
                        <input wire:model="end_date" type="date" class="form-control rounded-pill @error('end_date') is-invalid @enderror">
                        @error('end_date') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> {{ $targetId ? 'Update Target' : 'Save Target' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Target Directory Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-bullseye text-muted mr-2"></i>Active Zone Targets</span>
                <span class="badge badge-light border text-muted">{{ $targets->total() }} total</span>
            </div>
            <div style="width: 100%; max-width: 300px;">
                <input wire:model.live.debounce.300ms="search" placeholder="Search zone targets..." class="form-control form-control-sm rounded-pill bg-light border-0">
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Target Zone</th>
                        <th>Target Period</th>
                        <th class="text-right">Target</th>
                        <th class="text-right">Achieved</th>
                        <th class="text-right">Collected</th>
                        <th class="text-right">Remaining</th>
                        <th style="min-width: 160px;">Target Pace</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($targets as $target)
                        @php
                            $ach = $target->achievedCount();
                            $rem = $target->remainingCount();
                            $col = $target->collectedCount();
                            $pct = $target->progressPercent();
                            $barColor = $pct >= 80 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <tr>
                            <td>
                                <span class="font-weight-bold text-dark d-block">
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $target->zone->name ?? 'Zone' }}
                                </span>
                                <small class="text-muted">By {{ $target->creator->name ?? 'Admin' }}</small>
                            </td>
                            <td class="small text-muted">
                                {{ $target->start_date?->format('d M Y') }} &ndash; {{ $target->end_date?->format('d M Y') }}
                            </td>
                            <td class="text-right font-weight-bold text-dark">{{ number_format($target->target_count) }}</td>
                            <td class="text-right text-success font-weight-bold">{{ number_format($ach) }}</td>
                            <td class="text-right text-muted">{{ number_format($col) }}</td>
                            <td class="text-right font-weight-bold {{ $rem > 0 ? 'text-primary' : 'text-muted' }}">{{ number_format($rem) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-fill mr-2" style="height: 8px; background-color: #f1f5f9;">
                                        <div class="progress-bar" style="width: {{ min($pct, 100) }}%; background-color: {{ $barColor }};"></div>
                                    </div>
                                    <span class="small font-weight-bold text-muted">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $target->id }})" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none mr-1" title="Edit Target">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button wire:click="delete({{ $target->id }})" wire:confirm="Delete this target?" class="btn btn-sm btn-outline-danger rounded-pill px-2 shadow-none" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-bullseye fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No zone targets configured</h6>
                                <p class="small text-muted mb-0">Click "Set New Target" to establish collection goals.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($targets->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $targets->links() }}
        </div>
        @endif
    </div>
</div>