<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-history mr-2"></i>Audit Log</h4>
        <button wire:click="exportCsv" class="btn btn-success rounded-pill btn-sm px-3 shadow-sm">
            <i class="fas fa-download mr-1"></i>Export CSV
        </button>
        <button wire:click="clearLogs" wire:confirm="Are you sure you want to clear ALL audit logs? This cannot be undone." class="btn btn-danger rounded-pill btn-sm px-3 shadow-sm">
            <i class="fas fa-trash mr-1"></i>Clear Logs
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm rounded-lg text-center py-2">
                <div class="card-body p-2">
                    <h5 class="mb-0 text-primary">{{ number_format($logs->total()) }}</h5>
                    <small class="text-muted">Total Logs</small>
                </div>
            </div>
        </div>
        @foreach($eventCounts as $event => $count)
            <div class="col-md-2">
                <div class="card border-0 shadow-sm rounded-lg text-center py-2">
                    <div class="card-body p-2">
                        <h5 class="mb-0 @if($event === 'created') text-success @elseif($event === 'updated') text-warning @elseif($event === 'deleted') text-danger @elseif($event === 'login' || $event === 'logout') text-info @else text-secondary @endif">
                            {{ number_format($count) }}
                        </h5>
                        <small class="text-muted">{{ ucfirst($event) }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            {{-- Filters --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <input wire:model.live="search" placeholder="Search user, IP, type..." class="form-control form-control-sm rounded-pill">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="eventFilter" class="form-control form-control-sm rounded-pill">
                        <option value="">All Events</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                        <option value="settings_changed">Settings Changed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="typeFilter" class="form-control form-control-sm rounded-pill">
                        <option value="">All Entity Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ class_basename($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" wire:model.live="dateFrom" class="form-control form-control-sm rounded-pill" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" wire:model.live="dateTo" class="form-control form-control-sm rounded-pill" placeholder="To">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th style="width:5%"></th>
                            <th>Time</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Entity</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr wire:click="toggleExpand({{ $log->id }})" style="cursor:pointer" class="{{ $expandedId === $log->id ? 'table-active' : '' }}">
                                <td class="text-center align-middle">
                                    <i class="fas fa-chevron-{{ $expandedId === $log->id ? 'down' : 'right' }} text-muted small"></i>
                                </td>
                                <td class="small align-middle">{{ $log->created_at?->format('d M Y, h:i A') }}</td>
                                <td class="align-middle">
                                    <span class="font-weight-bold">{{ $log->user?->name ?? 'System' }}</span>
                                </td>
                                <td class="align-middle">
                                    @if($log->event === 'created')
                                        <span class="badge badge-success"><i class="fas fa-plus-circle mr-1"></i>Created</span>
                                    @elseif($log->event === 'updated')
                                        <span class="badge badge-warning text-dark"><i class="fas fa-edit mr-1"></i>Updated</span>
                                    @elseif($log->event === 'deleted')
                                        <span class="badge badge-danger"><i class="fas fa-trash mr-1"></i>Deleted</span>
                                    @elseif($log->event === 'login')
                                        <span class="badge badge-info"><i class="fas fa-sign-in-alt mr-1"></i>Login</span>
                                    @elseif($log->event === 'logout')
                                        <span class="badge badge-secondary"><i class="fas fa-sign-out-alt mr-1"></i>Logout</span>
                                    @elseif($log->event === 'settings_changed')
                                        <span class="badge badge-primary"><i class="fas fa-cog mr-1"></i>Settings</span>
                                    @else
                                        <span class="badge badge-dark">{{ $log->event }}</span>
                                    @endif
                                </td>
                                <td class="small align-middle">{{ $log->auditable_label }}</td>
                                <td class="small align-middle text-muted">{{ $log->ip_address }}</td>
                            </tr>

                            @if($expandedId === $log->id)
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <div class="bg-light p-3 border-top">
                                            <div class="row">
                                                @if($log->old_values)
                                                    <div class="col-md-6">
                                                        <h6 class="text-danger mb-2"><i class="fas fa-arrow-left mr-1"></i>Old Values</h6>
                                                        <div class="bg-white rounded p-2 small border">
                                                            @foreach($log->old_values as $key => $value)
                                                                <div class="mb-1">
                                                                    <span class="font-weight-bold text-muted">{{ $key }}:</span>
                                                                    <span class="text-danger">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($log->new_values)
                                                    <div class="col-md-6">
                                                        <h6 class="text-success mb-2"><i class="fas fa-arrow-right mr-1"></i>New Values</h6>
                                                        <div class="bg-white rounded p-2 small border">
                                                            @foreach($log->new_values as $key => $value)
                                                                <div class="mb-1">
                                                                    <span class="font-weight-bold text-muted">{{ $key }}:</span>
                                                                    <span class="text-success">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($log->url)
                                                <div class="mt-2 small text-muted">
                                                    <i class="fas fa-link mr-1"></i>{{ $log->url }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-history fa-2x mb-2 d-block"></i>
                                    No audit logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
