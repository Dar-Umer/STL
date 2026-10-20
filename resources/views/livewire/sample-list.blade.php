@php $isFarmer = auth()->user()->hasRole('Farmer'); @endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                {{ $isFarmer ? 'My Soil Sample Intake' : 'Soil Sample Master Directory' }}
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">
                {{ number_format($totalCount) }} total registered samples &middot; {{ number_format($samples->total()) }} matching active filters
            </p>
        </div>
        @if(!$isFarmer)
        <div>
            <button wire:click="exportExcel" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="exportExcel"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" wire:loading wire:target="exportExcel"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                <span wire:loading wire:target="exportExcel">Generating...</span>
            </button>
        </div>
        @endif
    </div>

    {{-- Stat KPI Cards --}}
    @if(!$isFarmer && !empty($stats))
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach($stats as $stat)
        @php
            $iconBg = match($stat['value']) {
                'reported' => 'bg-emerald-50 text-emerald-600',
                'received' => 'bg-blue-50 text-blue-600',
                'testing' => 'bg-amber-50 text-amber-600',
                default => 'bg-cyan-50 text-cyan-600',
            };
        @endphp
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0">
                @if($stat['value'] === 'reported')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($stat['value'] === 'received')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                @elseif($stat['value'] === 'testing')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                @endif
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ $stat['label'] }}</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stat['count']) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Filter Bar --}}
    @if(!$isFarmer)
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search sample code, farmer, phone..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
            </div>

            <div class="md:col-span-2">
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <input wire:model.live="dateFrom" type="date" max="{{ $dateTo ?: '' }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
            </div>

            <div class="md:col-span-2">
                <input wire:model.live="dateTo" type="date" min="{{ $dateFrom ?: '' }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
            </div>

            <div class="md:col-span-2 text-right">
                @if($search || $statusFilter || $dateFrom || $dateTo)
                <button wire:click="$set('search',''); $set('statusFilter',''); $set('dateFrom',''); $set('dateTo','')" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Samples Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Sample Records</h2>
            <span class="text-xs text-gray-500 font-medium">{{ $samples->total() }} samples</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Sample Code</th>
                        @if(!$isFarmer)
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Farmer</th>
                        @endif
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Plot &amp; Crop</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Source</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Status</th>
                        @if(!$isFarmer)
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Laboratory</th>
                        @endif
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500">Collection Date</th>
                        @if($isFarmer)
                        <th class="px-6 py-3.5 font-semibold uppercase text-gray-500 text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($samples as $sample)
                    <tr class="hover:bg-gray-50/70 transition group">
                        <td class="px-6 py-4">
                            @if($isFarmer)
                            <a href="{{ route('farmer.samples.detail', $sample->id) }}" class="font-mono text-xs font-bold text-emerald-700 hover:text-emerald-900">
                                {{ $sample->sample_code }}
                            </a>
                            @else
                            <a href="{{ route('field.samples.detail', $sample->id) }}" class="font-mono text-xs font-bold text-gray-900 hover:text-emerald-700 block">
                                {{ $sample->sample_code }}
                            </a>
                            <span class="text-[10px] text-gray-400 font-mono">ID #{{ $sample->id }}</span>
                            @endif
                        </td>

                        @if(!$isFarmer)
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-[10px]">
                                    {{ substr($sample->farmer->name ?? 'F', 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 block">{{ $sample->farmer->name ?? '—' }}</span>
                                    @if($sample->farmer?->phone)
                                    <span class="text-[11px] text-gray-400">{{ $sample->farmer->phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @endif

                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800 block">{{ $sample->plot->plot_name ?? 'Plot N/A' }}</span>
                            @if($sample->plot?->crop)
                            <span class="text-[11px] text-gray-400">{{ $sample->plot->crop->name }}</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($sample->source === 'self')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                Self-Sample
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                Field Staff
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $val = $sample->status->value ?? 'collected';
                            @endphp
                            @if($val === 'reported')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $sample->status->label() }}
                            </span>
                            @elseif($val === 'testing')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                {{ $sample->status->label() }}
                            </span>
                            @elseif($val === 'received')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                {{ $sample->status->label() }}
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                {{ $sample->status->label() }}
                            </span>
                            @endif
                        </td>

                        @if(!$isFarmer)
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ $sample->lab?->name ?? '—' }}
                        </td>
                        @endif

                        <td class="px-6 py-4 text-gray-500">
                            {{ $sample->collected_at?->format('d M Y') ?? $sample->created_at->format('d M Y') }}
                        </td>

                        @if($isFarmer)
                        <td class="px-6 py-4 text-right">
                            @if($sample->status->value === 'reported')
                            <a href="{{ route('farmer.samples.report', $sample->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                Soil Health Card
                            </a>
                            @else
                            <span class="text-xs text-gray-400 font-medium">Under Analysis</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isFarmer ? 6 : 7 }}" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <p class="font-medium text-gray-500">No samples found</p>
                            <p class="text-xs text-gray-400 mt-1">Adjust your search parameters or check filter dates</p>
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
    </div>
</div>
