<div wire:poll.60s="refresh">
    {{-- Dashboard Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div class="mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3 shadow-sm" 
                      style="width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff;">
                    <i class="fas fa-tachometer-alt fa-lg"></i>
                </span>
                <div>
                    <h3 class="mb-0 font-weight-bold" style="letter-spacing: -0.02em; color: #0f172a;">Admin Dashboard</h3>
                    <p class="text-muted small mb-0">System performance, sample lifecycle analytics & district lab operations</p>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge badge-light border mr-3 px-3 py-2 text-muted" style="font-size: 0.8rem;">
                <span class="d-inline-block rounded-circle bg-success mr-1" style="width: 8px; height: 8px; animation: pulse 2s infinite;"></span>
                Live &bull; Updated {{ now()->format('h:i A') }}
            </span>
            <button wire:click="refresh" wire:loading.attr="disabled" class="btn btn-sm btn-white border rounded-pill px-3 shadow-sm font-weight-medium text-secondary">
                <i class="fas fa-sync-alt mr-1" wire:loading.class="fa-spin" wire:target="refresh"></i> Refresh
            </button>
        </div>
    </div>

    {{-- System Overview KPIs --}}
    <div class="row mb-4">
        {{-- Labs --}}
        <div class="col-xl-2 col-lg-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm" 
                         style="width: 50px; height: 50px; min-width: 50px; border-radius: 12px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: #fff;">
                        <i class="fas fa-flask fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.06em;">Laboratories</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($totalLabs) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Active Testing Centers</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users --}}
        <div class="col-xl-2 col-lg-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm" 
                         style="width: 50px; height: 50px; min-width: 50px; border-radius: 12px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff;">
                        <i class="fas fa-users-cog fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.06em;">Staff & Admins</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($totalUsers) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Registered Accounts</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Farmers --}}
        <div class="col-xl-2 col-lg-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm" 
                         style="width: 50px; height: 50px; min-width: 50px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff;">
                        <i class="fas fa-tractor fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.06em;">Farmers</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($totalFarmers) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Enrolled Beneficiaries</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Samples --}}
        <div class="col-xl-2 col-lg-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm" 
                         style="width: 50px; height: 50px; min-width: 50px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #fff;">
                        <i class="fas fa-vial fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.06em;">Total Samples</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($totalSamples) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">All Time Registered</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- This Month --}}
        <div class="col-xl-2 col-lg-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm" 
                         style="width: 50px; height: 50px; min-width: 50px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff;">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.06em;">This Month</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($samplesThisMonth) }}</h4>
                        @if($samplesLastMonth > 0)
                            @php $pctMonth = (($samplesThisMonth - $samplesLastMonth) / $samplesLastMonth) * 100; @endphp
                            <span class="text-{{ $pctMonth >= 0 ? 'success' : 'danger' }} small font-weight-bold" style="font-size: 0.72rem;">
                                <i class="fas fa-arrow-{{ $pctMonth >= 0 ? 'up' : 'down' }} mr-1"></i>{{ ($pctMonth > 0 ? '+' : '') . number_format($pctMonth, 0) }}% MoM
                            </span>
                        @else
                            <span class="text-muted small" style="font-size: 0.75rem;">Current Cycle</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="col-xl-2 col-lg-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm" 
                         style="width: 50px; height: 50px; min-width: 50px; border-radius: 12px; background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); color: #fff;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.06em;">In Queue</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($queueCount) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Awaiting Testing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Role Breakdown --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-shield text-primary mr-2"></i>
                            <span class="font-weight-bold text-dark" style="font-size: 0.92rem;">User Distribution by Role</span>
                        </div>
                        <small class="text-muted">Total platform accounts: {{ number_format($totalUsers + $farmerCount) }}</small>
                    </div>
                    <div class="row">
                        <div class="col-md col-sm-4 col-6 mb-2 mb-md-0">
                            <div class="p-2 rounded-lg d-flex align-items-center" style="background: #fef2f2; border: 1px solid #fee2e2;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 text-danger bg-white shadow-xs" style="width:34px;height:34px;min-width:34px;">
                                    <i class="fas fa-user-lock"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-muted font-weight-bold text-uppercase" style="font-size:0.65rem;letter-spacing:0.05em;">Admin</div>
                                    <div class="h6 mb-0 font-weight-bold text-danger">{{ number_format($adminCount) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-sm-4 col-6 mb-2 mb-md-0">
                            <div class="p-2 rounded-lg d-flex align-items-center" style="background: #fffbeb; border: 1px solid #fef3c7;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 text-warning bg-white shadow-xs" style="width:34px;height:34px;min-width:34px;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-muted font-weight-bold text-uppercase" style="font-size:0.65rem;letter-spacing:0.05em;">Lab Manager</div>
                                    <div class="h6 mb-0 font-weight-bold text-warning">{{ number_format($labManagerCount) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-sm-4 col-6 mb-2 mb-md-0">
                            <div class="p-2 rounded-lg d-flex align-items-center" style="background: #f0fdfa; border: 1px solid #ccfbf1;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 text-info bg-white shadow-xs" style="width:34px;height:34px;min-width:34px;">
                                    <i class="fas fa-hard-hat"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-muted font-weight-bold text-uppercase" style="font-size:0.65rem;letter-spacing:0.05em;">Field Official</div>
                                    <div class="h6 mb-0 font-weight-bold text-info">{{ number_format($fieldOfficialCount) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-sm-4 col-6 mb-2 mb-md-0">
                            <div class="p-2 rounded-lg d-flex align-items-center" style="background: #eff6ff; border: 1px solid #dbeafe;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 text-primary bg-white shadow-xs" style="width:34px;height:34px;min-width:34px;">
                                    <i class="fas fa-microscope"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-muted font-weight-bold text-uppercase" style="font-size:0.65rem;letter-spacing:0.05em;">Lab Tech</div>
                                    <div class="h6 mb-0 font-weight-bold text-primary">{{ number_format($labTechnicianCount) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-sm-4 col-6 mb-2 mb-md-0">
                            <div class="p-2 rounded-lg d-flex align-items-center" style="background: #f1f5f9; border: 1px solid #e2e8f0;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 text-secondary bg-white shadow-xs" style="width:34px;height:34px;min-width:34px;">
                                    <i class="fas fa-tractor"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-muted font-weight-bold text-uppercase" style="font-size:0.65rem;letter-spacing:0.05em;">Farmers</div>
                                    <div class="h6 mb-0 font-weight-bold text-secondary">{{ number_format($farmerCount) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Distribution + Monthly Trend --}}
    <div class="row mb-4">
        {{-- Status Distribution --}}
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span class="font-weight-bold text-dark"><i class="fas fa-chart-pie text-success mr-2"></i>Sample Lifecycle Distribution</span>
                    <span class="badge badge-light border text-muted">{{ number_format($totalSamples) }} total samples</span>
                </div>
                <div class="card-body p-4">
                    @php
                        $statuses = [
                            ['label' => 'Collected in Field', 'count' => $samplesCollected, 'color' => '#06b6d4', 'bg' => 'rgba(6, 182, 212, 0.1)', 'icon' => 'fa-clipboard-list'],
                            ['label' => 'Received at Lab', 'count' => $samplesReceived, 'color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.1)', 'icon' => 'fa-inbox'],
                            ['label' => 'Testing in Progress', 'count' => $samplesTesting, 'color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.1)', 'icon' => 'fa-flask'],
                            ['label' => 'Reported & Completed', 'count' => $samplesReported, 'color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.1)', 'icon' => 'fa-check-circle'],
                        ];
                    @endphp
                    @foreach($statuses as $s)
                        @php $pct = $totalSamples > 0 ? round(($s['count'] / $totalSamples) * 100) : 0; @endphp
                        <div class="mb-3 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-2" 
                                          style="width: 28px; height: 28px; background: {{ $s['bg'] }}; color: {{ $s['color'] }};">
                                        <i class="fas {{ $s['icon'] }}" style="font-size: 0.8rem;"></i>
                                    </span>
                                    <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $s['label'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-weight-bold text-dark mr-1">{{ number_format($s['count']) }}</span>
                                    <span class="badge badge-light border text-muted" style="font-size: 0.75rem;">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; background-color: #f1f5f9;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $pct }}%; background-color: {{ $s['color'] }}; transition: width 0.6s ease;" 
                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Monthly Trend --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span class="font-weight-bold text-dark"><i class="fas fa-chart-bar text-primary mr-2"></i>Sample Inflow Trend (Last 6 Months)</span>
                    <span class="badge badge-light border text-muted">Monthly Volume</span>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    @php $maxMonthly = max(max($monthlyCounts), 1); @endphp
                    <div class="d-flex align-items-end justify-content-between" style="height: 180px; gap: 14px; padding-bottom: 8px;">
                        @foreach($monthlyCounts as $i => $count)
                            @php 
                                $height = max(($count / $maxMonthly) * 140, 8); 
                                $isLatest = $i === (count($monthlyCounts) - 1);
                            @endphp
                            <div class="d-flex flex-column align-items-center flex-fill position-relative">
                                <span class="mb-1 font-weight-bold {{ $count > 0 ? ($isLatest ? 'text-success' : 'text-primary') : 'text-muted' }}" 
                                      style="font-size: 0.8rem;">
                                    {{ $count }}
                                </span>
                                <div class="w-100 rounded-pill shadow-xs" 
                                     style="height: {{ $height }}px; min-height: 8px; background: {{ $isLatest ? 'linear-gradient(180deg, #10b981 0%, #059669 100%)' : 'linear-gradient(180deg, #60a5fa 0%, #2563eb 100%)' }}; transition: all 0.4s ease;"
                                     title="{{ \Carbon\Carbon::parse($monthlyLabels[$i])->format('F Y') }}: {{ $count }} samples">
                                </div>
                                <span class="mt-2 text-muted font-weight-medium" style="font-size: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($monthlyLabels[$i])->format('M') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                        <span><i class="fas fa-info-circle mr-1"></i> Bars depict total samples collected across all districts</span>
                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i>Current Month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lab-wise Performance --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="font-weight-bold text-dark"><i class="fas fa-microscope text-info mr-2"></i>Laboratory Performance Metrics</span>
                        <span class="text-muted small ml-2 d-none d-md-inline">&bull; Sample progress by testing facility</span>
                    </div>
                    <a href="{{ route('admin.labs') }}" class="btn btn-xs btn-outline-secondary rounded-pill px-3">
                        Manage Labs <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Laboratory</th>
                                <th class="text-right">Total Samples</th>
                                <th class="text-right">Collected</th>
                                <th class="text-right">Received</th>
                                <th class="text-right">Testing</th>
                                <th class="text-right">Reported</th>
                                <th class="text-right">Farmers</th>
                                <th class="text-right">Staff</th>
                                <th style="min-width: 170px;">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labStats as $lab)
                                @php 
                                    $reportPct = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; 
                                    $barColor = $reportPct >= 80 ? '#10b981' : ($reportPct >= 40 ? '#f59e0b' : '#3b82f6');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-2" style="width:30px;height:30px;background:#e0f2fe;color:#0284c7;">
                                                <i class="fas fa-flask" style="font-size:0.75rem;"></i>
                                            </span>
                                            <div>
                                                <span class="font-weight-bold text-dark d-block">{{ $lab->name }}</span>
                                                <span class="text-muted small">Code: {{ $lab->code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-bold">{{ number_format($lab->soil_samples_count) }}</td>
                                    <td class="text-right text-muted">{{ number_format($lab->collected_count) }}</td>
                                    <td class="text-right text-primary">{{ number_format($lab->received_count) }}</td>
                                    <td class="text-right text-warning">{{ number_format($lab->testing_count) }}</td>
                                    <td class="text-right text-success font-weight-bold">{{ number_format($lab->reported_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->farmers_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->users_count) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill mr-2" style="height: 10px; background-color: #f1f5f9;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $reportPct }}%; background-color: {{ $barColor }};">
                                                </div>
                                            </div>
                                            <span class="font-weight-bold small text-muted" style="min-width: 36px;">{{ $reportPct }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-flask fa-2x mb-2 text-muted d-block opacity-50"></i>
                                        No active laboratories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Zone-wise Distribution & Zone Target Overview --}}
    <div class="row mb-4">
        {{-- Zone Distribution --}}
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span class="font-weight-bold text-dark"><i class="fas fa-map-marked-alt text-warning mr-2"></i>Zone-wise Sample Distribution</span>
                    <span class="text-muted small">By Geographic Zone</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Zone</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Reported</th>
                                <th class="text-right">Officials</th>
                                <th style="min-width: 140px;">Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($zoneStats as $zone)
                                @php 
                                    $compPct = $zone->samples_count > 0 ? round(($zone->reported_count / $zone->samples_count) * 100) : 0; 
                                    $barColor = $compPct >= 80 ? '#10b981' : ($compPct >= 40 ? '#f59e0b' : '#3b82f6');
                                @endphp
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        <i class="fas fa-map-pin text-muted mr-1"></i> {{ $zone->name }}
                                    </td>
                                    <td class="text-right font-weight-bold">{{ number_format($zone->samples_count) }}</td>
                                    <td class="text-right text-success font-weight-bold">{{ number_format($zone->reported_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->field_officials_count) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill mr-2" style="height: 8px; background-color: #f1f5f9;">
                                                <div class="progress-bar" style="width: {{ $compPct }}%; background-color: {{ $barColor }};"></div>
                                            </div>
                                            <span class="small font-weight-bold text-muted">{{ $compPct }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No zones registered.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Zone Target Overview --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="font-weight-bold text-dark"><i class="fas fa-bullseye text-danger mr-2"></i>Zone Target Progress</span>
                    </div>
                    @if(isset($totalTargetCount) && $totalTargetCount > 0)
                        <span class="badge badge-success px-3 py-1 font-weight-bold">
                            {{ round(($totalAchievedCount / $totalTargetCount) * 100) }}% Achieved
                        </span>
                    @endif
                </div>
                <div class="card-body p-0 table-responsive">
                    @if($zoneTargets->count())
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Zone</th>
                                    <th class="text-right">Target</th>
                                    <th class="text-right">Reported</th>
                                    <th class="text-right">Pending</th>
                                    <th style="min-width: 140px;">Target Pace</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zoneTargets as $zt)
                                    @php
                                        $ach = $zt->achievedCount();
                                        $rem = $zt->remainingCount();
                                        $col = $zt->collectedCount();
                                        $pct = $zt->progressPercent();
                                        $barColor = $pct >= 80 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $zt->zone->name ?? 'Zone' }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($zt->target_count) }}</td>
                                        <td class="text-right text-success font-weight-bold">{{ number_format($ach) }}</td>
                                        <td class="text-right {{ $rem > 0 ? 'text-primary font-weight-bold' : 'text-muted' }}">{{ number_format($rem) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-fill mr-2" style="height: 8px; background-color: #f1f5f9;">
                                                    <div class="progress-bar" style="width: {{ min($pct, 100) }}%; background-color: {{ $barColor }};"></div>
                                                </div>
                                                <span class="small font-weight-bold text-muted">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bullseye fa-2x mb-2 text-muted opacity-50"></i>
                            <p class="mb-0 small">No active zone targets set for the current period.</p>
                            <a href="{{ route('admin.zone-targets') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-2">
                                <i class="fas fa-plus mr-1"></i> Set Zone Targets
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Samples --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span class="font-weight-bold text-dark"><i class="fas fa-history text-secondary mr-2"></i>Recently Registered Soil Samples</span>
                    <a href="{{ route('samples.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none">
                        View All Samples <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Sample Code</th>
                                <th>Farmer Name</th>
                                <th>Assigned Lab</th>
                                <th>Zone / District</th>
                                <th>Status</th>
                                <th class="text-right">Collection Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSamples as $sample)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold text-dark font-monospace" style="letter-spacing: 0.03em;">
                                            {{ $sample->sample_code }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center text-muted" style="width: 26px; height: 26px; background: #f1f5f9; font-size: 0.7rem;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="text-dark font-weight-medium">{{ $sample->farmer->name ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="fas fa-flask mr-1 text-info"></i> {{ $sample->lab->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="fas fa-map-marker-alt mr-1 text-danger"></i> {{ $sample->farmer->zone->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $val = $sample->status->value ?? 'collected';
                                            $badgeClass = match($val) {
                                                'reported' => 'badge-success',
                                                'testing' => 'badge-warning',
                                                'received' => 'badge-primary',
                                                default => 'badge-info',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-1">
                                            {{ $sample->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-right text-muted small">
                                        {{ $sample->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-vial fa-2x mb-2 text-muted opacity-50 d-block"></i>
                                        No recent samples registered yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
