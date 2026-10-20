<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Operational Analytics &amp; Reports
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Audit-ready turnaround tracking, sample pipeline intelligence, lab throughput, and field productivity</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                {{ now()->format('d M Y, h:i A') }}
            </span>
            <button wire:click="loadAllData" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-xs rounded-xl shadow-xs transition">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh Data
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">From Date</label>
                <input type="date" wire:model.live="dateFrom" max="{{ $dateTo ?: '' }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">To Date</label>
                <input type="date" wire:model.live="dateTo" min="{{ $dateFrom ?: '' }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
            </div>
            @if($isAdmin)
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Laboratory</label>
                <select wire:model.live="labFilter" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                    <option value="">All Laboratories</option>
                    @foreach($labs as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="md:col-span-{{ $isAdmin ? 3 : 6 }}">
                <button wire:click="loadAllData" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs rounded-xl shadow-xs transition active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Apply Filter Range
                </button>
            </div>
        </div>
    </div>

    {{-- Segmented Tabs --}}
    <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-2xl overflow-x-auto w-full sm:w-max">
        <button wire:click="$set('activeTab', 'summary')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $activeTab === 'summary' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Summary Dashboard
        </button>
        <button wire:click="$set('activeTab', 'lab')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $activeTab === 'lab' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            By Laboratory
        </button>
        <button wire:click="$set('activeTab', 'zone')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $activeTab === 'zone' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            By Zone
        </button>
        <button wire:click="$set('activeTab', 'field-official')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $activeTab === 'field-official' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Field Officials
        </button>
        <button wire:click="$set('activeTab', 'technician')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $activeTab === 'technician' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Technicians
        </button>
        <button wire:click="$set('activeTab', 'self-sample')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $activeTab === 'self-sample' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Self Samples
        </button>
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

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Samples</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($total) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Collected</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($collected) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Received Lab</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($received) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">In Testing</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($testing) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Reported</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($reported) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">In Queue</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($queue) }}</p>
            </div>
        </div>
    </div>

    {{-- Pipeline + Completion Rate + Monthly Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Pipeline Breakdown --}}
        <div class="lg:col-span-5 bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Workflow Pipeline</h3>
                    <span class="text-xs text-gray-400 font-medium">Conversion</span>
                </div>

                @php $pipelineMax = max($total, 1); @endphp
                @php $pipelineItems = [
                    ['label' => 'Collected', 'count' => $collected, 'bg' => 'bg-cyan-500'],
                    ['label' => 'Received', 'count' => $received, 'bg' => 'bg-blue-500'],
                    ['label' => 'Testing', 'count' => $testing, 'bg' => 'bg-amber-500'],
                    ['label' => 'Reported', 'count' => $reported, 'bg' => 'bg-emerald-500'],
                ]; @endphp

                <div class="space-y-3.5">
                    @foreach($pipelineItems as $pi)
                    @php $pct = round(($pi['count'] / $pipelineMax) * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-gray-700">{{ $pi['label'] }}</span>
                            <span class="font-bold text-gray-900">{{ number_format($pi['count']) }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $pi['bg'] }} rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 mt-4 flex items-center justify-between text-xs text-gray-600">
                <span class="inline-flex items-center gap-1 font-medium"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Field: <strong>{{ number_format($field) }}</strong></span>
                <span class="inline-flex items-center gap-1 font-medium"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Self: <strong>{{ number_format($self) }}</strong></span>
            </div>
        </div>

        {{-- Completion Radial KPI --}}
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col items-center justify-center text-center">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Report Completion Rate</h3>
            
            <div class="relative w-32 h-32 flex items-center justify-center mb-3">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="{{ $reportRate >= 80 ? 'text-emerald-500' : ($reportRate >= 50 ? 'text-amber-500' : 'text-rose-500') }} transition-all duration-1000" stroke-dasharray="{{ $reportRate }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $reportRate }}%</span>
                    <span class="text-[10px] uppercase font-bold text-gray-400">Completed</span>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2">
                <strong class="text-gray-900">{{ number_format($reported) }}</strong> of {{ number_format($total) }} finalized
            </p>
        </div>

        {{-- Monthly Trend Histogram --}}
        <div class="lg:col-span-4 bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Monthly Intake</h3>
                <span class="text-xs text-gray-400 font-medium">Last 6 Months</span>
            </div>

            @php $maxMonthly = max(array_merge($monthlyCounts, [1])); @endphp
            <div class="h-32 flex items-end justify-between gap-2 pt-2">
                @foreach($monthlyCounts as $i => $count)
                @php $barPct = max(round(($count / $maxMonthly) * 100), 4); @endphp
                <div class="flex-1 flex flex-col items-center h-full justify-end group">
                    <span class="text-[10px] font-bold text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity mb-1">{{ $count }}</span>
                    <div class="w-full bg-gradient-to-t from-emerald-600 to-emerald-400 rounded-t-lg transition-all duration-500 hover:brightness-110" style="height: {{ $barPct }}%"></div>
                    <span class="text-[10px] font-semibold text-gray-400 uppercase mt-2">{{ \Carbon\Carbon::parse($monthlyLabels[$i])->format('M') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Comparison Tables: Lab & Zone Summaries --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Lab Summary Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Lab Summary</h3>
                <button wire:click="$set('activeTab', 'lab')" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                    View Full Table &rarr;
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase text-gray-500">Laboratory</th>
                            <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Samples</th>
                            <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                            <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-32">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($labReportData as $lab)
                        @php $r = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-3 font-bold text-gray-900">{{ $lab->name }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->soil_samples_count) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-600">{{ number_format($lab->reported_count) }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $r >= 80 ? 'bg-emerald-500' : ($r >= 50 ? 'bg-amber-500' : 'bg-rose-500') }} rounded-full" style="width: {{ $r }}%"></div>
                                    </div>
                                    <span class="font-bold text-gray-700 w-8 text-right">{{ $r }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400">No laboratory data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Zone Summary Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Zone Summary</h3>
                <button wire:click="$set('activeTab', 'zone')" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                    View Full Table &rarr;
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase text-gray-500">Zone</th>
                            <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Samples</th>
                            <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                            <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-32">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($zoneReportData as $zone)
                        @php $r = $zone->samples_count > 0 ? round(($zone->reported_count / $zone->samples_count) * 100) : 0; @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-3 font-bold text-gray-900">{{ $zone->name }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->samples_count) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-emerald-600">{{ number_format($zone->reported_count) }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $r >= 80 ? 'bg-emerald-500' : ($r >= 50 ? 'bg-amber-500' : 'bg-rose-500') }} rounded-full" style="width: {{ $r }}%"></div>
                                    </div>
                                    <span class="font-bold text-gray-700 w-8 text-right">{{ $r }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400">No zone data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY LAB TAB ═══════════════════════════ --}}
    @if($activeTab == 'lab')
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Laboratory Throughput &amp; Processing Report</h2>
            <div class="flex items-center gap-2">
                <button wire:click="exportLabExcel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-emerald-700 bg-white hover:bg-emerald-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </button>
                <button wire:click="exportLabPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-rose-700 bg-white hover:bg-rose-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-10">#</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Laboratory</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500">Code</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Total</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Field</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Self</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Collected</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Received</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Testing</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Farmers</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Staff</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-36">Completion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($labReportData as $i => $lab)
                    @php $r = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; @endphp
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-bold text-gray-900">{{ $lab->name }}</td>
                        <td class="px-4 py-3"><span class="font-mono text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $lab->code }}</span></td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ number_format($lab->soil_samples_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->soil_samples_count - ($lab->self_samples_count ?? 0)) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->self_samples_count ?? 0) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->collected_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->received_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->testing_count) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($lab->reported_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->farmers_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->users_count) }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $r >= 80 ? 'bg-emerald-500' : ($r >= 50 ? 'bg-amber-500' : 'bg-rose-500') }} rounded-full" style="width: {{ $r }}%"></div>
                                </div>
                                <span class="font-bold text-gray-700 w-8 text-right">{{ $r }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="13" class="px-5 py-12 text-center text-gray-400">No laboratory records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY ZONE TAB ═══════════════════════════ --}}
    @if($activeTab == 'zone')
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Zonal Agricultural Sampling Report</h2>
            <div class="flex items-center gap-2">
                <button wire:click="exportZoneExcel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-emerald-700 bg-white hover:bg-emerald-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </button>
                <button wire:click="exportZonePdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-rose-700 bg-white hover:bg-rose-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-10">#</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Zone Name</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Total</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Field</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Self</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Collected</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Received</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Testing</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Officials</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-36">Completion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($zoneReportData as $i => $zone)
                    @php $r = $zone->samples_count > 0 ? round(($zone->reported_count / $zone->samples_count) * 100) : 0; @endphp
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-bold text-gray-900">{{ $zone->name }}</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ number_format($zone->samples_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->samples_count - ($zone->self_samples_count ?? 0)) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->self_samples_count ?? 0) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->collected_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->received_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->testing_count) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($zone->reported_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($zone->field_officials_count) }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $r >= 80 ? 'bg-emerald-500' : ($r >= 50 ? 'bg-amber-500' : 'bg-rose-500') }} rounded-full" style="width: {{ $r }}%"></div>
                                </div>
                                <span class="font-bold text-gray-700 w-8 text-right">{{ $r }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="px-5 py-12 text-center text-gray-400">No zone sampling records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY FIELD OFFICIAL TAB ═══════════════════════════ --}}
    @if($activeTab == 'field-official')
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Field Staff Productivity &amp; Intake Log</h2>
            <div class="flex items-center gap-2">
                <button wire:click="exportFieldOfficialExcel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-emerald-700 bg-white hover:bg-emerald-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </button>
                <button wire:click="exportFieldOfficialPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-rose-700 bg-white hover:bg-rose-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-10">#</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Official Name</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500">Email</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500">Jurisdiction</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Total Intake</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Collected</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Received</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Testing</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Last Active Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($fieldOfficialReportData as $i => $official)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-bold text-gray-900">{{ $official->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $official->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-purple-50 text-purple-700 font-medium">
                                {{ $official->zone->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ number_format($official->total_collected) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($official->collected_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($official->received_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($official->testing_count) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($official->reported_count) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $official->last_collection ? \Carbon\Carbon::parse($official->last_collection)->format('d M Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="px-5 py-12 text-center text-gray-400">No field staff intake logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ BY TECHNICIAN TAB ═══════════════════════════ --}}
    @if($activeTab == 'technician')
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Lab Technician Testing Performance</h2>
            <div class="flex items-center gap-2">
                <button wire:click="exportTechnicianExcel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-emerald-700 bg-white hover:bg-emerald-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </button>
                <button wire:click="exportTechnicianPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-rose-700 bg-white hover:bg-rose-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-10">#</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Technician</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500">Email</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500">Assigned Lab</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Total Tests</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Received</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">In Testing</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Last Test Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($technicianReportData as $i => $tech)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-bold text-gray-900">{{ $tech->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $tech->email }}</td>
                        <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-cyan-50 text-cyan-700 font-medium">{{ $tech->lab->name ?? '—' }}</span></td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ number_format($tech->total_analyzed) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($tech->received_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($tech->testing_count) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($tech->reported_count) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $tech->last_analysis ? \Carbon\Carbon::parse($tech->last_analysis)->format('d M Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-5 py-12 text-center text-gray-400">No technician analysis records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Self-Samples</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($selfTotal) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Received Lab</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($selfReceived) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Testing</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($selfTesting) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Reported</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($selfReported) }}</p>
            </div>
        </div>
    </div>

    {{-- Self Sample by Lab Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Self-Registered Samples by Lab</h2>
            <div class="flex items-center gap-2">
                <button wire:click="exportSelfSampleExcel" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-emerald-700 bg-white hover:bg-emerald-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </button>
                <button wire:click="exportSelfSamplePdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-rose-700 bg-white hover:bg-rose-50 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-10">#</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Laboratory</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Total</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Received</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Testing</th>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 text-right">Reported</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500 w-36">Report Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($selfSampleLabData as $i => $lab)
                    @php $r = $lab->self_samples_count > 0 ? round(($lab->self_reported_count / $lab->self_samples_count) * 100) : 0; @endphp
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-bold text-gray-900">{{ $lab->name }}</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ number_format($lab->self_samples_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->self_received_count) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($lab->self_testing_count) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($lab->self_reported_count) }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $r >= 80 ? 'bg-emerald-500' : ($r >= 50 ? 'bg-amber-500' : 'bg-rose-500') }} rounded-full" style="width: {{ $r }}%"></div>
                                </div>
                                <span class="font-bold text-gray-700 w-8 text-right">{{ $r }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No self-samples registered.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
