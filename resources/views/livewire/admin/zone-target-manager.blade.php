<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-bullseye mr-2"></i>Zone Targets</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> New Target</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Zone</label>
                            <select wire:model="zone_id" class="form-control rounded-pill">
                                <option value="">Select Zone</option>
                                @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            @error('zone_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Target (samples)</label>
                            <input wire:model="target_count" type="number" min="1" class="form-control rounded-pill">
                            @error('target_count') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input wire:model="start_date" type="date" class="form-control rounded-pill">
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>End Date</label>
                            <input wire:model="end_date" type="date" class="form-control rounded-pill">
                            @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Save</button>
                <button type="button" wire:click="cancel" class="btn btn-secondary rounded-pill px-3">Cancel</button>
            </form>
            <hr>
            @endif

            <div class="mb-3">
                <input wire:model.live="search" placeholder="Search by zone name..." class="form-control rounded-pill">
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th>Zone</th><th>Period</th><th class="text-right">Target</th><th class="text-right">Achieved</th><th class="text-right">Left</th><th>Progress</th><th>Created By</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($targets as $target)
                        @php
                            $achieved = $target->achievedCount();
                            $left = $target->remainingCount();
                            $pct = $target->progressPercent();
                            $barColor = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                        @endphp
                        <tr>
                            <td>{{ $target->zone->name ?? 'N/A' }}</td>
                            <td>{{ $target->start_date->format('d M Y') }} &ndash; {{ $target->end_date->format('d M Y') }}</td>
                            <td class="text-right">{{ number_format($target->target_count) }}</td>
                            <td class="text-right">{{ number_format($achieved) }}</td>
                            <td class="text-right {{ $left > 0 ? '' : 'text-danger font-weight-bold' }}">{{ number_format($left) }}</td>
                            <td style="min-width: 180px;">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-fill rounded-pill" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $barColor }} rounded-pill" role="progressbar"
                                            style="width: {{ $pct }}%"
                                            aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $pct }}%
                                        </div>
                                    </div>
                                    <span class="ml-2 font-weight-bold text-{{ $barColor }}">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td>{{ $target->creator?->name ?? 'System' }}</td>
                            <td>
                                <button wire:click="edit({{ $target->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $target->id }})" onclick="return confirm('Delete this target?')" class="btn btn-sm btn-danger rounded-pill px-3"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No targets defined yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">{{ $targets->links() }}</div>
        </div>
    </div>
</div>