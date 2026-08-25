<div wire:poll.60s="refresh">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard</h4>
        <div>
            <small class="text-muted mr-2">Last updated: {{ now()->format('d/m/Y h:i A') }}</small>
            <button wire:click="refresh" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
        </div>
    </div>

    {{-- System Overview KPIs --}}
    <div class="row mb-4">
        <div class="col-lg-2 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(23,162,184,.12);"><i class="fas fa-flask text-info fa-lg"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Labs</p><h3 class="mb-0 font-weight-bold">{{ number_format($totalLabs) }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(108,117,125,.12);"><i class="fas fa-users text-secondary fa-lg"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Users</p><h3 class="mb-0 font-weight-bold">{{ number_format($totalUsers) }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(255,193,7,.12);"><i class="fas fa-tractor text-warning fa-lg"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Farmers</p><h3 class="mb-0 font-weight-bold">{{ number_format($totalFarmers) }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(0,123,255,.12);"><i class="fas fa-vial text-primary fa-lg"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Total Samples</p><h3 class="mb-0 font-weight-bold">{{ number_format($totalSamples) }}</h3></div>
            </div></div>
        </div>
        <div class="col-lg-2 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(40,167,69,.12);"><i class="fas fa-calendar-check text-success fa-lg"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">This Month</p><h3 class="mb-0 font-weight-bold">{{ number_format($samplesThisMonth) }}</h3>
                    @if($samplesLastMonth > 0)
                    <small class="text-muted d-block" style="font-size:.7rem;">{{ ($samplesThisMonth > $samplesLastMonth ? '+' : '').number_format((($samplesThisMonth - $samplesLastMonth) / $samplesLastMonth) * 100, 0) }}% vs last month</small>
                    @endif
                </div>
            </div></div>
        </div>
        <div class="col-lg-2 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body py-3 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(220,53,69,.12);"><i class="fas fa-clock text-danger fa-lg"></i></div>
                <div><p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Pending</p><h3 class="mb-0 font-weight-bold">{{ number_format($queueCount) }}</h3></div>
            </div></div>
        </div>
    </div>

    {{-- User Role Breakdown --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3">
                    <h6 class="mb-0"><i class="fas fa-user-shield mr-2"></i>Users by Role</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row text-center">
                        <div class="col"><span class="badge badge-danger rounded-pill px-3 py-2">Admin: {{ $adminCount }}</span></div>
                        <div class="col"><span class="badge badge-warning rounded-pill px-3 py-2">Lab Manager: {{ $labManagerCount }}</span></div>
                        <div class="col"><span class="badge badge-info rounded-pill px-3 py-2">Field Official: {{ $fieldOfficialCount }}</span></div>
                        <div class="col"><span class="badge badge-primary rounded-pill px-3 py-2">Lab Technician: {{ $labTechnicianCount }}</span></div>
                        <div class="col"><span class="badge badge-secondary rounded-pill px-3 py-2">Farmer: {{ $farmerCount }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Distribution + Monthly Trend --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Sample Status Distribution</h6>
                    <small class="text-muted">{{ number_format($totalSamples) }} total</small>
                </div>
                <div class="card-body">
                    @php
                        $statuses = [
                            ['label' => 'Collected', 'count' => $samplesCollected, 'color' => 'info', 'icon' => 'fa-clipboard-list'],
                            ['label' => 'Received', 'count' => $samplesReceived, 'color' => 'primary', 'icon' => 'fa-download'],
                            ['label' => 'Testing', 'count' => $samplesTesting, 'color' => 'warning', 'icon' => 'fa-flask'],
                            ['label' => 'Reported', 'count' => $samplesReported, 'color' => 'success', 'icon' => 'fa-check-circle'],
                        ];
                    @endphp
                    @foreach($statuses as $s)
                        @php $pct = $totalSamples > 0 ? round(($s['count'] / $totalSamples) * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="fas {{ $s['icon'] }} text-{{ $s['color'] }} mr-1"></i> {{ $s['label'] }}</span>
                                <span class="font-weight-bold">{{ number_format($s['count']) }} <small class="text-muted">({{ $pct }}%)</small></span>
                            </div>
                            <div class="progress" style="height: 24px;">
                                <div class="progress-bar bg-{{ $s['color'] }} progress-bar-striped" role="progressbar"
                                    style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                    @if($pct > 8){{ $pct }}%@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom">
                    <h6 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Monthly Trend (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    @php $maxMonthly = max(max($monthlyCounts), 1); @endphp
                    <div class="d-flex align-items-end" style="height: 200px; gap: 8px;">
                        @foreach($monthlyCounts as $i => $count)
                            @php $height = max(($count / $maxMonthly) * 170, 4); @endphp
                            <div class="d-flex flex-column align-items-center flex-fill">
                                <small class="mb-1 font-weight-bold text-{{ $count > 0 ? 'primary' : 'muted' }}">{{ $count }}</small>
                                <div class="bg-gradient-primary w-100 rounded" style="height: {{ $height }}px; transition: height 0.3s;"></div>
                                <small class="mt-1 text-muted" style="font-size: 10px;">{{ \Carbon\Carbon::parse($monthlyLabels[$i])->format('M') }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lab-wise Performance --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-microscope mr-2"></i>Lab-wise Performance</h6>
                    <small class="text-muted">Active labs performance overview</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Lab</th>
                                <th class="text-right">Samples</th>
                                <th class="text-right">Collected</th>
                                <th class="text-right">Received</th>
                                <th class="text-right">Testing</th>
                                <th class="text-right">Reported</th>
                                <th class="text-right">Farmers</th>
                                <th class="text-right">Users</th>
                                <th>Report Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labStats as $lab)
                                @php $reportPct = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $lab->name }} <small class="text-muted">({{ $lab->code }})</small></td>
                                    <td class="text-right">{{ number_format($lab->soil_samples_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->collected_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->received_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->testing_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->reported_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->farmers_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->users_count) }}</td>
                                    <td>
                                        <div class="progress" style="height: 18px;">
                                            <div class="progress-bar bg-{{ $reportPct >= 80 ? 'success' : ($reportPct >= 50 ? 'warning' : 'danger') }}"
                                                style="width: {{ $reportPct }}%">
                                                {{ $reportPct }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center py-4 text-muted">No labs found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Zone-wise Sample Distribution --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-map-marked-alt mr-2"></i>Zone-wise Sample Distribution</h6>
                    <small class="text-muted">Samples collected per zone</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Zone</th>
                                <th class="text-right">Total Samples</th>
                                <th class="text-right">Collected</th>
                                <th class="text-right">Received</th>
                                <th class="text-right">Testing</th>
                                <th class="text-right">Reported</th>
                                <th class="text-right">Field Officials</th>
                                <th>Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($zoneStats as $zone)
                                @php $compPct = $zone->samples_count > 0 ? round(($zone->reported_count / $zone->samples_count) * 100) : 0; @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $zone->name }}</td>
                                    <td class="text-right">{{ number_format($zone->samples_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->collected_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->received_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->testing_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->reported_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->field_officials_count) }}</td>
                                    <td>
                                        <div class="progress" style="height: 18px;">
                                            <div class="progress-bar bg-{{ $compPct >= 80 ? 'success' : ($compPct >= 50 ? 'warning' : 'danger') }}"
                                                style="width: {{ $compPct }}%">
                                                {{ $compPct }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-muted">No zones found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Zone Target Overview --}}
    @if($zoneTargets->count())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-bullseye text-success mr-2"></i>Zone Target Overview</h6>
                    <small class="text-muted mr-3">
                        <strong>{{ number_format($totalAchievedCount) }}</strong> / {{ number_format($totalTargetCount) }} achieved
                        ({{ $totalTargetCount > 0 ? round(($totalAchievedCount / $totalTargetCount) * 100) : 0 }}%)
                    </small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Zone</th>
                                <th class="text-right">Target</th>
                                <th class="text-right">Achieved</th>
                                <th class="text-right">Collected</th>
                                <th class="text-right">Left</th>
                                <th style="min-width:200px">Progress</th>
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
                            <tr>
                                <td class="font-weight-bold">{{ $zt->zone->name ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($zt->target_count) }}</td>
                                <td class="text-right">{{ number_format($ach) }}</td>
                                <td class="text-right">{{ number_format($col) }}</td>
                                <td class="text-right {{ $rem > 0 ? 'text-info font-weight-bold' : 'text-danger font-weight-bold' }}">{{ number_format($rem) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-fill" style="height:22px">
                                            <div class="progress-bar bg-{{ $barColor }}" style="width:{{ $pct }}%">
                                                {{ $pct }}%
                                            </div>
                                        </div>
                                        <small class="ml-2 text-muted">{{ number_format($col) }} / {{ number_format($ach) }}</small>
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

    {{-- Recent Samples --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Recent Samples</h6>
                    <a href="{{ route('samples.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Code</th><th>Farmer</th><th>Lab</th><th>Zone</th><th>Status</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentSamples as $sample)
                            <tr>
                                <td class="font-weight-bold">{{ $sample->sample_code }}</td>
                                <td>{{ $sample->farmer->name ?? '—' }}</td>
                                <td>{{ $sample->lab->name ?? '—' }}</td>
                                <td>{{ $sample->farmer->zone->name ?? '—' }}</td>
                                <td>
                                                    <span class="badge badge-{{ $sample->status->value == 'reported' ? 'success' : ($sample->status->value == 'testing' ? 'warning' : ($sample->status->value == 'received' ? 'primary' : 'info')) }} rounded-pill px-3 py-1">
                                                        {{ $sample->status->label() }}
                                                    </span>
                                </td>
                                <td>{{ $sample->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">No samples yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
