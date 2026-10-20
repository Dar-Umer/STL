<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Location &amp; Administrative Hierarchy
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Manage jurisdictional coverage across states, districts, agricultural zones, panchayats, and villages</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button wire:click="toggleImport" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-sm rounded-xl shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Import CSV / Excel
            </button>
            <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Location
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Units</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stats['total']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">States / UT</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stats['state']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Districts</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stats['district']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Zones</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stats['zone']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Panchayats</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stats['panchayat']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Villages</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">{{ number_format($stats['village']) }}</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 space-y-3">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            {{-- Segmented Pills --}}
            <div class="flex items-center gap-1.5 p-1 bg-gray-100 rounded-xl overflow-x-auto w-full sm:w-auto">
                <button wire:click.prevent="$set('filterType', 'all')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'all' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">All</button>
                <button wire:click.prevent="$set('filterType', 'state')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'state' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">States</button>
                <button wire:click.prevent="$set('filterType', 'district')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'district' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">Districts</button>
                <button wire:click.prevent="$set('filterType', 'zone')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'zone' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">Zones</button>
                <button wire:click.prevent="$set('filterType', 'panchayat')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'panchayat' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">Panchayats</button>
                <button wire:click.prevent="$set('filterType', 'village')" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'village' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">Villages</button>
            </div>

            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search locations by name..." class="w-full pl-10 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                @if($search)
                <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Content --}}
    @if($search || $filterType !== 'all')
    {{-- Flat Filtered Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Location Name</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Level / Type</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Parent Jurisdiction</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($allLocations as $loc)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-3.5">
                            <span class="font-bold text-gray-900">{{ $loc->name }}</span>
                            @if($loc->code)
                            <span class="font-mono text-xs text-gray-400 ml-1">({{ $loc->code }})</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            @if($loc->type === 'state')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 uppercase">State</span>
                            @elseif($loc->type === 'district')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100 uppercase">District</span>
                            @elseif($loc->type === 'zone')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100 uppercase">Zone</span>
                            @elseif($loc->type === 'panchayat')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100 uppercase">Panchayat</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase">Village</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-xs text-gray-600 font-medium">
                            {{ $loc->parent?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <button wire:click="edit({{ $loc->id }})" class="p-1.5 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $loc->id }})" class="p-1.5 rounded-xl text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            No locations found matching your filter criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    {{-- Hierarchy Tree Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Administrative Geographic Tree</h2>
            <span class="text-xs text-gray-500 font-medium">State &rarr; District &rarr; Zone &rarr; Panchayat &rarr; Village</span>
        </div>

        <div class="p-6 space-y-4">
            @forelse($states as $state)
            <div class="border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
                {{-- State Header --}}
                <div class="px-5 py-3.5 bg-blue-50/60 border-b border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span class="font-bold text-gray-900 text-sm">{{ $state->name }}</span>
                        @if($state->code)<span class="font-mono text-xs text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md font-semibold">{{ $state->code }}</span>@endif
                        <span class="text-xs text-gray-400 ml-1">({{ count($state->children) }} districts)</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="edit({{ $state->id }})" class="p-1 rounded-lg text-gray-500 hover:text-emerald-600 hover:bg-white transition" title="Edit State">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="confirmDelete({{ $state->id }})" class="p-1 rounded-lg text-gray-500 hover:text-rose-600 hover:bg-white transition" title="Delete State">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Districts --}}
                <div class="divide-y divide-gray-100 bg-white">
                    @forelse($state->children as $district)
                    <div class="px-5 py-3 pl-8 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                                <span class="font-bold text-gray-800 text-xs tracking-wide uppercase">{{ $district->name }}</span>
                                <span class="text-xs text-gray-400">({{ count($district->children) }} zones)</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="edit({{ $district->id }})" class="p-1 rounded-lg text-gray-400 hover:text-emerald-600 transition" title="Edit District"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <button wire:click="confirmDelete({{ $district->id }})" class="p-1 rounded-lg text-gray-400 hover:text-rose-600 transition" title="Delete District"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </div>

                        {{-- Zones --}}
                        @if(count($district->children))
                        <div class="pl-6 space-y-1.5 border-l-2 border-gray-100 ml-1">
                            @foreach($district->children as $zone)
                            <div class="flex items-center justify-between text-xs py-1 group/zone">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    <span class="font-medium text-gray-700">{{ $zone->name }}</span>
                                    @if($zone->code)<span class="font-mono text-[10px] text-gray-400">({{ $zone->code }})</span>@endif
                                    <span class="text-[11px] text-gray-400">&middot; {{ count($zone->children) }} panchayats</span>
                                </div>
                                <div class="opacity-0 group-hover/zone:opacity-100 flex items-center gap-1 transition">
                                    <button wire:click="edit({{ $zone->id }})" class="p-0.5 text-gray-400 hover:text-emerald-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button wire:click="confirmDelete({{ $zone->id }})" class="p-0.5 text-gray-400 hover:text-rose-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </div>
                            </div>

                            {{-- Panchayats --}}
                            @if(count($zone->children))
                            <div class="pl-4 space-y-1 border-l border-dashed border-gray-200 ml-1">
                                @foreach($zone->children as $panchayat)
                                <div class="flex items-center justify-between text-xs py-0.5 group/panchayat">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        <span class="text-gray-600">{{ $panchayat->name }}</span>
                                        <span class="text-[10px] text-gray-400">({{ count($panchayat->children) }} villages)</span>
                                    </div>
                                    <div class="opacity-0 group-hover/panchayat:opacity-100 flex items-center gap-1 transition">
                                        <button wire:click="edit({{ $panchayat->id }})" class="p-0.5 text-gray-400 hover:text-emerald-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                        <button wire:click="confirmDelete({{ $panchayat->id }})" class="p-0.5 text-gray-400 hover:text-rose-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </div>
                                </div>

                                {{-- Villages --}}
                                @if(count($panchayat->children))
                                <div class="pl-4 flex flex-wrap gap-1.5 pt-1">
                                    @foreach($panchayat->children as $village)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        {{ $village->name }}
                                        <button wire:click="edit({{ $village->id }})" class="text-emerald-600 hover:text-emerald-900 ml-0.5"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                        <button wire:click="confirmDelete({{ $village->id }})" class="text-rose-400 hover:text-rose-700"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="px-5 py-3 text-xs text-gray-400">No districts defined in this state yet.</div>
                    @endforelse
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-400">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-medium text-gray-500">No jurisdictions configured</p>
                <p class="text-xs text-gray-400 mt-1">Click "Add Location" or "Import CSV" above to populate state and district boundaries</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    {{-- MODAL: Add/Edit Location --}}
    @if($showFormModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">{{ $locationId ? 'Edit' : 'Add' }} Administrative Location</h3>
                </div>
                <button type="button" wire:click="cancel" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Hierarchy Level</label>
                        <select wire:model.live="type" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="state">State / UT</option>
                            <option value="district">District</option>
                            <option value="zone">Zone</option>
                            <option value="panchayat">Panchayat</option>
                            <option value="village">Village</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Location Name <span class="text-rose-500">*</span></label>
                        <input wire:model="name" type="text" placeholder="e.g. Rajouri" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('name') border-rose-300 ring-2 ring-rose-100 @enderror">
                        @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Location Code <span class="text-xs text-gray-400 font-normal lowercase">(optional)</span></label>
                        <input wire:model="code" type="text" placeholder="Auto-generated if left blank" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-mono text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>

                    @if($type !== 'state')
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Parent Jurisdiction <span class="text-rose-500">*</span></label>
                        <select wire:model="parent_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('parent_id') border-rose-300 ring-2 ring-rose-100 @enderror">
                            <option value="">— Select Parent Location —</option>
                            @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Location
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL: Import CSV / Excel --}}
    @if($showImportModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Bulk Import Locations</h3>
                </div>
                <button type="button" wire:click="toggleImport" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="import">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Select File (Excel .xlsx / .csv)</label>
                        <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-xl p-1 bg-gray-50">
                        @error('importFile') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50 space-y-2">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Required Column Structure</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-[11px] text-left border border-gray-200 bg-white rounded-lg">
                                <thead class="bg-gray-100 text-gray-600">
                                    <tr>
                                        <th class="p-2 border-b">State</th>
                                        <th class="p-2 border-b">District</th>
                                        <th class="p-2 border-b">Zone</th>
                                        <th class="p-2 border-b">Panchayat</th>
                                        <th class="p-2 border-b">Village</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <tr>
                                        <td class="p-2 border-b">Jammu &amp; Kashmir</td>
                                        <td class="p-2 border-b">Rajouri</td>
                                        <td class="p-2 border-b">North Zone</td>
                                        <td class="p-2 border-b">Panchayat A</td>
                                        <td class="p-2 border-b">Village 1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[11px] text-gray-500">Each row represents one village. Parents are created automatically if missing.</p>
                        <div class="pt-2">
                            <a href="{{ route('admin.locations.template') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download Excel Template
                            </a>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                    <button type="button" wire:click="toggleImport" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]" wire:loading.attr="disabled" wire:target="import">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span>Start Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL: Delete Confirmation --}}
    @if($deletingId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Delete Location</h3>
                <p class="text-xs text-gray-500 mt-1">Are you sure you want to delete <strong class="text-gray-900">{{ $deletingName }}</strong>? All nested sub-locations and linked records may be affected.</p>
            </div>
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2.5">
                <button type="button" wire:click="cancelDelete" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                    Cancel
                </button>
                <button type="button" wire:click="delete" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition">
                    Delete Location
                </button>
            </div>
        </div>
    </div>
    @endif
</div>