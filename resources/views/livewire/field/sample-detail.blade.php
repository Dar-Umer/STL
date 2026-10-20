<div class="space-y-6">
    {{-- Top Back Link --}}
    <div>
        <a href="{{ route('samples.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to All Soil Samples</span>
        </a>
    </div>

    {{-- Main Header Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-2xl font-bold shadow-md shadow-emerald-500/20 flex-shrink-0">
                    <i class="fas fa-flask"></i>
                </span>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight font-mono">{{ $sample->sample_code }}</h2>
                        @php
                            $st = $sample->status->value ?? 'collected';
                            $badge = match($st) {
                                'reported' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'received' => 'bg-blue-50 text-blue-700 border-blue-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                            $dot = match($st) {
                                'reported' => 'bg-emerald-500',
                                'received' => 'bg-blue-500',
                                default => 'bg-amber-500',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                            {{ $sample->status->label() }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                        <span>Collected on {{ $sample->collected_at?->format('d M Y, h:i A') ?? '—' }}</span>
                        @if($sample->lab)
                            <span>&middot;</span>
                            <span>{{ $sample->lab->name }}</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Actions Toolbar --}}
            <div class="flex flex-wrap items-center gap-2">
                @php $prefix = auth()->user()->hasRole('Farmer') ? 'farmer' : 'field'; @endphp
                <a href="{{ route("$prefix.samples.acknowledgement", $sample->id) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                    <i class="fas fa-receipt text-emerald-600 text-xs"></i>
                    <span>Acknowledgement</span>
                </a>
                <a href="{{ route("$prefix.samples.acknowledgement-pdf", $sample->id) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]"
                   title="Download Acknowledgement PDF">
                    <i class="fas fa-download text-gray-400 text-xs"></i>
                    <span>PDF</span>
                </a>
                @if($sample->status->value === 'reported')
                    <a href="{{ route("$prefix.samples.report", $sample->id) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <i class="fas fa-file-pdf text-xs"></i>
                        <span>Download Certified Soil Health Card</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- 2-Column Responsive Body --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left Column: Passport + Beneficiary + Plot --}}
        <div class="space-y-6">
            {{-- Card 1: Sample Passport --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-id-badge"></i>
                    </span>
                    <span>Sample Passport & Identifiers</span>
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-xs">
                    <div>
                        <dt class="text-gray-400 font-medium">Sample Code</dt>
                        <dd class="font-mono font-bold text-gray-900 text-sm mt-0.5">{{ $sample->sample_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 font-medium">Lifecycle Status</dt>
                        <dd class="font-semibold text-gray-900 mt-0.5">{{ $sample->status->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 font-medium">Collection Date</dt>
                        <dd class="text-gray-800 mt-0.5 font-mono">{{ $sample->collected_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 font-medium">Lab Intake Date</dt>
                        <dd class="text-gray-800 mt-0.5 font-mono">{{ $sample->received_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 font-medium">Analysis Completed</dt>
                        <dd class="text-gray-800 mt-0.5 font-mono">{{ $sample->analyzed_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 font-medium">Operating Laboratory</dt>
                        <dd class="font-semibold text-gray-900 mt-0.5">{{ $sample->lab->name ?? '—' }} ({{ $sample->lab->code ?? 'N/A' }})</dd>
                    </div>
                    @if($sample->qrCode || $sample->qr_code_path)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-400 font-medium">Container QR Barcode</dt>
                            <dd class="mt-1">
                                <code class="px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-mono text-gray-800 border border-gray-200">
                                    {{ $sample->qr_code_path ?? $sample->qrCode?->code }}
                                </code>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Card 2: Beneficiary Details --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-user"></i>
                    </span>
                    <span>Beneficiary Farmer Details</span>
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-xs">
                    <div>
                        <dt class="text-gray-400 font-medium">Full Name</dt>
                        <dd class="font-bold text-gray-900 text-sm mt-0.5">{{ $sample->farmer->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 font-medium">Phone Number</dt>
                        <dd class="mt-0.5 font-mono">
                            @if($sample->farmer?->phone)
                                <a href="tel:{{ $sample->farmer->phone }}" class="text-emerald-700 hover:underline font-semibold">
                                    {{ $sample->farmer->phone }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    @if($sample->farmer?->parentage)
                        <div>
                            <dt class="text-gray-400 font-medium">Parentage</dt>
                            <dd class="text-gray-800 mt-0.5">{{ $sample->farmer->parentage }}</dd>
                        </div>
                    @endif
                    @if($sample->farmer?->aadhar_no)
                        <div>
                            <dt class="text-gray-400 font-medium">Aadhaar Card No</dt>
                            <dd class="text-gray-800 mt-0.5 font-mono">{{ $sample->farmer->aadhar_no }}</dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-gray-400 font-medium">Location Hierarchy</dt>
                        <dd class="text-gray-700 mt-0.5">
                            {{ $sample->farmer?->village?->name ?? '—' }}
                            @if($sample->farmer?->panchayat) &middot; Panchayat: {{ $sample->farmer->panchayat->name }} @endif
                            @if($sample->farmer?->zone) &middot; Zone: {{ $sample->farmer->zone->name }} @endif
                            @if($sample->farmer?->district) &middot; {{ $sample->farmer->district->name }} / {{ $sample->farmer->state?->name ?? '' }} @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Card 3: Plot Details --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-map-marked-alt"></i>
                    </span>
                    <span>Field Plot Specifications</span>
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-xs">
                    <div>
                        <dt class="text-gray-400 font-medium">Plot Name</dt>
                        <dd class="font-bold text-gray-900 mt-0.5">{{ $sample->plot->plot_name ?? '—' }}</dd>
                    </div>
                    @if($sample->plot?->khasra_no)
                        <div>
                            <dt class="text-gray-400 font-medium">Khasra / Survey No</dt>
                            <dd class="text-gray-800 mt-0.5 font-mono">{{ $sample->plot->khasra_no }}</dd>
                        </div>
                    @endif
                    @if($sample->plot?->area_kanals)
                        <div>
                            <dt class="text-gray-400 font-medium">Area in Kanals</dt>
                            <dd class="text-gray-800 mt-0.5 font-mono">{{ number_format($sample->plot->area_kanals, 2) }} Kanals</dd>
                        </div>
                    @endif
                    @if($sample->plot?->soilType)
                        <div>
                            <dt class="text-gray-400 font-medium">Soil Classification</dt>
                            <dd class="font-semibold text-gray-900 mt-0.5">{{ $sample->plot->soilType->name }}</dd>
                        </div>
                    @endif
                    @if($sample->plot?->crop)
                        <div>
                            <dt class="text-gray-400 font-medium">Target Crop</dt>
                            <dd class="font-bold text-emerald-700 mt-0.5">{{ $sample->plot->crop->name }}</dd>
                        </div>
                    @endif
                    @if($sample->plot?->age_of_plants)
                        <div>
                            <dt class="text-gray-400 font-medium">Crop Age</dt>
                            <dd class="text-gray-800 mt-0.5 font-mono">{{ $sample->plot->age_of_plants }} Years</dd>
                        </div>
                    @endif
                    @if($sample->plot?->latitude && $sample->plot?->longitude)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-400 font-medium">GPS Field Coordinates</dt>
                            <dd class="text-gray-700 mt-0.5 font-mono flex items-center gap-1.5">
                                <i class="fas fa-location-dot text-rose-500"></i>
                                <span>{{ $sample->plot->latitude }}, {{ $sample->plot->longitude }}</span>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Right Column: Personnel + Timeline + Test Results + Recommendations --}}
        <div class="space-y-6">
            {{-- Card 4: Operating Personnel --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-users"></i>
                    </span>
                    <span>Assigned Personnel</span>
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                        <dt class="text-gray-400 font-medium text-[10px] uppercase">Field Collector</dt>
                        <dd class="font-bold text-gray-900 text-sm mt-0.5">{{ $sample->collector->name ?? 'Direct Lab Walk-in' }}</dd>
                    </div>
                    <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                        <dt class="text-gray-400 font-medium text-[10px] uppercase">Lab Testing Analyst</dt>
                        <dd class="font-bold text-gray-900 text-sm mt-0.5">{{ $sample->analyst->name ?? 'Pending Assignment' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Card 5: Sample Lifecycle Timeline --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-timeline"></i>
                    </span>
                    <span>Lifecycle Timeline</span>
                </h3>

                @php
                    $timeline = [
                        ['label' => 'Collected in Field', 'date' => $sample->collected_at, 'completed' => true],
                        ['label' => 'Received at Laboratory', 'date' => $sample->received_at, 'completed' => !is_null($sample->received_at)],
                        ['label' => 'Testing Completed & Reported', 'date' => $sample->analyzed_at, 'completed' => $sample->status->value === 'reported'],
                    ];
                @endphp

                <div class="relative pl-6 space-y-6">
                    <div class="absolute left-2.5 top-2 bottom-2 w-0.5 bg-gray-200"></div>
                    @foreach($timeline as $item)
                        <div class="relative flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] -ml-6 border-2 border-white {{ $item['completed'] ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                @if($item['completed']) <i class="fas fa-check"></i> @endif
                            </span>
                            <div>
                                <h4 class="text-xs font-bold {{ $item['completed'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $item['label'] }}</h4>
                                <span class="text-[11px] font-mono text-gray-500">
                                    {{ $item['date']?->format('d M Y, h:i A') ?? ($item['completed'] ? 'Recorded' : 'Pending') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Card 6: Certified Chemical Test Results Scorecard --}}
            @if($sample->results->count())
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-emerald-600"></i>
                            Chemical Test Scorecard
                        </h3>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            {{ $sample->results->count() }} Parameters
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-gray-50/80 border-b border-gray-100">
                                <tr>
                                    <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Parameter</th>
                                    <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Observed</th>
                                    <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Normal Range</th>
                                    <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                                    <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500 text-right">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($sample->results as $result)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <td class="px-5 py-3 font-bold text-gray-900">
                                            {{ $result->parameter->name ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 font-mono font-bold text-emerald-700">
                                            {{ $result->value ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 font-mono text-gray-500">
                                            {{ $result->parameter->range ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 font-mono text-gray-400">
                                            {{ $result->parameter->unit ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            @if($result->rating)
                                                @php $tier = strtolower($result->rating); @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs"
                                                      style="{{ $result->parameter->getRatingBadgeStyle($tier) }}">
                                                    {{ $result->parameter->getRatingLabel($tier) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Card 7: Recommendations Box --}}
            @if($sample->recommendations)
                <div class="bg-emerald-50/40 border border-emerald-200 rounded-2xl p-6 space-y-2">
                    <h3 class="text-sm font-bold text-emerald-900 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-amber-500"></i>
                        Agronomic & Fertilizer Recommendations
                    </h3>
                    <p class="text-xs text-gray-800 leading-relaxed whitespace-pre-line">{{ $sample->recommendations }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
