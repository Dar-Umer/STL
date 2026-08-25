<div>
    @php $isFarmer = auth()->user()->hasRole('Farmer'); @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-flask text-success mr-2"></i> {{ $isFarmer ? 'My Samples' : 'All Samples' }}</h4>
            <p class="text-muted mb-0 small">{{ $totalCount }} total &middot; {{ $samples->total() }} filtered</p>
        </div>
        @if(!$isFarmer)
        <div>
            <button wire:click="exportExcel" wire:loading.attr="disabled" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel mr-1"></i>
                <span wire:loading.remove>Export Excel</span>
                <span wire:loading><i class="fas fa-spinner fa-spin mr-1"></i> Exporting...</span>
            </button>
        </div>
        @endif
    </div>

    @if(!$isFarmer)
    <div class="row mb-4">
        @foreach($stats as $stat)
        <div class="col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $stat['value'] === 'reported' ? '#28a745' : ($stat['value'] === 'received' ? '#17a2b8' : '#ffc107') }} !important;">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 small text-uppercase text-muted letter-spacing">{{ $stat['label'] }}</p>
                        <h3 class="mb-0 font-weight-bold">{{ $stat['count'] }}</h3>
                    </div>
                    <div class="text-{{ $stat['value'] === 'reported' ? 'success' : ($stat['value'] === 'received' ? 'info' : 'warning') }}">
                        <i class="fas fa-{{ $stat['value'] === 'reported' ? 'check-circle' : ($stat['value'] === 'received' ? 'inbox' : 'hourglass-half') }} fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body px-4 py-4">
            @if(!$isFarmer)
            <div class="row mb-3">
                <div class="col-md-3 mb-2 mb-md-0">
                    <input wire:model.live.debounce="search" type="text" class="form-control form-control-sm border-0 bg-light rounded-pill" placeholder="Search code, farmer, plot...">
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <select wire:model.live="statusFilter" class="form-control form-control-sm border-0 bg-light rounded-pill">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <input wire:model.live="dateFrom" type="date" class="form-control form-control-sm border-0 bg-light rounded-pill" placeholder="From" max="{{ $dateTo ?: '' }}">
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <input wire:model.live="dateTo" type="date" class="form-control form-control-sm border-0 bg-light rounded-pill" placeholder="To" min="{{ $dateFrom ?: '' }}">
                </div>
                <div class="col-md-3 mb-2 mb-md-0 text-md-right">
                    <button wire:click="$set('search',''); $set('statusFilter',''); $set('dateFrom',''); $set('dateTo','')" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fas fa-times mr-1"></i> Clear Filters
                    </button>
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted border-bottom">
                            <th class="border-top-0 font-weight-bold">Sample Code</th>
                            @if(!$isFarmer)<th class="border-top-0 font-weight-bold">Farmer</th>@endif
                            <th class="border-top-0 font-weight-bold">Plot / Crop</th>
                            <th class="border-top-0 font-weight-bold">Source</th>
                            <th class="border-top-0 font-weight-bold">Status</th>
                            @if(!$isFarmer)<th class="border-top-0 font-weight-bold">Lab</th>@endif
                            <th class="border-top-0 font-weight-bold">Collected</th>
                            @if($isFarmer)<th class="border-top-0 font-weight-bold">Report</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($samples as $sample)
                        <tr class="align-middle border-bottom">
                            <td class="py-3">
                                @if($isFarmer)
                                <a href="{{ route('farmer.samples.detail', $sample->id) }}" class="font-weight-bold text-success">{{ $sample->sample_code }}</a>
                                @else
                                <a href="{{ route('field.samples.detail', $sample->id) }}" class="font-weight-bold text-dark">{{ $sample->sample_code }}</a>
                                <br><small class="text-muted">#{{ $sample->id }}</small>
                                @endif
                            </td>
                            @if(!$isFarmer)
                            <td class="py-3">
                                <span class="font-weight-bold">{{ $sample->farmer->name ?? '—' }}</span>
                                @if($sample->farmer?->phone)
                                <br><small class="text-muted">{{ $sample->farmer->phone }}</small>
                                @endif
                            </td>
                            @endif
                            <td class="py-3">
                                <span>{{ $sample->plot->plot_name ?? '—' }}</span>
                                @if($sample->plot?->crop)
                                <br><small class="text-muted">{{ $sample->plot->crop->name }}</small>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($sample->source === 'self')
                                <span class="badge badge-info rounded-pill px-3 py-1"><i class="fas fa-hand-holding mr-1"></i>Self</span>
                                @else
                                <span class="badge badge-secondary rounded-pill px-3 py-1"><i class="fas fa-tractor mr-1"></i>Field</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="badge badge-{{ $sample->status->value === 'reported' ? 'success' : ($sample->status->value === 'received' ? 'info' : 'warning') }} rounded-pill px-3 py-1">
                                    {{ $sample->status->label() }}
                                </span>
                            </td>
                            @if(!$isFarmer)
                            <td class="py-3 small text-muted">{{ $sample->lab->name ?? '—' }}</td>
                            @endif
                            <td class="py-3 small text-muted">
                                {{ $sample->collected_at?->format('d M Y') ?? $sample->created_at->format('d M Y') }}
                            </td>
                            @if($isFarmer)
                            <td class="py-3">
                                @if($sample->status->value === 'reported')
                                <a href="{{ route('farmer.samples.report', $sample->id) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-download mr-1"></i> Report
                                </a>
                                @else
                                <span class="text-muted small">In progress</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-flask fa-4x text-muted mb-3 d-block" style="opacity:.3;"></i>
                                    <h5 class="text-muted font-weight-normal mb-1">No samples found</h5>
                                    <p class="text-muted small mb-0">
                                        @if($search || $statusFilter || $dateFrom || $dateTo)
                                            Try adjusting your filters.
                                        @else
                                            No samples have been created yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-file mr-1"></i> Page {{ $samples->currentPage() }} &middot;
                    {{ $samples->firstItem() ?? 0 }}–{{ $samples->lastItem() ?? 0 }} of {{ $samples->total() }}
                </small>
                <div>{{ $samples->links() }}</div>
            </div>
        </div>
    </div>

    <style>
        .letter-spacing { letter-spacing: .05em; }
        .opacity-50 { opacity: .5; }
        .align-middle td, .align-middle th { vertical-align: middle !important; }
        .table th { border-top: 0; }
    </style>
</div>
