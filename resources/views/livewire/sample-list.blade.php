<div>
    @php $isFarmer = auth()->user()->hasRole('Farmer'); @endphp

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div class="mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3 shadow-sm" 
                      style="width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff;">
                    <i class="fas fa-flask fa-lg"></i>
                </span>
                <div>
                    <h3 class="mb-0 font-weight-bold" style="letter-spacing: -0.02em; color: #0f172a;">
                        {{ $isFarmer ? 'My Soil Samples' : 'Soil Sample Directory' }}
                    </h3>
                    <p class="text-muted small mb-0">
                        {{ number_format($totalCount) }} total registered &bull; {{ number_format($samples->total()) }} matching active filters
                    </p>
                </div>
            </div>
        </div>
        @if(!$isFarmer)
        <div>
            <button wire:click="exportExcel" wire:loading.attr="disabled" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-medium">
                <i class="fas fa-file-excel mr-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="fas fa-spinner fa-spin mr-1" wire:loading wire:target="exportExcel"></i>
                <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                <span wire:loading wire:target="exportExcel">Generating...</span>
            </button>
        </div>
        @endif
    </div>

    {{-- Stat Cards (Admins / Staff) --}}
    @if(!$isFarmer && !empty($stats))
    <div class="row mb-4">
        @foreach($stats as $stat)
        @php
            $statColor = match($stat['value']) {
                'reported' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                'received' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                'testing' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                default => 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)',
            };
            $statIcon = match($stat['value']) {
                'reported' => 'fa-check-circle',
                'received' => 'fa-inbox',
                'testing' => 'fa-vial',
                default => 'fa-clipboard-list',
            };
        @endphp
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card card-hover border-0 shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-lg mr-3 shadow-sm text-white" 
                         style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: {{ $statColor }};">
                        <i class="fas {{ $statIcon }} fa-lg"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">{{ $stat['label'] }}</span>
                        <h4 class="mb-0 font-weight-bold text-dark" style="letter-spacing: -0.02em;">{{ number_format($stat['count']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Main Directory Card --}}
    <div class="card border-0 shadow-sm">
        {{-- Filters Bar --}}
        @if(!$isFarmer)
        <div class="card-header bg-white py-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 mb-2 mb-lg-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0 rounded-left" style="border-radius: 9999px 0 0 9999px;">
                                <i class="fas fa-search text-muted small"></i>
                            </span>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" 
                               class="form-control bg-light border-left-0" 
                               style="border-radius: 0 9999px 9999px 0;"
                               placeholder="Search sample code, farmer, phone...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2 mb-lg-0">
                    <select wire:model.live="statusFilter" class="form-control rounded-pill bg-light border-0">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2 mb-lg-0">
                    <input wire:model.live="dateFrom" type="date" class="form-control rounded-pill bg-light border-0" placeholder="From" max="{{ $dateTo ?: '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2 mb-lg-0">
                    <input wire:model.live="dateTo" type="date" class="form-control rounded-pill bg-light border-0" placeholder="To" min="{{ $dateFrom ?: '' }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6 text-right">
                    @if($search || $statusFilter || $dateFrom || $dateTo)
                    <button wire:click="$set('search',''); $set('statusFilter',''); $set('dateFrom',''); $set('dateTo','')" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-none">
                        <i class="fas fa-times mr-1"></i> Reset Filters
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Table --}}
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Sample Code</th>
                        @if(!$isFarmer)<th>Farmer</th>@endif
                        <th>Plot / Crop</th>
                        <th>Source</th>
                        <th>Status</th>
                        @if(!$isFarmer)<th>Laboratory</th>@endif
                        <th>Collection Date</th>
                        @if($isFarmer)<th class="text-right">Actions</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($samples as $sample)
                    <tr>
                        <td>
                            @if($isFarmer)
                            <a href="{{ route('farmer.samples.detail', $sample->id) }}" class="font-weight-bold text-success">
                                {{ $sample->sample_code }}
                            </a>
                            @else
                            <a href="{{ route('field.samples.detail', $sample->id) }}" class="font-weight-bold text-dark font-monospace">
                                {{ $sample->sample_code }}
                            </a>
                            <small class="text-muted d-block">ID #{{ $sample->id }}</small>
                            @endif
                        </td>
                        @if(!$isFarmer)
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center text-muted" style="width: 26px; height: 26px; background: #f1f5f9; font-size: 0.7rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold text-dark d-block">{{ $sample->farmer->name ?? '—' }}</span>
                                    @if($sample->farmer?->phone)
                                    <small class="text-muted"><i class="fas fa-phone-alt mr-1" style="font-size: 0.65rem;"></i>{{ $sample->farmer->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @endif
                        <td>
                            <span class="text-dark font-weight-medium">{{ $sample->plot->plot_name ?? '—' }}</span>
                            @if($sample->plot?->crop)
                            <small class="text-muted d-block">{{ $sample->plot->crop->name }}</small>
                            @endif
                        </td>
                        <td>
                            @if($sample->source === 'self')
                            <span class="badge badge-info px-3 py-1"><i class="fas fa-hand-holding mr-1"></i>Self</span>
                            @else
                            <span class="badge badge-secondary px-3 py-1"><i class="fas fa-tractor mr-1"></i>Field</span>
                            @endif
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
                        @if(!$isFarmer)
                        <td class="small text-muted">
                            @if($sample->lab)
                            <i class="fas fa-flask text-info mr-1"></i> {{ $sample->lab->name }}
                            @else
                            —
                            @endif
                        </td>
                        @endif
                        <td class="small text-muted">
                            {{ $sample->collected_at?->format('d M Y') ?? $sample->created_at->format('d M Y') }}
                        </td>
                        @if($isFarmer)
                        <td class="text-right">
                            @if($sample->status->value === 'reported')
                            <a href="{{ route('farmer.samples.report', $sample->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                <i class="fas fa-file-pdf mr-1"></i> Report
                            </a>
                            @else
                            <span class="badge badge-light border text-muted">Processing</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isFarmer ? 6 : 7 }}" class="text-center py-5 text-muted">
                            <i class="fas fa-vial fa-3x mb-3 text-muted opacity-50 d-block"></i>
                            <h6 class="font-weight-bold text-dark">No samples found</h6>
                            <p class="small text-muted mb-0">
                                @if($search || $statusFilter || $dateFrom || $dateTo)
                                    Try clearing your search criteria or date filters.
                                @else
                                    No soil samples have been registered yet.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($samples->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $samples->firstItem() ?? 0 }}–{{ $samples->lastItem() ?? 0 }} of {{ number_format($samples->total()) }} records
            </small>
            <div>{{ $samples->links() }}</div>
        </div>
        @endif
    </div>
</div>
