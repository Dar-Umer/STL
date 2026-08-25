<div>
    <div class="row mb-3">
        <div class="col">
            <a href="{{ route('samples.index') }}" class="text-muted">
                <i class="fas fa-arrow-left mr-1"></i> Back to Samples
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width:56px;height:56px;font-size:1.3rem">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold mb-1">{{ $sample->sample_code }}</h4>
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="badge badge-{{ $sample->status->value === 'reported' ? 'success' : 'info' }} rounded-pill px-3 py-1 mr-2">
                                {{ $sample->status->label() }}
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i> Collected {{ $sample->collected_at?->format('d M Y, h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="mt-2 mt-md-0 d-flex align-items-center flex-wrap">
                    @php $prefix = auth()->user()->hasRole('Farmer') ? 'farmer' : 'field'; @endphp
                    <a href="{{ route("$prefix.samples.acknowledgement", $sample->id) }}" target="_blank" class="btn btn-outline-success rounded-pill btn-sm mr-2">
                        <i class="fas fa-receipt mr-1"></i> Acknowledgement
                    </a>
                    <div class="btn-group btn-group-sm mr-2">
                        <a href="{{ route("$prefix.samples.acknowledgement-pdf", $sample->id) }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-download mr-1"></i> PDF
                        </a>
                        <a href="{{ route("$prefix.samples.acknowledgement", $sample->id) }}" target="_blank" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-print"></i>
                        </a>
                    </div>
                    @if($sample->status->value === 'reported')
                    <a href="{{ route("$prefix.samples.report", $sample->id) }}" class="btn btn-success rounded-pill btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> Download Report
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-info-circle mr-2"></i>Sample Information</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" style="width:140px">Sample Code</td>
                            <td class="font-weight-medium">{{ $sample->sample_code }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Status</td>
                            <td><span class="badge badge-{{ $sample->status->value === 'reported' ? 'success' : 'info' }} rounded-pill px-2 py-1">{{ $sample->status->label() }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Collection Date</td>
                            <td>{{ $sample->collected_at?->format('d M Y, h:i A') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Received Date</td>
                            <td>{{ $sample->received_at?->format('d M Y, h:i A') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Analyzed Date</td>
                            <td>{{ $sample->analyzed_at?->format('d M Y, h:i A') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Laboratory</td>
                            <td>{{ $sample->lab->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Lab Code</td>
                            <td><code>{{ $sample->lab->code ?? '—' }}</code></td>
                        </tr>
                        @if($sample->qrCode)
                        <tr>
                            <td class="text-muted small">QR Code</td>
                            <td><code>{{ $sample->qrCode->code }}</code></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-user mr-2"></i>Farmer Information</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" style="width:140px">Name</td>
                            <td class="font-weight-medium">{{ $sample->farmer->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Phone</td>
                            <td><a href="tel:{{ $sample->farmer->phone ?? '' }}">{{ $sample->farmer->phone ?? '—' }}</a></td>
                        </tr>
                        @if($sample->farmer?->parentage)
                        <tr>
                            <td class="text-muted small">Parentage</td>
                            <td>{{ $sample->farmer->parentage }}</td>
                        </tr>
                        @endif
                        @if($sample->farmer?->aadhar_no)
                        <tr>
                            <td class="text-muted small">Aadhar No</td>
                            <td>{{ $sample->farmer->aadhar_no }}</td>
                        </tr>
                        @endif
                        @if($sample->farmer?->village || $sample->farmer?->panchayat || $sample->farmer?->zone)
                        <tr>
                            <td class="text-muted small">State</td>
                            <td>{{ $sample->farmer?->state?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">District</td>
                            <td>{{ $sample->farmer?->district?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Zone</td>
                            <td>{{ $sample->farmer?->zone?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Panchayat</td>
                            <td>{{ $sample->farmer?->panchayat?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Village</td>
                            <td>{{ $sample->farmer?->village?->name ?? '—' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-map-marked-alt mr-2"></i>Plot Information</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" style="width:140px">Plot Name</td>
                            <td class="font-weight-medium">{{ $sample->plot->plot_name ?? '—' }}</td>
                        </tr>
                        @if($sample->plot?->khasra_no)
                        <tr>
                            <td class="text-muted small">Khasra No</td>
                            <td>{{ $sample->plot->khasra_no }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->area_kanals)
                        <tr>
                            <td class="text-muted small">Area (Kanals)</td>
                            <td>{{ number_format($sample->plot->area_kanals, 2) }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->soilType)
                        <tr>
                            <td class="text-muted small">Soil Type</td>
                            <td>{{ $sample->plot->soilType->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->crop)
                        <tr>
                            <td class="text-muted small">Crop</td>
                            <td>{{ $sample->plot->crop->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->age_of_plants)
                        <tr>
                            <td class="text-muted small">Crop Age</td>
                            <td>{{ $sample->plot->age_of_plants }} years</td>
                        </tr>
                        @endif
                        @if($sample->plot?->variety)
                        <tr>
                            <td class="text-muted small">Variety</td>
                            <td>{{ $sample->plot->variety }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->state?->name)
                        <tr>
                            <td class="text-muted small">State</td>
                            <td>{{ $sample->plot->state->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->district?->name)
                        <tr>
                            <td class="text-muted small">District</td>
                            <td>{{ $sample->plot->district->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->zone?->name)
                        <tr>
                            <td class="text-muted small">Zone</td>
                            <td>{{ $sample->plot->zone->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->panchayat?->name)
                        <tr>
                            <td class="text-muted small">Panchayat</td>
                            <td>{{ $sample->plot->panchayat->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->village?->name)
                        <tr>
                            <td class="text-muted small">Village</td>
                            <td>{{ $sample->plot->village->name }}</td>
                        </tr>
                        @endif
                        @if($sample->plot?->latitude && $sample->plot?->longitude)
                        <tr>
                            <td class="text-muted small">Coordinates</td>
                            <td>{{ $sample->plot->latitude }}, {{ $sample->plot->longitude }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-users mr-2"></i>Personnel</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" style="width:140px">Collected By</td>
                            <td class="font-weight-medium">{{ $sample->collector->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Analyst</td>
                            <td>{{ $sample->analyst->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Lab</td>
                            <td>{{ $sample->lab->name ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-clock mr-2"></i>Sample Timeline</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    @php
                        $timeline = [
                            ['label' => 'Collected', 'date' => $sample->collected_at, 'icon' => 'fa-hand-holding', 'completed' => true],
                            ['label' => 'Received', 'date' => $sample->received_at, 'icon' => 'fa-check-circle', 'completed' => !is_null($sample->received_at)],
                            ['label' => 'Reported', 'date' => $sample->reported_at ?? null, 'icon' => 'fa-file-alt', 'completed' => $sample->status->value === 'reported'],
                        ];
                    @endphp
                    <div class="position-relative" style="padding-left:32px">
                        <div class="position-absolute" style="left:14px;top:8px;bottom:8px;width:2px;background:#e9ecef"></div>
                        @foreach($timeline as $item)
                        <div class="d-flex align-items-start mb-3 position-relative">
                            <div class="rounded-circle d-flex align-items-center justify-content-center position-absolute shadow-sm"
                                style="width:30px;height:30px;left:-22px;font-size:.7rem;
                                {{ $item['completed'] ? 'background:#28a745;color:#fff' : 'background:#f8f9fa;color:#adb5bd' }}">
                                <i class="fas {{ $item['icon'] }}"></i>
                            </div>
                            <div class="ml-2">
                                <strong class="d-block {{ $item['completed'] ? 'text-dark' : 'text-muted' }}" style="font-size:.9rem">{{ $item['label'] }}</strong>
                                <small class="{{ $item['completed'] ? 'text-muted' : 'text-muted' }}" style="font-size:.78rem">
                                    {{ $item['date']?->format('d M Y, h:i A') ?? ($item['completed'] ? '' : 'Pending') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($sample->results->count())
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-chart-bar mr-2"></i>Test Results</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Parameter</th>
                                    <th>Value</th>
                                    <th>Range</th>
                                    <th>Unit</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sample->results as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->parameter->name ?? '—' }}</td>
                                    <td class="font-weight-medium">{{ $result->value ?? '—' }}</td>
                                    <td><small class="text-muted">{{ $result->parameter->range ?? '—' }}</small></td>
                                    <td>{{ $result->parameter->unit ?? '—' }}</td>
                                    <td>
                                        @if($result->rating)
                                        @php $tier = strtolower($result->rating); @endphp
                                        <span style="{{ $result->parameter->getRatingBadgeStyle($tier) }}">
                                            {{ $result->parameter->getRatingLabel($tier) }}
                                        </span>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($sample->recommendations)
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="font-weight-bold text-success mb-0"><i class="fas fa-lightbulb mr-2"></i>Recommendations</h6>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <p class="mb-0 text-justify" style="line-height:1.7">{{ $sample->recommendations }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
