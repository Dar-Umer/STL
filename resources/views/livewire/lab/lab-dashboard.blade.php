<div wire:poll.60s="refresh">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div class="mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3 shadow-sm" 
                      style="width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff;">
                    <i class="fas fa-microscope fa-lg"></i>
                </span>
                <div>
                    <h3 class="mb-0 font-weight-bold" style="letter-spacing: -0.02em; color: #0f172a;">Laboratory Operations</h3>
                    <p class="text-muted small mb-0">Daily sample intake, testing queues, and result reporting</p>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button wire:click="refresh" wire:loading.attr="disabled" class="btn btn-sm btn-white border rounded-pill px-3 shadow-sm font-weight-medium text-secondary">
                <i class="fas fa-sync-alt mr-1" wire:loading.class="fa-spin" wire:target="refresh"></i> Refresh
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        {{-- Received Today --}}
        <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm text-white" 
                         style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="fas fa-download fa-lg"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Received Today</span>
                        <h3 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($todayReceived) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- To Receive --}}
        <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm text-white" 
                         style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-inbox fa-lg"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Awaiting Intake</span>
                        <h3 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($collectedCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Testing --}}
        <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm text-white" 
                         style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        <i class="fas fa-flask fa-lg"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Testing Queue</span>
                        <h3 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($pendingTesting) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed This Week --}}
        <div class="col-lg-3 col-sm-6">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm text-white" 
                         style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Completed (7 Days)</span>
                        <h3 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($completedThisWeek) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($overdueCount > 0)
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center rounded-lg shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div class="flex-grow-1">
                    @php $threshold = (int) setting('overdue_days', 2); @endphp
                    <strong class="mr-1">{{ $overdueCount }}</strong> {{ \Illuminate\Support\Str::plural('sample', $overdueCount) }} overdue for testing —
                    received more than {{ $threshold }} {{ \Illuminate\Support\Str::plural('day', $threshold) }} ago and not yet tested.
                    <a href="{{ route('lab.testing-list') }}" class="alert-link font-weight-bold ml-1">Go to Testing &rarr;</a>
                </div>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Action Buttons --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{ route('lab.queue') }}" class="btn btn-primary rounded-pill px-4 mr-2 shadow-sm font-weight-medium">
                            <i class="fas fa-inbox mr-1"></i> Receive Samples
                        </a>
                        <a href="{{ route('lab.self-sample') }}" class="btn btn-outline-primary rounded-pill px-4 mr-2 shadow-sm font-weight-medium">
                            <i class="fas fa-user-plus mr-1"></i> Self Sample Collection
                        </a>
                        <a href="{{ route('lab.testing-list') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm font-weight-medium">
                            <i class="fas fa-flask mr-1"></i> Sample Testing Queue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- To Receive + Pending Testing --}}
    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-inbox text-info mr-2"></i> Samples to Receive</h5>
                    @if($collectedSamples->count() > 0)
                        <button wire:click="receiveAll" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm font-weight-bold">
                            <i class="fas fa-check-double mr-1"></i> Receive All
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light small text-uppercase text-muted">
                            <tr><th class="border-top-0 font-weight-bold py-3">Code</th><th class="border-top-0 font-weight-bold py-3">Farmer</th><th class="border-top-0 font-weight-bold py-3">Collected</th><th class="border-top-0 font-weight-bold py-3"></th></tr>
                        </thead>
                        <tbody>
                            @forelse($collectedSamples as $sample)
                            <tr class="border-bottom align-middle">
                                <td class="py-3 font-weight-bold text-dark">{{ $sample->sample_code }}</td>
                                <td class="py-3">{{ $sample->farmer->name ?? '—' }}</td>
                                <td class="py-3 small">{{ $sample->collected_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="py-3 text-right">
                                    <button wire:click="markReceived({{ $sample->id }})" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm font-weight-bold">
                                        <i class="fas fa-check mr-1"></i> Receive
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No samples pending receipt.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-flask text-primary mr-2"></i> Pending Testing</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light small text-uppercase text-muted">
                            <tr><th class="border-top-0 font-weight-bold py-3">Code</th><th class="border-top-0 font-weight-bold py-3">Farmer</th><th class="border-top-0 font-weight-bold py-3">Received</th><th class="border-top-0 font-weight-bold py-3"></th></tr>
                        </thead>
                        <tbody>
                            @forelse($pendingSamples as $sample)
                            <tr class="border-bottom align-middle">
                                <td class="py-3 font-weight-bold text-dark">{{ $sample->sample_code }}</td>
                                <td class="py-3">{{ $sample->farmer->name ?? '—' }}</td>
                                <td class="py-3 small">{{ $sample->received_at?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('lab.testing', $sample->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm font-weight-bold">
                                        <i class="fas fa-vial mr-1"></i> Test
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No samples pending testing.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Samples --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-search mr-2"></i> Search Samples</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input wire:model.live.debounce="search" type="text" class="form-control form-control-sm bg-light border-0" placeholder="Search by sample code or farmer name...">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <select wire:model.live="statusFilter" class="form-control form-control-sm border-0 bg-light">
                                <option value="">All Statuses</option>
                                <option value="collected">Collected</option>
                                <option value="received">Received</option>
                                <option value="reported">Reported</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <select wire:model.live="dateFilter" class="form-control form-control-sm border-0 bg-light">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('samples.index') }}" class="btn btn-sm btn-outline-primary rounded-pill btn-block px-3 shadow-sm font-weight-bold">
                                <i class="fas fa-external-link-alt mr-1"></i> Full List
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light small text-uppercase text-muted">
                                <tr>
                                    <th class="border-top-0 font-weight-bold py-3">Code</th>
                                    <th class="border-top-0 font-weight-bold py-3">Farmer</th>
                                    <th class="border-top-0 font-weight-bold py-3">Plot</th>
                                    <th class="border-top-0 font-weight-bold py-3">Source</th>
                                    <th class="border-top-0 font-weight-bold py-3">Lab</th>
                                    <th class="border-top-0 font-weight-bold py-3">Status</th>
                                    <th class="border-top-0 font-weight-bold py-3">Date</th>
                                    <th class="border-top-0 font-weight-bold py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($searchResults as $sample)
                                <tr class="border-bottom align-middle">
                                    <td class="py-3 font-weight-bold text-dark">{{ $sample->sample_code }}</td>
                                    <td class="py-3">{{ $sample->farmer->name ?? '—' }}</td>
                                    <td class="py-3 small">{{ $sample->plot->plot_name ?? '—' }}</td>
                                    <td class="py-3">
                                        @if($sample->source === 'self')
                                        <span class="badge badge-pill badge-info px-3 py-1"><i class="fas fa-hand-holding mr-1"></i>Self</span>
                                        @else
                                        <span class="badge badge-pill badge-secondary px-3 py-1"><i class="fas fa-tractor mr-1"></i>Field</span>
                                        @endif
                                    </td>
                                    <td class="py-3 small">{{ $sample->lab->name ?? '—' }}</td>
                                    <td class="py-3">
                                        <span class="badge badge-pill px-3 py-1 badge-{{ $sample->status->value == 'reported' ? 'success' : ($sample->status->value == 'received' ? 'primary' : 'secondary') }}">
                                            {{ $sample->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3 small">{{ $sample->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 text-right">
                                        @if($sample->status->value == 'collected')
                                            <button wire:click="markReceived({{ $sample->id }})" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm font-weight-bold">
                                                <i class="fas fa-check mr-1"></i> Receive
                                            </button>
                                        @elseif($sample->status->value == 'received')
                                            <a href="{{ route('lab.testing', $sample->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm font-weight-bold">
                                                <i class="fas fa-vial mr-1"></i> Test
                                            </a>
                                        @else
                                            <a href="{{ route('field.samples.report', $sample->id) }}" target="_blank" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-file-pdf mr-1"></i> Report
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center py-5 text-muted">No samples match your search.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Overview + Recent Completed --}}
    <div class="row mt-4">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-chart-pie mr-2"></i> Sample Status Overview</h5>
                </div>
                <div class="card-body">
                    @php
                        $statuses = [
                            ['label' => 'Collected', 'count' => $collectedCount, 'color' => 'secondary'],
                            ['label' => 'Received', 'count' => $pendingTesting, 'color' => 'primary'],
                            ['label' => 'Reported', 'count' => $totalReported, 'color' => 'success'],
                        ];
                        $maxS = max($collectedCount, $pendingTesting, $totalReported, 1);
                    @endphp
                    @foreach($statuses as $s)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="font-weight-bold">{{ $s['label'] }}</span>
                                <span class="font-weight-bold">{{ $s['count'] }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height:24px;">
                                <div class="progress-bar bg-{{ $s['color'] }} rounded-pill" role="progressbar"
                                    style="width: {{ ($s['count'] / $maxS) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-check-circle text-success mr-2"></i> Recently Completed</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light small text-uppercase text-muted">
                            <tr><th class="border-top-0 font-weight-bold py-3">Code</th><th class="border-top-0 font-weight-bold py-3">Farmer</th><th class="border-top-0 font-weight-bold py-3">Analyzed</th><th class="border-top-0 font-weight-bold py-3"></th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentCompleted as $sample)
                            <tr class="border-bottom align-middle">
                                <td class="py-3 font-weight-bold text-dark">{{ $sample->sample_code }}</td>
                                <td class="py-3">{{ $sample->farmer->name ?? '—' }}</td>
                                <td class="py-3 small">{{ $sample->analyzed_at?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('field.samples.report', $sample->id) }}" target="_blank" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm">
                                        <i class="fas fa-file-pdf mr-1"></i> Report
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No samples completed yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Zone Target Overview --}}
    @if($zoneTargets && $zoneTargets->count())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-bullseye text-success mr-2"></i> Zone Target Overview</h5>
                    <small class="text-muted">
                        <strong>{{ number_format($totalAchievedCount) }}</strong> / {{ number_format($totalTargetCount) }} samples
                        ({{ $totalTargetCount > 0 ? round(($totalAchievedCount / $totalTargetCount) * 100) : 0 }}%)
                    </small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light small text-uppercase text-muted">
                            <tr>
                                <th class="border-top-0 font-weight-bold py-3">Zone</th>
                                <th class="border-top-0 font-weight-bold py-3 text-right">Target</th>
                                <th class="border-top-0 font-weight-bold py-3 text-right">Achieved</th>
                                <th class="border-top-0 font-weight-bold py-3 text-right">Remaining</th>
                                <th class="border-top-0 font-weight-bold py-3" style="min-width:200px">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($zoneTargets as $zt)
                            @php
                                $ach = $zt->achievedCount();
                                $rem = $zt->remainingCount();
                                $col = $zt->collectedCount();
                                $pct = $zt->progressPercent();
                                $barColor = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                            @endphp
                            <tr class="border-bottom align-middle">
                                <td class="py-3 font-weight-bold">{{ $zt->zone->name ?? 'N/A' }}</td>
                                <td class="py-3 text-right">{{ number_format($zt->target_count) }}</td>
                                <td class="py-3 text-right">{{ number_format($ach) }}</td>
                                <td class="py-3 text-right {{ $rem > 0 ? 'text-info' : 'text-danger font-weight-bold' }}">{{ number_format($rem) }}</td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-fill rounded-pill" style="height:22px;">
                                            <div class="progress-bar bg-{{ $barColor }} rounded-pill"
                                                style="width:{{ $pct }}%"
                                                aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $pct }}%
                                            </div>
                                        </div>
                                        <small class="ml-2 text-muted">{{ number_format($col) }} collected / {{ number_format($ach) }} achieved</small>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .gap-2 { gap: .5rem; }
        .align-middle td, .align-middle th { vertical-align: middle !important; }
        .table th { border-top: 0; }
    </style>
</div>
