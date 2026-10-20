<div wire:poll.60s="refresh" class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-emerald-500/20">
                <i class="fas fa-microscope"></i>
            </span>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Laboratory Operations</h2>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Sync
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Daily sample intake, testing queues, and result reporting</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="refresh" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" wire:loading.class="animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    {{-- Overdue Warning Alert --}}
    @if($overdueCount > 0)
        @php $threshold = (int) setting('overdue_days', 2); @endphp
        <div class="bg-gradient-to-r from-rose-500 to-rose-600 rounded-2xl shadow-lg shadow-rose-500/20 text-white p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center text-white text-lg flex-shrink-0">
                    <i class="fas fa-triangle-exclamation"></i>
                </span>
                <div>
                    <h4 class="font-bold text-sm tracking-wide">
                        {{ $overdueCount }} {{ \Illuminate\Support\Str::plural('Sample', $overdueCount) }} Overdue for Testing
                    </h4>
                    <p class="text-xs text-rose-100 mt-0.5">
                        Received more than {{ $threshold }} {{ \Illuminate\Support\Str::plural('day', $threshold) }} ago and pending analysis.
                    </p>
                </div>
            </div>
            <a href="{{ route('lab.testing-list') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white text-rose-700 hover:bg-rose-50 font-bold text-xs rounded-xl shadow-sm transition active:scale-[0.98]">
                <span>Go to Testing Queue</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    @endif

    {{-- 4 KPI Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Received Today --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Received Today</span>
                <span class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-download"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($todayReceived) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-full">
                    Intake
                </span>
            </div>
        </div>

        {{-- Awaiting Intake --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Awaiting Intake</span>
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-inbox"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($collectedCount) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                    In Transit
                </span>
            </div>
        </div>

        {{-- Testing Queue --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Testing Queue</span>
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-flask"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($pendingTesting) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                    At Lab
                </span>
            </div>
        </div>

        {{-- Completed This Week --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Completed (7 Days)</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-circle-check"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($completedThisWeek) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Reported
                </span>
            </div>
        </div>
    </div>

    {{-- Quick Action Launchbar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('lab.queue') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <i class="fas fa-qrcode"></i>
                <span>Receive Samples (QR Intake)</span>
            </a>
            <a href="{{ route('lab.self-sample') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-hand-holding text-emerald-600"></i>
                <span>Self Sample Intake</span>
            </a>
            <a href="{{ route('lab.testing-list') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-vial text-blue-600"></i>
                <span>Sample Testing Workbench</span>
            </a>
        </div>
        <div class="text-xs text-gray-500">
            Total Samples Reported: <strong class="text-gray-900 font-semibold">{{ number_format($totalReported) }}</strong>
        </div>
    </div>

    {{-- Dual Workbench Columns: Awaiting Intake + Pending Testing --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Column 1: Awaiting Intake --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-inbox"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Samples to Receive</h3>
                        <p class="text-[11px] text-gray-400">Collected in field, awaiting lab check-in</p>
                    </div>
                </div>
                @if($collectedSamples->count() > 0)
                    <button wire:click="receiveAll" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition active:scale-[0.98]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Receive All ({{ $collectedSamples->count() }})</span>
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Sample Code</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Collected</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($collectedSamples as $sample)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3 font-mono font-bold text-gray-900">
                                    {{ $sample->sample_code }}
                                </td>
                                <td class="px-5 py-3 text-gray-700 font-medium">
                                    {{ $sample->farmer->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-gray-400">
                                    {{ $sample->collected_at?->format('d M H:i') ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <button wire:click="markReceived({{ $sample->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition active:scale-[0.98]">
                                        <i class="fas fa-check text-[10px]"></i>
                                        <span>Receive</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-xs text-gray-400">
                                    <i class="fas fa-circle-check text-emerald-400 text-lg mb-1 block"></i>
                                    All collected samples have been received into the lab.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Column 2: Pending Testing --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-flask"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Pending Testing</h3>
                        <p class="text-[11px] text-gray-400">Received at lab, awaiting chemical analysis</p>
                    </div>
                </div>
                <a href="{{ route('lab.testing-list') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    <span>View Queue</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Sample Code</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Received</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingSamples as $sample)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3 font-mono font-bold text-gray-900">
                                    {{ $sample->sample_code }}
                                </td>
                                <td class="px-5 py-3 text-gray-700 font-medium">
                                    {{ $sample->farmer->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-gray-400">
                                    {{ $sample->received_at?->format('d M H:i') ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('lab.testing', $sample->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition active:scale-[0.98]">
                                        <i class="fas fa-vial text-[10px]"></i>
                                        <span>Test</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-gray-400">
                                    No samples currently waiting for testing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Unified Search & Filter Directory --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Sample Explorer</h3>
                <p class="text-xs text-gray-500">Live query across all samples assigned to your laboratory</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Sample code or farmer..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-9 pr-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <select wire:model.live="statusFilter"
                        class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    <option value="">All Statuses</option>
                    <option value="collected">Collected</option>
                    <option value="received">Received</option>
                    <option value="reported">Reported</option>
                </select>

                <select wire:model.live="dateFilter"
                        class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>

                <a href="{{ route('samples.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition shadow-xs">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Full Catalog</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Sample Code</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Plot</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Source</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Laboratory</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($searchResults as $sample)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                {{ $sample->sample_code }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $sample->farmer->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $sample->plot->plot_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($sample->source === 'self')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-200">
                                        <i class="fas fa-hand-holding text-[10px]"></i>
                                        Self
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        <i class="fas fa-tractor text-[10px]"></i>
                                        Field
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                {{ $sample->lab->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
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
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                    {{ $sample->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400 font-mono">
                                {{ $sample->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sample->status->value == 'collected')
                                    <button wire:click="markReceived({{ $sample->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg shadow-xs transition active:scale-[0.98]">
                                        <i class="fas fa-check text-[10px]"></i>
                                        <span>Receive</span>
                                    </button>
                                @elseif($sample->status->value == 'received')
                                    <a href="{{ route('lab.testing', $sample->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                                        <i class="fas fa-vial text-[10px]"></i>
                                        <span>Test</span>
                                    </a>
                                @else
                                    <a href="{{ route('field.samples.report', $sample->id) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-xs transition active:scale-[0.98]">
                                        <i class="fas fa-file-pdf text-[10px] text-rose-500"></i>
                                        <span>Report</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <p class="text-sm">No samples matched the specified filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Overview Cards: Status Pipeline + Recently Completed --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Status Breakdown --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-emerald-600"></i>
                Sample Lifecycle Pipeline
            </h3>
            @php
                $statuses = [
                    ['label' => 'Collected in Field', 'count' => $collectedCount, 'color' => 'bg-amber-500'],
                    ['label' => 'Received at Lab (Queue)', 'count' => $pendingTesting, 'color' => 'bg-blue-500'],
                    ['label' => 'Analysis Complete & Reported', 'count' => $totalReported, 'color' => 'bg-emerald-500'],
                ];
                $maxS = max($collectedCount, $pendingTesting, $totalReported, 1);
            @endphp
            <div class="space-y-4">
                @foreach($statuses as $s)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-medium text-gray-700">{{ $s['label'] }}</span>
                            <span class="font-bold text-gray-900">{{ number_format($s['count']) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full {{ $s['color'] }} transition-all duration-500"
                                 style="width: {{ round(($s['count'] / $maxS) * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recently Completed --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-gray-900">Recently Analyzed Samples</h3>
                </div>
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Latest 5</span>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Sample Code</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500">Analyzed</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-gray-500 text-right">Report</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentCompleted as $sample)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3 font-mono font-bold text-gray-900">{{ $sample->sample_code }}</td>
                                <td class="px-5 py-3 text-gray-700 font-medium">{{ $sample->farmer->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-400">{{ $sample->analyzed_at?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('field.samples.report', $sample->id) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition">
                                        <i class="fas fa-file-pdf text-[10px]"></i>
                                        <span>PDF</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-gray-400">
                                    No completed samples in this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Zone Targets Progress Overview (if any) --}}
    @if($zoneTargets && $zoneTargets->count())
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-bullseye"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Zone Target Execution</h3>
                        <p class="text-[11px] text-gray-400">Quarterly and seasonal sample intake targets per block</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ number_format($totalAchievedCount) }} / {{ number_format($totalTargetCount) }} Total
                    ({{ $totalTargetCount > 0 ? round(($totalAchievedCount / $totalTargetCount) * 100) : 0 }}%)
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Zone / Block</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Target</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Achieved</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Remaining</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500" style="min-width: 220px;">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($zoneTargets as $zt)
                            @php
                                $ach = $zt->achievedCount();
                                $rem = $zt->remainingCount();
                                $pct = $zt->progressPercent();
                                $barBg = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-rose-500');
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $zt->zone->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-gray-700">
                                    {{ number_format($zt->target_count) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-emerald-700">
                                    {{ number_format($ach) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono {{ $rem > 0 ? 'text-gray-500' : 'text-emerald-600 font-bold' }}">
                                    {{ number_format($rem) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="h-2.5 rounded-full {{ $barBg }} transition-all duration-500"
                                                 style="width: {{ min($pct, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 w-10 text-right">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
