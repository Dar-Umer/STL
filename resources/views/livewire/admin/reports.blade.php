<div>
    <style>
        .report-stat-card { transition: transform .15s, box-shadow .15s; }
        .report-stat-card:hover { transform: translateY(-2px); box-shadow: 0 .4rem 1rem rgba(0,0,0,.1) !important; }
        .report-tab-link { border: none; border-radius: 2rem !important; padding: .45rem 1.1rem; font-size: .82rem; font-weight: 500; color: #6c757d; background: #f1f3f5; transition: all .2s; margin-right: .3rem; }
        .report-tab-link:hover { background: #dee2e6; color: #495057; }
        .report-tab-link.active { background: #007bff; color: #fff; box-shadow: 0 2px 6px rgba(0,123,255,.3); }
        .report-filter-section { border-left: 3px solid #007bff; }
        .monthly-bar { border-radius: 4px 4px 0 0; transition: height .4s ease, opacity .2s; position: relative; }
        .monthly-bar:hover { opacity: .85; }
        .monthly-bar::after { content: attr(data-count); position: absolute; top: -22px; left: 50%; transform: translateX(-50%); font-size: .7rem; font-weight: 700; color: #495057; opacity: 0; transition: opacity .2s; }
        .monthly-bar:hover::after { opacity: 1; }
        .status-row { padding: .65rem 0; border-bottom: 1px solid #f1f3f5; }
        .status-row:last-child { border-bottom: none; }
        .section-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: #868e96; font-weight: 600; }
        .report-table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #868e96; font-weight: 600; border-top: none; }
        .report-table td { vertical-align: middle; }
        .export-btn { transition: transform .12s; }
        .export-btn:hover { transform: scale(1.05); }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-chart-bar text-primary mr-2"></i>Reports</h4>
            <small class="text-muted">Analytics and export across all modules</small>
        </div>
        <div class="d-flex align-items-center">
            <small class="text-muted mr-3">{{ now()->format('d/m/Y h:i A') }}</small>
            <button wire:click="loadAllData" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm rounded-lg mb-4 report-filter-section">
        <div class="card-body py-3">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="section-label mb-1">From</label>
                    <input type="date" wire:model.live="dateFrom" class="form-control form-control-sm rounded-pill" max="{{ $dateTo ?: '' }}">
                </div>
                <div class="col-md-3">
                    <label class="section-label mb-1">To</label>
                    <input type="date" wire:model.live="dateTo" class="form-control form-control-sm rounded-pill" min="{{ $dateFrom ?: '' }}">
                </div>
                @if($isAdmin)
                <div class="col-md-3">
                    <label class="section-label mb-1">Lab</label>
                    <select wire:model.live="labFilter" class="form-control form-control-sm rounded-pill">
                        <option value="">All Labs</option>
                        @foreach($labs as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-{{ $isAdmin ? 3 : 6 }}">
                    <button wire:click="loadAllData" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm w-100">
                        <i class="fas fa-filter mr-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mb-4">
        <div class="d-flex flex-wrap">
            <button class="report-tab-link {{ $activeTab == 'summary' ? 'active' : '' }}" wire:click="$set('activeTab', 'summary')">
                <i class="fas fa-th-large mr-1"></i> Summary
            </button>
            <button class="report-tab-link {{ $activeTab == 'lab' ? 'active' : '' }}" wire:click="$set('activeTab', 'lab')">
                <i class="fas fa-microscope mr-1"></i> By Lab
            </button>
            <button class="report-tab-link {{ $activeTab == 'zone' ? 'active' : '' }}" wire:click="$set('activeTab', 'zone')">
                <i class="fas fa-map-marked-alt mr-1"></i> By Zone
            </button>
            <button class="report-tab-link {{ $activeTab == 'field-official' ? 'active' : '' }}" wire:click="$set('activeTab', 'field-official')">
                <i class="fas fa-hard-hat mr-1"></i> Field Officials
            </button>
            <button class="report-tab-link {{ $activeTab == 'technician' ? 'active' : '' }}" wire:click="$set('activeTab', 'technician')">
                <i class="fas fa-flask mr-1"></i> Technicians
            </button>
            <button class="report-tab-link {{ $activeTab == 'self-sample' ? 'active' : '' }}" wire:click="$set('activeTab', 'self-sample')">
                <i class="fas fa-user-plus mr-1"></i> Self Samples
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════ SUMMARY TAB ═══════════════════════════ --}}
    @if($activeTab == 'summary')
    @php
        $total = $summaryData['total'] ?? 0;
        $reported = $summaryData['reported'] ?? 0;
        $collected = $summaryData['collected'] ?? 0;
        $received = $summaryData['received'] ?? 0;
        $testing = $summaryData['testing'] ?? 0;
        $queue = $summaryData['queue'] ?? 0;
        $field = $summaryData['field'] ?? 0;
        $self = $summaryData['self'] ?? 0;
        $reportRate = $total > 0 ? round(($reported / $total) * 100) : 0;
    @endphp

    {{-- Key Metrics --}}
    <div class="row mb-4">
        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(0,123,255,.1);">
                        <i class="fas fa-vial text-primary"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Total</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($total) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(23,162,184,.1);">
                        <i class="fas fa-clipboard-list text-info"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Collected</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($collected) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(0,123,255,.1);">
                        <i class="fas fa-download text-primary"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Received</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($received) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(255,193,7,.1);">
                        <i class="fas fa-flask text-warning"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Testing</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($testing) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(40,167,69,.1);">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Reported</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($reported) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(220,53,69,.1);">
                        <i class="fas fa-clock text-danger"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">In Queue</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($queue) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pipeline + Report Rate --}}
    <div class="row mb-4">
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-stream text-primary mr-2"></i>Pipeline Overview</h6>
                </div>
                <div class="card-body py-2">
                    @php $pipelineMax = max($total, 1); @endphp
                    @php $pipelineItems = [
                        ['label' => 'Collected', 'count' => $collected, 'color' => '#17a2b8', 'bg' => 'rgba(23,162,184,.15)'],
                        ['label' => 'Received', 'count' => $received, 'color' => '#007bff', 'bg' => 'rgba(0,123,255,.15)'],
                        ['label' => 'Testing', 'count' => $testing, 'color' => '#ffc107', 'bg' => 'rgba(255,193,7,.15)'],
                        ['label' => 'Reported', 'count' => $reported, 'color' => '#28a745', 'bg' => 'rgba(40,167,69,.15)'],
                    ]; @endphp
                    @foreach($pipelineItems as $pi)
                    <div class="d-flex align-items-center mb-2">
                        <div style="width:80px" class="small font-weight-bold text-muted">{{ $pi['label'] }}</div>
                        <div class="flex-fill mx-2">
                            <div class="progress" style="height:20px;background:{{ $pi['bg'] }}">
                                <div class="progress-bar" style="width:{{ $pipelineMax > 0 ? round(($pi['count'] / $pipelineMax) * 100) : 0 }}%;background:{{ $pi['color'] }};border-radius:10px"></div>
                            </div>
                        </div>
                        <div style="width:50px" class="text-right font-weight-bold">{{ number_format($pi['count']) }}</div>
                    </div>
                    @endforeach
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted"><i class="fas fa-leaf mr-1 text-secondary"></i>Field: <strong>{{ number_format($field) }}</strong></span>
                        <span class="text-muted"><i class="fas fa-user-plus mr-1 text-info"></i>Self: <strong>{{ number_format($self) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-bullseye text-success mr-2"></i>Report Completion</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="position-relative mb-3" style="width:120px;height:120px;">
                        <svg viewBox="0 0 36 36" style="transform:rotate(-90deg)">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e9ecef" stroke-width="3"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="{{ $reportRate >= 80 ? '#28a745' : ($reportRate >= 50 ? '#ffc107' : '#dc3545') }}" stroke-width="3" stroke-dasharray="{{ $reportRate }}, 100" stroke-linecap="round" style="transition: stroke-dasharray .8s ease"/>
                        </svg>
                        <div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);text-align:center">
                            <h3 class="mb-0 font-weight-bold">{{ $reportRate }}%</h3>
                            <small class="text-muted">of {{ number_format($total) }}</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 text-center">{{ number_format($reported) }} samples reported out of {{ number_format($total) }} total</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-chart-bar text-warning mr-2"></i>Monthly Trend</h6>
                </div>
                <div class="card-body">
                    @php $maxMonthly = max(array_merge($monthlyCounts, [1])); @endphp
                    <div class="d-flex align-items-end justify-content-between" style="height:140px;gap:6px;">
                        @foreach($monthlyCounts as $i => $count)
                            @php $height = max(($count / $maxMonthly) * 110, 3); @endphp
                            <div class="d-flex flex-column align-items-center flex-fill">
                                <div class="monthly-bar w-100" data-count="{{ $count }}" style="height:{{ $height }}px;background:linear-gradient(180deg, #007bff, #6610f2)"></div>
                                <small class="mt-1 text-muted" style="font-size:9px">{{ \Carbon\Carbon::parse($monthlyLabels[$i])->format('M') }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lab Summary + Zone Summary --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-microscope text-info mr-2"></i>Lab Summary</h6>
                    <button class="report-tab-link {{ $activeTab == 'lab' ? 'active' : '' }}" wire:click="$set('activeTab', 'lab')" style="font-size:.72rem;padding:.3rem .8rem">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 report-table">
                        <thead class="thead-light"><tr><th>Lab</th><th class="text-right">Samples</th><th class="text-right">Reported</th><th style="min-width:100px">Rate</th></tr></thead>
                        <tbody>
                            @forelse($labReportData as $lab)
                                @php $r = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $lab->name }}</td>
                                    <td class="text-right">{{ number_format($lab->soil_samples_count) }}</td>
                                    <td class="text-right">{{ number_format($lab->reported_count) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill" style="height:16px;border-radius:8px">
                                                <div class="progress-bar bg-{{ $r >= 80 ? 'success' : ($r >= 50 ? 'warning' : 'danger') }}" style="width:{{ $r }}%;border-radius:8px">{{ $r }}%</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fas fa-inbox d-block mb-1" style="font-size:1.5rem;opacity:.3"></i>No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-map-marked-alt text-danger mr-2"></i>Zone Summary</h6>
                    <button class="report-tab-link {{ $activeTab == 'zone' ? 'active' : '' }}" wire:click="$set('activeTab', 'zone')" style="font-size:.72rem;padding:.3rem .8rem">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 report-table">
                        <thead class="thead-light"><tr><th>Zone</th><th class="text-right">Samples</th><th class="text-right">Reported</th><th style="min-width:100px">Rate</th></tr></thead>
                        <tbody>
                            @forelse($zoneReportData as $zone)
                                @php $r = $zone->samples_count > 0 ? round(($zone->reported_count / $zone->samples_count) * 100) : 0; @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $zone->name }}</td>
                                    <td class="text-right">{{ number_format($zone->samples_count) }}</td>
                                    <td class="text-right">{{ number_format($zone->reported_count) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-fill" style="height:16px;border-radius:8px">
                                                <div class="progress-bar bg-{{ $r >= 80 ? 'success' : ($r >= 50 ? 'warning' : 'danger') }}" style="width:{{ $r }}%;border-radius:8px">{{ $r }}%</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fas fa-inbox d-block mb-1" style="font-size:1.5rem;opacity:.3"></i>No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY LAB TAB ═══════════════════════════ --}}
    @if($activeTab == 'lab')
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-microscope text-info mr-2"></i>Lab-wise Report</h6>
            <div>
                <button wire:click="exportLabExcel" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm export-btn mr-1"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                <button wire:click="exportLabPdf" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm export-btn"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Lab</th>
                            <th>Code</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Field</th>
                            <th class="text-right">Self</th>
                            <th class="text-right">Collected</th>
                            <th class="text-right">Received</th>
                            <th class="text-right">Testing</th>
                            <th class="text-right">Reported</th>
                            <th class="text-right">Farmers</th>
                            <th class="text-right">Users</th>
                            <th style="min-width:120px">Report Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labReportData as $i => $lab)
                            @php $r = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; @endphp
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold">{{ $lab->name }}</td>
                                <td><span class="badge badge-light rounded-pill">{{ $lab->code }}</span></td>
                                <td class="text-right font-weight-bold">{{ number_format($lab->soil_samples_count) }}</td>
                                <td class="text-right">{{ number_format($lab->soil_samples_count - ($lab->self_samples_count ?? 0)) }}</td>
                                <td class="text-right">{{ number_format($lab->self_samples_count ?? 0) }}</td>
                                <td class="text-right">{{ number_format($lab->collected_count) }}</td>
                                <td class="text-right">{{ number_format($lab->received_count) }}</td>
                                <td class="text-right">{{ number_format($lab->testing_count) }}</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($lab->reported_count) }}</td>
                                <td class="text-right">{{ number_format($lab->farmers_count) }}</td>
                                <td class="text-right">{{ number_format($lab->users_count) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-fill" style="height:18px;border-radius:9px">
                                            <div class="progress-bar bg-{{ $r >= 80 ? 'success' : ($r >= 50 ? 'warning' : 'danger') }}" style="width:{{ $r }}%;border-radius:9px">{{ $r }}%</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="13" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i><br>No labs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY ZONE TAB ═══════════════════════════ --}}
    @if($activeTab == 'zone')
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-map-marked-alt text-danger mr-2"></i>Zone-wise Report</h6>
            <div>
                <button wire:click="exportZoneExcel" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm export-btn mr-1"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                <button wire:click="exportZonePdf" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm export-btn"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Zone</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Field</th>
                            <th class="text-right">Self</th>
                            <th class="text-right">Collected</th>
                            <th class="text-right">Received</th>
                            <th class="text-right">Testing</th>
                            <th class="text-right">Reported</th>
                            <th class="text-right">Officials</th>
                            <th style="min-width:120px">Completion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zoneReportData as $i => $zone)
                            @php $r = $zone->samples_count > 0 ? round(($zone->reported_count / $zone->samples_count) * 100) : 0; @endphp
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold">{{ $zone->name }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($zone->samples_count) }}</td>
                                <td class="text-right">{{ number_format($zone->samples_count - ($zone->self_samples_count ?? 0)) }}</td>
                                <td class="text-right">{{ number_format($zone->self_samples_count ?? 0) }}</td>
                                <td class="text-right">{{ number_format($zone->collected_count) }}</td>
                                <td class="text-right">{{ number_format($zone->received_count) }}</td>
                                <td class="text-right">{{ number_format($zone->testing_count) }}</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($zone->reported_count) }}</td>
                                <td class="text-right">{{ number_format($zone->field_officials_count) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-fill" style="height:18px;border-radius:9px">
                                            <div class="progress-bar bg-{{ $r >= 80 ? 'success' : ($r >= 50 ? 'warning' : 'danger') }}" style="width:{{ $r }}%;border-radius:9px">{{ $r }}%</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i><br>No zone data found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY FIELD OFFICIAL TAB ═══════════════════════════ --}}
    @if($activeTab == 'field-official')
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-hard-hat text-primary mr-2"></i>Field Official Report</h6>
            <div>
                <button wire:click="exportFieldOfficialExcel" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm export-btn mr-1"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                <button wire:click="exportFieldOfficialPdf" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm export-btn"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Zone</th>
                            <th class="text-right">Total Collected</th>
                            <th class="text-right">Collected</th>
                            <th class="text-right">Received</th>
                            <th class="text-right">Testing</th>
                            <th class="text-right">Reported</th>
                            <th>Last Collection</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fieldOfficialReportData as $i => $official)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold">{{ $official->name }}</td>
                                <td class="text-muted small">{{ $official->email }}</td>
                                <td><span class="badge badge-light rounded-pill">{{ $official->zone->name ?? '—' }}</span></td>
                                <td class="text-right font-weight-bold">{{ number_format($official->total_collected) }}</td>
                                <td class="text-right">{{ number_format($official->collected_count) }}</td>
                                <td class="text-right">{{ number_format($official->received_count) }}</td>
                                <td class="text-right">{{ number_format($official->testing_count) }}</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($official->reported_count) }}</td>
                                <td class="text-muted small">{{ $official->last_collection ? \Carbon\Carbon::parse($official->last_collection)->format('d/m/Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i><br>No field official data found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY TECHNICIAN TAB ═══════════════════════════ --}}
    @if($activeTab == 'technician')
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-flask text-warning mr-2"></i>Technician Report</h6>
            <div>
                <button wire:click="exportTechnicianExcel" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm export-btn mr-1"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                <button wire:click="exportTechnicianPdf" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm export-btn"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Lab</th>
                            <th class="text-right">Total Analyzed</th>
                            <th class="text-right">Received</th>
                            <th class="text-right">Testing</th>
                            <th class="text-right">Reported</th>
                            <th>Last Analysis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($technicianReportData as $i => $tech)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold">{{ $tech->name }}</td>
                                <td class="text-muted small">{{ $tech->email }}</td>
                                <td><span class="badge badge-light rounded-pill">{{ $tech->lab->name ?? '—' }}</span></td>
                                <td class="text-right font-weight-bold">{{ number_format($tech->total_analyzed) }}</td>
                                <td class="text-right">{{ number_format($tech->received_count) }}</td>
                                <td class="text-right">{{ number_format($tech->testing_count) }}</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($tech->reported_count) }}</td>
                                <td class="text-muted small">{{ $tech->last_analysis ? \Carbon\Carbon::parse($tech->last_analysis)->format('d/m/Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i><br>No technician data found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ SELF SAMPLE TAB ═══════════════════════════ --}}
    @if($activeTab == 'self-sample')
    @php
        $selfTotal = $selfSampleSummary['total'] ?? 0;
        $selfReceived = $selfSampleSummary['received'] ?? 0;
        $selfTesting = $selfSampleSummary['testing'] ?? 0;
        $selfReported = $selfSampleSummary['reported'] ?? 0;
        $selfRate = $selfTotal > 0 ? round(($selfReported / $selfTotal) * 100) : 0;
    @endphp

    {{-- Self Sample KPIs --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(23,162,184,.1);">
                        <i class="fas fa-user-plus text-info"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Total Self-Samples</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($selfTotal) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(0,123,255,.1);">
                        <i class="fas fa-download text-primary"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Received</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($selfReceived) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(255,193,7,.1);">
                        <i class="fas fa-flask text-warning"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Testing</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($selfTesting) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-lg report-stat-card">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:44px;height:44px;background:rgba(40,167,69,.1);">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <p class="section-label mb-0">Reported</p>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($selfReported) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Self Sample by Lab --}}
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-microscope text-info mr-2"></i>Self-Samples by Lab</h6>
            <div>
                <button wire:click="exportSelfSampleExcel" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm export-btn mr-1"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                <button wire:click="exportSelfSamplePdf" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm export-btn"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Lab</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Received</th>
                            <th class="text-right">Testing</th>
                            <th class="text-right">Reported</th>
                            <th style="min-width:120px">Report Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selfSampleLabData as $i => $lab)
                            @php $r = $lab->self_samples_count > 0 ? round(($lab->self_reported_count / $lab->self_samples_count) * 100) : 0; @endphp
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold">{{ $lab->name }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($lab->self_samples_count) }}</td>
                                <td class="text-right">{{ number_format($lab->self_received_count) }}</td>
                                <td class="text-right">{{ number_format($lab->self_testing_count) }}</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($lab->self_reported_count) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-fill" style="height:18px;border-radius:9px">
                                            <div class="progress-bar bg-{{ $r >= 80 ? 'success' : ($r >= 50 ? 'warning' : 'danger') }}" style="width:{{ $r }}%;border-radius:9px">{{ $r }}%</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i><br>No self-sample data found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Self Sample Collectors --}}
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-user-check text-secondary mr-2"></i>Collected By (Lab Staff)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Lab</th>
                            <th class="text-right">Self-Samples</th>
                            <th>Last Collection</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selfSampleCollectors as $i => $collector)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold">{{ $collector->name }}</td>
                                <td class="text-muted small">{{ $collector->email }}</td>
                                <td><span class="badge badge-light rounded-pill">{{ $collector->lab->name ?? '—' }}</span></td>
                                <td class="text-right font-weight-bold">{{ number_format($collector->self_samples_count) }}</td>
                                <td class="text-muted small">{{ $collector->last_collection ? \Carbon\Carbon::parse($collector->last_collection)->format('d/m/Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i><br>No collectors found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
