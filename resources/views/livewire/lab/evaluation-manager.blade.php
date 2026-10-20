<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>Evaluation &amp; Bench Records</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Laboratory intake sequences, bench testing worksheets, and chemical results tracking.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mr-1">
                Next Sequence: <strong class="ml-1 font-mono text-emerald-900 font-bold">{{ $stats['nextLabNumber'] }}</strong>
            </span>

            <button wire:click="exportAllExcel"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-xs rounded-xl border border-gray-200 shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-file-excel text-emerald-600"></i>
                <span>Export All (Excel)</span>
            </button>

            @if(count($selected) > 0)
                <button wire:click="printSelectedWorksheet"
                        class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98] animate-pulse">
                    <i class="fas fa-file-pdf"></i>
                    <span>Print Worksheet ({{ count($selected) }})</span>
                </button>
                <button wire:click="exportSelectedExcel"
                        class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium text-xs rounded-xl border border-emerald-200 shadow-xs transition">
                    <i class="fas fa-download"></i>
                    <span>Export Selected</span>
                </button>
            @endif

            <a href="{{ route('lab.queue') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <i class="fas fa-inbox"></i>
                <span>Receive Samples (Intake)</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 flex items-center justify-between text-emerald-800 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    @if(session('warning'))
        <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-4 flex items-center justify-between text-amber-800 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                <span class="text-sm font-semibold">{{ session('warning') }}</span>
            </div>
            <button type="button" @click="$el.closest('div').remove()" class="text-amber-500 hover:text-amber-700 p-1 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Backfill Alert if any legacy unassigned samples --}}
    @if($stats['unassignedCount'] > 0)
        <div class="rounded-2xl bg-amber-50/60 border border-amber-200 p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fas fa-tag"></i>
                </span>
                <div>
                    <h5 class="text-xs font-bold text-amber-900">Legacy Samples Detected</h5>
                    <p class="text-xs text-amber-800">There are {{ $stats['unassignedCount'] }} sample(s) awaiting sequence number assignment.</p>
                </div>
            </div>
            <button wire:click="backfillMissingCodes"
                    class="px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-xs transition">
                Assign Numbers
            </button>
        </div>
    @endif

    {{-- 4 KPI Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Evaluated --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Evaluated Samples</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-vial"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($stats['totalEvaluated']) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Tracked
                </span>
            </div>
        </div>

        {{-- Awaiting Testing --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Awaiting Testing</span>
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-hourglass-half"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-amber-600 tracking-tight">{{ number_format($stats['pendingTesting']) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                    In Queue
                </span>
            </div>
        </div>

        {{-- Tested & Reported --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tested &amp; Reported</span>
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-double"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-blue-600 tracking-tight">{{ number_format($stats['reported']) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                    Complete
                </span>
            </div>
        </div>

        {{-- Evaluation Batches --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Evaluation Batches</span>
                <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-layer-group"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-purple-600 tracking-tight">{{ number_format($stats['totalBatches']) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">
                    Runs
                </span>
            </div>
        </div>
    </div>

    {{-- Search & Controls Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 space-y-3">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            {{-- Tab Switcher --}}
            <div class="inline-flex p-1 rounded-xl bg-gray-100 border border-gray-200 text-xs font-semibold">
                <button wire:click="setTab('samples')"
                        class="px-3.5 py-1.5 rounded-lg transition {{ $activeTab === 'samples' ? 'bg-white text-gray-900 shadow-xs font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    <i class="fas fa-list-ol mr-1.5 text-emerald-600"></i>
                    <span>All Evaluated Samples ({{ $stats['totalEvaluated'] }})</span>
                </button>
                <button wire:click="setTab('batches')"
                        class="px-3.5 py-1.5 rounded-lg transition {{ $activeTab === 'batches' ? 'bg-white text-gray-900 shadow-xs font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    <i class="fas fa-layer-group mr-1.5 text-purple-600"></i>
                    <span>Evaluation Batches ({{ $stats['totalBatches'] }})</span>
                </button>
            </div>

            {{-- Batch Filter Badge --}}
            @if($batchFilter)
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 font-medium">Active Batch:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-mono font-bold border border-emerald-200">
                        {{ $batches->firstWhere('id', $batchFilter)?->batch_code ?? 'Batch #' . $batchFilter }}
                        <button wire:click="$set('batchFilter', '')" class="text-emerald-600 hover:text-emerald-900 ml-1">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </span>
                </div>
            @endif
        </div>

        {{-- Filters Strip --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-2 border-t border-gray-100">
            {{-- Search Input --}}
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search Lab Code (e.g. 1001), Sample No, Farmer..."
                       class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
            </div>

            {{-- Status Filter --}}
            <div>
                <select wire:model.live="statusFilter"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-700 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    <option value="">All Testing Statuses</option>
                    <option value="received">Awaiting Testing (Received)</option>
                    <option value="testing">In Testing</option>
                    <option value="reported">Tested &amp; Reported</option>
                </select>
            </div>

            {{-- Batch Filter --}}
            <div>
                <select wire:model.live="batchFilter"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-700 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    <option value="">All Batches</option>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}">{{ $b->batch_code }} ({{ $b->lab_number_from }} - {{ $b->lab_number_to }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset Filters --}}
            <div>
                <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('batchFilter', ''); $set('dateFrom', ''); $set('dateTo', '')"
                        class="w-full px-3.5 py-2 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl transition text-center">
                    <i class="fas fa-undo-alt mr-1 text-gray-400"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">

        {{-- TAB 1: ALL SAMPLES TABLE --}}
        @if($activeTab === 'samples')
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 w-10 text-center">
                                <input type="checkbox"
                                       wire:click="toggleSelectAll"
                                       @checked(!empty($samples->items()) && count(array_intersect(collect($samples->items())->pluck('id')->all(), $selected)) === count($samples->items()))
                                       class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 cursor-pointer">
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Lab Code</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Sample Ref</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Batch / Intake</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Core Parameters (N-P-K-pH)</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">Status</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($samples as $sample)
                            @php
                                $results = $sample->results->keyBy('parameter_id');
                                $nVal = $results->firstWhere('parameter.name', 'N')?->value;
                                $pVal = $results->firstWhere('parameter.name', 'P')?->value;
                                $kVal = $results->firstWhere('parameter.name', 'K')?->value;
                                $phVal = $results->firstWhere('parameter.name', 'pH')?->value;
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition group">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox"
                                           wire:model.live="selected"
                                           value="{{ $sample->id }}"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <i class="fas fa-tag text-[10px] text-emerald-500"></i>
                                        <span>{{ $sample->lab_number }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-gray-900 block">{{ $sample->sample_code }}</span>
                                    <span class="text-[11px] text-gray-400 capitalize">{{ $sample->source ?? 'Field' }} Sample</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                            {{ strtoupper(substr($sample->farmer->name ?? 'F', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900 block group-hover:text-emerald-700 transition">{{ $sample->farmer->name ?? '—' }}</span>
                                            <span class="text-xs text-gray-500">{{ $sample->farmer->phone ?? '' }} &bull; {{ $sample->farmer->village->name ?? $sample->farmer->village_name ?? '—' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sample->evaluationBatch)
                                        <button wire:click="filterByBatch({{ $sample->evaluationBatch->id }})"
                                                class="font-mono text-xs font-semibold text-emerald-700 hover:text-emerald-900 hover:underline block">
                                            {{ $sample->evaluationBatch->batch_code }}
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                    <span class="text-[11px] text-gray-400 block">{{ $sample->received_at?->format('d M Y, h:i A') ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-lg text-xs {{ $nVal !== null ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'bg-gray-100 text-gray-400' }}">
                                            N: {{ $nVal ?? '—' }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-lg text-xs {{ $pVal !== null ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'bg-gray-100 text-gray-400' }}">
                                            P: {{ $pVal ?? '—' }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-lg text-xs {{ $kVal !== null ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'bg-gray-100 text-gray-400' }}">
                                            K: {{ $kVal ?? '—' }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-lg text-xs {{ $phVal !== null ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'bg-gray-100 text-gray-400' }}">
                                            pH: {{ $phVal ?? '—' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($sample->status->value === 'reported')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>Tested</span>
                                        </span>
                                    @elseif($sample->status->value === 'testing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Testing</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Awaiting</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Enter / Edit Results (Quick Analysis) --}}
                                        <button wire:click="openQuickEntry({{ $sample->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium border border-gray-200 bg-white hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 text-gray-700 shadow-2xs transition active:scale-[0.97]"
                                                title="Record Analysis Results">
                                            <i class="fas fa-flask text-emerald-600"></i>
                                            <span>{{ $sample->status->value === 'reported' ? 'Edit' : 'Enter' }} Results</span>
                                        </button>

                                        {{-- Print Worksheet PDF for this sample --}}
                                        <a href="{{ route('lab.evaluations.selected.pdf', ['ids' => $sample->id]) }}" target="_blank"
                                           class="p-2 rounded-xl text-gray-500 hover:text-emerald-700 hover:bg-gray-100 transition"
                                           title="Reprint Worksheet (PDF)">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        {{-- Soil Report if reported --}}
                                        @if($sample->status->value === 'reported')
                                            <a href="{{ route('field.samples.report', $sample->id) }}" target="_blank"
                                               class="p-2 rounded-xl text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition"
                                               title="View Final Soil Report">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-900">No evaluated samples found</h4>
                                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Try adjusting your search criteria or receive new samples via the Intake Queue.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($samples->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $samples->links() }}
                </div>
            @endif

        {{-- TAB 2: EVALUATION BATCHES TABLE --}}
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Batch Code</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Intake Date</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Number Range</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">Samples</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Testing Progress</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Received By</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($batchesList as $batch)
                            @php
                                $progress = $batch->progress_percent;
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-gray-900 block">
                                        {{ $batch->batch_code }}
                                    </span>
                                    <span class="text-[11px] text-gray-400">{{ $batch->lab->name ?? 'Main Lab' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900 block">{{ $batch->created_at->format('d M Y') }}</span>
                                    <span class="text-[11px] text-gray-400">{{ $batch->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $batch->lab_number_from ?? '—' }} &rarr; {{ $batch->lab_number_to ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap font-bold text-gray-900">
                                    {{ $batch->sample_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-28 bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-emerald-600 h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">{{ $progress }}%</span>
                                    </div>
                                    <span class="text-[11px] text-gray-400 block mt-0.5">
                                        {{ $batch->tested_count }} / {{ $batch->sample_count }} Tested
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                    {{ $batch->receivedBy->name ?? 'System' }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Reprint Worksheet --}}
                                        <a href="{{ route('lab.evaluations.batch.pdf', $batch->id) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition active:scale-[0.97]">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>Reprint Worksheet</span>
                                        </a>

                                        {{-- View Samples in Batch --}}
                                        <button wire:click="filterByBatch({{ $batch->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-medium border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 transition">
                                            <i class="fas fa-list text-[10px] text-gray-400"></i>
                                            <span>Samples</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-900">No evaluation batches recorded yet</h4>
                                    <p class="text-xs text-gray-500 mt-1">Receive new samples from the Intake Queue to create batches.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($batchesList->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $batchesList->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- QUICK RESULTS ENTRY MODAL --}}
    @if($showQuickEntryModal && $editingSample)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-gray-100 overflow-hidden animate-scale-up">

                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-xs">
                            <i class="fas fa-flask"></i>
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900">Record Test Results</h3>
                                <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Lab Code: {{ $editingSample->lab_number }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ $editingSample->sample_code }} &bull; Beneficiary: <strong>{{ $editingSample->farmer->name ?? '—' }}</strong>
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeQuickEntry" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition" title="Close">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5 max-h-[72vh] overflow-y-auto">
                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-100 text-emerald-900 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-emerald-600"></i>
                            <span>Enter values measured on your bench worksheet. Ratings are evaluated in real-time.</span>
                        </div>
                        <a href="{{ route('lab.testing', $editingSample->id) }}"
                           class="text-xs font-bold text-emerald-700 hover:underline">
                            Full Form &rarr;
                        </a>
                    </div>

                    {{-- Parameter Grid --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                            Chemical &amp; Physical Parameters
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($parameters as $param)
                                <div class="p-3 rounded-xl border border-gray-200 bg-gray-50/60 hover:bg-white hover:border-emerald-300 transition">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="text-xs font-bold text-gray-800">
                                            {{ $param->name }}
                                            @if($param->unit)
                                                <span class="text-[10px] font-normal text-gray-500">({{ $param->unit }})</span>
                                            @endif
                                        </label>
                                        @if(!empty($quickRatings[$param->id]))
                                            <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-full uppercase
                                                @if($quickRatings[$param->id] === 'Low' || $quickRatings[$param->id] === 'Acidic') bg-rose-100 text-rose-800
                                                @elseif($quickRatings[$param->id] === 'Medium' || $quickRatings[$param->id] === 'Neutral') bg-emerald-100 text-emerald-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ $quickRatings[$param->id] }}
                                            </span>
                                        @endif
                                    </div>
                                    <input type="number"
                                           step="any"
                                           wire:model.live.debounce.300ms="quickValues.{{ $param->id }}"
                                           placeholder="Value..."
                                           class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-mono text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Remarks --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Technician Remarks &amp; Observations
                        </label>
                        <textarea wire:model="quickRemarks"
                                  rows="2"
                                  placeholder="Enter any chemical or bench observations..."
                                  class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"></textarea>
                    </div>

                    {{-- Auto Recommendations Toggle --}}
                    <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200">
                        <input type="checkbox" id="autoRec" wire:model="autoGenerateRecommendations"
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 cursor-pointer">
                        <label for="autoRec" class="text-xs text-gray-700 font-medium cursor-pointer">
                            Automatically generate fertilizer &amp; crop recommendations from rule engine
                        </label>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/70 flex items-center justify-between">
                    <button type="button" wire:click="closeQuickEntry"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 transition">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveQuickResults"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <i class="fas fa-save"></i>
                        <span>Save Results &amp; Finalize</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
