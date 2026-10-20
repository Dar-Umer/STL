<div wire:poll.60s="refresh" class="space-y-6">
@php
    $totalLabs = $totalLabs ?? 0;
    $totalUsers = $totalUsers ?? 0;
    $totalFarmers = $totalFarmers ?? 0;
    $totalSamples = $totalSamples ?? 0;
    $samplesCollected = $samplesCollected ?? 0;
    $samplesReceived = $samplesReceived ?? 0;
    $samplesTesting = $samplesTesting ?? 0;
    $samplesReported = $samplesReported ?? 0;
    $queueCount = $queueCount ?? 0;
    $samplesThisMonth = $samplesThisMonth ?? 0;
    $samplesLastMonth = $samplesLastMonth ?? 0;
    $monthlyLabels = !empty($monthlyLabels) ? $monthlyLabels : ['M1', 'M2', 'M3', 'M4', 'M5', 'M6'];
    $monthlyCounts = !empty($monthlyCounts) ? $monthlyCounts : [0, 0, 0, 0, 0, 0];
    $adminCount = $adminCount ?? 0;
    $labManagerCount = $labManagerCount ?? 0;
    $fieldOfficialCount = $fieldOfficialCount ?? 0;
    $labTechnicianCount = $labTechnicianCount ?? 0;
    $farmerCount = $farmerCount ?? 0;
    $totalTargetCount = $totalTargetCount ?? 0;
    $totalAchievedCount = $totalAchievedCount ?? 0;
    $labStats = $labStats ?? collect();
    $zoneTargets = $zoneTargets ?? collect();
    $recentSamples = $recentSamples ?? collect();
@endphp

    {{-- Welcome Header (PTA Style) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                Welcome back, {{ auth()->user()->name }}!
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">
                Here's the real-time operational health and sample lifecycle analytics across your soil testing network.
            </p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Live Syncing
            </span>
            <button wire:click="refresh"
                    wire:loading.attr="disabled"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 shadow-2xs transition active:scale-95 cursor-pointer">
                <svg wire:loading.class="animate-spin text-emerald-600" class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    {{-- Row 1: KPI Cards Grid (6 Columns - PTA Style) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Card 1: Total Samples --}}
        <a href="{{ route('samples.index') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-lg border border-gray-100 hover:border-blue-200 p-5 transition-all duration-200 hover:-translate-y-1 block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-blue-600 transition-colors">Samples</span>
                <div class="w-9 h-9 bg-blue-50/80 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ number_format($totalSamples) }}</p>
            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>{{ number_format($samplesThisMonth) }} this month</span>
            </div>
        </a>

        {{-- Card 2: Completed Tests --}}
        <a href="{{ route('samples.index') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-lg border border-gray-100 hover:border-emerald-200 p-5 transition-all duration-200 hover:-translate-y-1 block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-emerald-600 transition-colors">Completed</span>
                <div class="w-9 h-9 bg-emerald-50/80 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ number_format($samplesReported) }}</p>
            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>{{ $totalSamples > 0 ? round(($samplesReported / $totalSamples) * 100) : 0 }}% completed</span>
            </div>
        </a>

        {{-- Card 3: Laboratories --}}
        <a href="{{ route('admin.labs') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-lg border border-gray-100 hover:border-cyan-200 p-5 transition-all duration-200 hover:-translate-y-1 block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-cyan-600 transition-colors">Labs</span>
                <div class="w-9 h-9 bg-cyan-50/80 text-cyan-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-cyan-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.942A4.5 4.5 0 0115.888 17H8.112a4.5 4.5 0 01-2.342-.758L4.2 15.3m15.6 0A2.25 2.25 0 0021 13.368V7.5a2.25 2.25 0 00-2.25-2.25h-1.5M4.2 15.3A2.25 2.25 0 013 13.368V7.5a2.25 2.25 0 012.25-2.25h1.5"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ number_format($totalLabs) }}</p>
            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                <span>Testing centers</span>
            </div>
        </a>

        {{-- Card 4: Farmers --}}
        <a href="{{ route('admin.farmers') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-lg border border-gray-100 hover:border-amber-200 p-5 transition-all duration-200 hover:-translate-y-1 block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-amber-600 transition-colors">Farmers</span>
                <div class="w-9 h-9 bg-amber-50/80 text-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ number_format($totalFarmers) }}</p>
            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Enrolled growers</span>
            </div>
        </a>

        {{-- Card 5: Field Staff --}}
        <a href="{{ route('admin.users') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-lg border border-gray-100 hover:border-indigo-200 p-5 transition-all duration-200 hover:-translate-y-1 block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-indigo-600 transition-colors">Staff</span>
                <div class="w-9 h-9 bg-indigo-50/80 text-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.999-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ number_format($totalUsers) }}</p>
            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                <span>{{ $fieldOfficialCount }} in field</span>
            </div>
        </a>

        {{-- Card 6: In Queue --}}
        <a href="{{ route('lab.queue') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-lg border border-gray-100 hover:border-rose-200 p-5 transition-all duration-200 hover:-translate-y-1 block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-rose-600 transition-colors">Queue</span>
                <div class="w-9 h-9 bg-rose-50/80 text-rose-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-rose-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ number_format($queueCount) }}</p>
            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full {{ $queueCount > 0 ? 'bg-rose-500' : 'bg-gray-300' }}"></span>
                <span>{{ $samplesTesting }} in testing</span>
            </div>
        </a>
    </div>

    {{-- Row 2: Operational Pipeline Attention Alert Cards (PTA Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Alert 1: Pending Lab Intake --}}
        <a href="{{ route('lab.queue') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-md border border-gray-100 p-5 transition-all duration-200 hover:border-rose-300 hover:-translate-y-0.5 block">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Samples Awaiting Lab Intake</span>
                <span class="w-2.5 h-2.5 rounded-full {{ $samplesCollected > 0 ? 'bg-rose-500 animate-ping' : 'bg-gray-300' }}"></span>
            </div>
            <p class="text-3xl font-bold {{ $samplesCollected > 0 ? 'text-rose-600' : 'text-gray-900' }} mt-2">
                {{ number_format($samplesCollected) }}
            </p>
            <p class="text-xs text-gray-400 mt-1 font-medium">Field collected &bull; awaiting lab receipt check-in</p>
        </a>

        {{-- Alert 2: Testing In Progress --}}
        <a href="{{ route('lab.testing-list') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-md border border-gray-100 p-5 transition-all duration-200 hover:border-amber-300 hover:-translate-y-0.5 block">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Testing on Workbench</span>
                <span class="w-2.5 h-2.5 rounded-full {{ $samplesTesting > 0 ? 'bg-amber-500' : 'bg-gray-300' }}"></span>
            </div>
            <p class="text-3xl font-bold {{ $samplesTesting > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-2">
                {{ number_format($samplesTesting) }}
            </p>
            <p class="text-xs text-gray-400 mt-1 font-medium">Chemical &amp; physical parameter analysis ongoing</p>
        </a>

        {{-- Alert 3: Seasonal Target Attainment --}}
        <a href="{{ route('admin.zone-targets') }}"
           class="group bg-white rounded-2xl shadow-xs hover:shadow-md border border-gray-100 p-5 transition-all duration-200 hover:border-emerald-300 hover:-translate-y-0.5 block">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Seasonal Target Attainment</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            @php $targetRate = $totalTargetCount > 0 ? round(($totalAchievedCount / $totalTargetCount) * 100) : 0; @endphp
            <p class="text-3xl font-bold text-emerald-600 mt-2">
                {{ $targetRate }}%
            </p>
            <p class="text-xs text-gray-400 mt-1 font-medium">
                {{ number_format($totalAchievedCount) }} achieved of {{ number_format($totalTargetCount) }} targeted tests
            </p>
        </a>
    </div>

    {{-- Row 3: Output Snapshot Gradient Cards (PTA Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Card 1: Monthly Intake --}}
        <div class="bg-gradient-to-br from-emerald-50/70 via-white to-emerald-50/30 rounded-2xl shadow-xs border border-emerald-100/80 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Samples This Month</p>
                <span class="p-1.5 bg-emerald-100/70 text-emerald-700 rounded-lg text-xs font-semibold">Active Cycle</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-3 tracking-tight">{{ number_format($samplesThisMonth) }}</p>
            <p class="text-xs text-emerald-600/90 mt-1.5 font-medium">Enrolled in {{ now()->format('F Y') }}</p>
        </div>

        {{-- Card 2: Total Completed Reports --}}
        <a href="{{ route('samples.index') }}"
           class="bg-gradient-to-br from-blue-50/70 via-white to-blue-50/30 rounded-2xl shadow-xs border border-blue-100/80 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 block">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Reported &amp; Dispatched</p>
                <span class="p-1.5 bg-blue-100/70 text-blue-700 rounded-lg text-xs font-semibold">Ready</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-3 tracking-tight">{{ number_format($samplesReported) }}</p>
            <p class="text-xs text-blue-600/90 mt-1.5 font-medium">Certified soil fertility health cards</p>
        </a>

        {{-- Card 3: Inflow Turnaround Rate --}}
        <div class="bg-gradient-to-br from-purple-50/70 via-white to-purple-50/30 rounded-2xl shadow-xs border border-purple-100/80 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-purple-700">Active Pipeline Volume</p>
                <span class="p-1.5 bg-purple-100/70 text-purple-700 rounded-lg text-xs font-semibold">In Progress</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-3 tracking-tight">{{ number_format($queueCount) }}</p>
            <p class="text-xs text-purple-600/90 mt-1.5 font-medium">Across all regional testing laboratories</p>
        </div>
    </div>

    {{-- Row 4: Lifecycle Distribution & Monthly Trend (PTA Visual Analytics) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Lifecycle Progress Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Sample Lifecycle Distribution</h3>
                        <p class="text-xs text-gray-400">Progression from field collection to report delivery</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-600 border border-gray-200">
                        {{ number_format($totalSamples) }} total
                    </span>
                </div>

                @php
                    $steps = [
                        ['label' => 'Collected in Field', 'count' => $samplesCollected, 'color' => 'bg-cyan-500', 'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200'],
                        ['label' => 'Received at Lab', 'count' => $samplesReceived, 'color' => 'bg-blue-500', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                        ['label' => 'Testing in Progress', 'count' => $samplesTesting, 'color' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        ['label' => 'Reported & Completed', 'count' => $samplesReported, 'color' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                    ];
                @endphp

                <div class="space-y-4 my-2">
                    @foreach($steps as $s)
                        @php $pct = $totalSamples > 0 ? round(($s['count'] / $totalSamples) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-medium mb-1.5">
                                <span class="text-gray-700 font-semibold">{{ $s['label'] }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-900 font-bold">{{ number_format($s['count']) }}</span>
                                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold border {{ $s['badge'] }}">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $s['color'] }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400 mt-4">
                <span>Updated in real-time</span>
                <a href="{{ route('samples.index') }}" class="font-semibold text-brand-600 hover:text-brand-700">View sample tracking &rarr;</a>
            </div>
        </div>

        {{-- Monthly Trend Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Sample Inflow Trend (Last 6 Months)</h3>
                        <p class="text-xs text-gray-400">Monthly sample volume intake across all districts</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        Monthly Volume
                    </span>
                </div>

                @php $maxMonthly = max(max($monthlyCounts), 1); @endphp
                <div class="flex items-end justify-between gap-3 h-44 pt-4 px-2">
                    @foreach($monthlyCounts as $i => $count)
                        @php 
                            $height = max(($count / $maxMonthly) * 110, 8); 
                            $isLatest = $i === (count($monthlyCounts) - 1);
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                            <span class="text-[11px] font-bold {{ $count > 0 ? ($isLatest ? 'text-emerald-600' : 'text-blue-600') : 'text-gray-400' }}">
                                {{ $count }}
                            </span>
                            <div class="w-full max-w-[42px] rounded-t-xl transition-all duration-300 {{ $isLatest ? 'bg-gradient-to-t from-emerald-600 to-emerald-400 shadow-sm shadow-emerald-200' : 'bg-gradient-to-t from-blue-600 to-blue-400 group-hover:from-blue-500 group-hover:to-blue-300' }}"
                                 style="height: {{ $height }}px">
                            </div>
                            <span class="text-[11px] font-semibold {{ $isLatest ? 'text-gray-900 font-bold' : 'text-gray-400' }}">
                                {{ explode(' ', $monthlyLabels[$i] ?? '')[0] ?? ($monthlyLabels[$i] ?? '') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400 mt-4">
                <span>{{ now()->format('Y') }} Testing Season</span>
                <span class="font-medium text-emerald-700 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Current month in green
                </span>
            </div>
        </div>
    </div>

    {{-- Row 5: High-Value Performance Cards (Labs and Zones) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Laboratory Performance Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Laboratory Turnaround &amp; Workload</h3>
                    <p class="text-xs text-gray-400">Testing facility capacity and completion metrics</p>
                </div>
                <a href="{{ route('admin.labs') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1 rounded-xl transition">
                    Manage Labs
                </a>
            </div>

            <div class="overflow-x-auto -mx-6 px-6">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 uppercase tracking-wider text-[10px]">
                            <th class="pb-2.5 font-semibold">Laboratory</th>
                            <th class="pb-2.5 font-semibold text-right">Samples</th>
                            <th class="pb-2.5 font-semibold text-right">Testing</th>
                            <th class="pb-2.5 font-semibold text-right">Reported</th>
                            <th class="pb-2.5 font-semibold text-right min-w-[100px]">Completion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($labStats as $lab)
                            @php 
                                $reportPct = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100) : 0; 
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            <i class="fas fa-flask text-[11px]"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-800 truncate">{{ $lab->name }}</p>
                                            <p class="text-[10px] text-gray-400">Code: {{ $lab->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-right font-bold text-gray-800">{{ number_format($lab->soil_samples_count) }}</td>
                                <td class="py-3 text-right font-semibold text-amber-600">{{ number_format($lab->testing_count) }}</td>
                                <td class="py-3 text-right font-bold text-emerald-600">{{ number_format($lab->reported_count) }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $reportPct }}%"></div>
                                        </div>
                                        <span class="font-bold text-gray-700 text-[11px] w-8">{{ $reportPct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">No active laboratories configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Geographic Zone Targets Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Zone Target Progress</h3>
                    <p class="text-xs text-gray-400">Geographic quotas and achievement pace</p>
                </div>
                <a href="{{ route('admin.zone-targets') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1 rounded-xl transition">
                    View Targets
                </a>
            </div>

            <div class="overflow-x-auto -mx-6 px-6">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 uppercase tracking-wider text-[10px]">
                            <th class="pb-2.5 font-semibold">Zone</th>
                            <th class="pb-2.5 font-semibold text-right">Target</th>
                            <th class="pb-2.5 font-semibold text-right">Achieved</th>
                            <th class="pb-2.5 font-semibold text-right">Pending</th>
                            <th class="pb-2.5 font-semibold text-right min-w-[100px]">Pace</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($zoneTargets as $zt)
                            @php
                                $ach = $zt->achievedCount();
                                $rem = $zt->remainingCount();
                                $pct = min($zt->progressPercent(), 100);
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="py-3 font-semibold text-gray-800">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>{{ $zt->zone->name ?? 'Zone' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right font-bold text-gray-800">{{ number_format($zt->target_count) }}</td>
                                <td class="py-3 text-right font-bold text-emerald-600">{{ number_format($ach) }}</td>
                                <td class="py-3 text-right font-semibold {{ $rem > 0 ? 'text-amber-600' : 'text-gray-400' }}">{{ number_format($rem) }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="font-bold text-gray-700 text-[11px] w-8">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">No active seasonal targets defined.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Row 6: Recent Samples Activity Feed & User Capacity (PTA Activity Layout) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Samples Feed (2 Columns on large) --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Recently Registered Soil Samples</h3>
                    <p class="text-xs text-gray-400">Latest samples collected from agricultural landholdings</p>
                </div>
                <a href="{{ route('samples.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1 rounded-xl transition">
                    View all
                </a>
            </div>

            @if($recentSamples->isNotEmpty())
                <div class="divide-y divide-gray-50">
                    @foreach($recentSamples as $sample)
                        <div class="flex items-center justify-between py-3 hover:bg-gray-50/80 -mx-2 px-2 rounded-xl transition group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold shadow-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-vial"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-800 font-mono group-hover:text-brand-700 transition-colors">
                                            {{ $sample->sample_code }}
                                        </span>
                                        <span class="text-xs text-gray-400">&bull;</span>
                                        <span class="text-xs font-medium text-gray-600 truncate">
                                            {{ $sample->farmer->name ?? 'Unknown Farmer' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 flex items-center gap-2 mt-0.5">
                                        <span><i class="fas fa-flask text-gray-300 mr-1"></i>{{ $sample->lab->name ?? 'Unassigned' }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fas fa-map-marker-alt text-gray-300 mr-1"></i>{{ $sample->farmer->zone->name ?? 'General' }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                @php
                                    $val = $sample->status->value ?? 'collected';
                                    $badgeStyle = match($val) {
                                        'reported' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'testing' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'received' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full border {{ $badgeStyle }}">
                                    {{ $sample->status->label() }}
                                </span>
                                <span class="text-xs text-gray-400 hidden sm:inline">
                                    {{ $sample->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-xs text-gray-400">
                    No samples registered yet.
                </div>
            @endif
        </div>

        {{-- Role Capacity & Distribution --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">User Distribution</h3>
                        <p class="text-xs text-gray-400">Staff &amp; portal accounts by role</p>
                    </div>
                    <span class="text-xs font-semibold text-gray-400">{{ number_format($totalUsers + $farmerCount) }} accounts</span>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-rose-50/60 border border-rose-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-user-lock"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">System Administrators</span>
                        </div>
                        <span class="text-sm font-bold text-rose-700">{{ number_format($adminCount) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/60 border border-amber-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Lab Managers</span>
                        </div>
                        <span class="text-sm font-bold text-amber-700">{{ number_format($labManagerCount) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-indigo-50/60 border border-indigo-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Lab Technicians</span>
                        </div>
                        <span class="text-sm font-bold text-indigo-700">{{ number_format($labTechnicianCount) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-cyan-50/60 border border-cyan-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Field Officials</span>
                        </div>
                        <span class="text-sm font-bold text-cyan-700">{{ number_format($fieldOfficialCount) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-tractor"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Farmer Beneficiaries</span>
                        </div>
                        <span class="text-sm font-bold text-emerald-700">{{ number_format($farmerCount) }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.users') }}" class="mt-4 inline-flex items-center justify-between text-xs font-semibold text-brand-600 hover:text-brand-700 pt-3 border-t border-gray-100">
                <span>Manage staff accounts &amp; roles</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Row 7: Quick Actions Grid (PTA Style) --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-900 mb-0.5">Quick Actions &amp; Operational Shortcuts</h3>
        <p class="text-xs text-gray-400 mb-4">Direct shortcuts to high-frequency laboratory and field operations.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <a href="{{ route('field.collect-sample') }}"
               class="group flex items-center justify-between px-4 py-3 bg-gray-50/70 border border-gray-100 hover:border-brand-200 rounded-xl text-sm font-medium text-gray-700 hover:text-brand-700 hover:bg-brand-50/60 transition-all duration-200">
                <span class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Collect Field Sample
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.farmers') }}"
               class="group flex items-center justify-between px-4 py-3 bg-gray-50/70 border border-gray-100 hover:border-brand-200 rounded-xl text-sm font-medium text-gray-700 hover:text-brand-700 hover:bg-brand-50/60 transition-all duration-200">
                <span class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Farmers Directory
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.parameters') }}"
               class="group flex items-center justify-between px-4 py-3 bg-gray-50/70 border border-gray-100 hover:border-brand-200 rounded-xl text-sm font-medium text-gray-700 hover:text-brand-700 hover:bg-brand-50/60 transition-all duration-200">
                <span class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                    Soil Test Parameters
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.qr-codes') }}"
               class="group flex items-center justify-between px-4 py-3 bg-gray-50/70 border border-gray-100 hover:border-brand-200 rounded-xl text-sm font-medium text-gray-700 hover:text-brand-700 hover:bg-brand-50/60 transition-all duration-200">
                <span class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    QR Code Batch Print
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.zone-targets') }}"
               class="group flex items-center justify-between px-4 py-3 bg-gray-50/70 border border-gray-100 hover:border-brand-200 rounded-xl text-sm font-medium text-gray-700 hover:text-brand-700 hover:bg-brand-50/60 transition-all duration-200">
                <span class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Manage Zone Targets
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.reports') }}"
               class="group flex items-center justify-between px-4 py-3 bg-gray-50/70 border border-gray-100 hover:border-brand-200 rounded-xl text-sm font-medium text-gray-700 hover:text-brand-700 hover:bg-brand-50/60 transition-all duration-200">
                <span class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    Analytics &amp; MIS Reports
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
