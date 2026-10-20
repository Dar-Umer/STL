<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Audit Trail &amp; Activity Log
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Immutable chronological compliance log tracking mutations, logins, and system alterations</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportCsv" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-xs rounded-xl shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </button>
            <button wire:click="clearLogs" wire:confirm="Are you sure you want to permanently clear ALL audit logs? This action is irreversible." class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-700 font-medium text-xs rounded-xl shadow-xs transition">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Clear Logs
            </button>
        </div>
    </div>

    {{-- Event Count Pills Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-7 gap-3">
        <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-xs text-center">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total</p>
            <p class="text-lg font-bold text-gray-900 mt-0.5">{{ number_format($logs->total()) }}</p>
        </div>
        @foreach($eventCounts as $event => $count)
        <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-xs text-center">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ ucfirst(str_replace('_', ' ', $event)) }}</p>
            <p class="text-lg font-bold mt-0.5 @if($event === 'created') text-emerald-600 @elseif($event === 'updated') text-amber-600 @elseif($event === 'deleted') text-rose-600 @elseif($event === 'login' || $event === 'logout') text-blue-600 @else text-gray-700 @endif">
                {{ number_format($count) }}
            </p>
        </div>
        @endforeach
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search user, IP, or record..." class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
            </div>
            <div class="md:col-span-2">
                <select wire:model.live="eventFilter" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
                    <option value="">All Action Events</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="settings_changed">Settings Changed</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <select wire:model.live="typeFilter" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
                    <option value="">All Entity Models</option>
                    @foreach($types as $type)
                    <option value="{{ $type }}">{{ class_basename($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
            </div>
            <div class="md:col-span-2">
                <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
            </div>
        </div>
    </div>

    {{-- Audit Log Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Historical Audit Stream</h2>
            <span class="text-xs text-gray-400 font-medium">{{ $logs->total() }} recorded entries</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold uppercase text-gray-500 w-8"></th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Timestamp</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">User / Actor</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Action Event</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Target Entity</th>
                        <th class="px-5 py-3 font-semibold uppercase text-gray-500">Origin IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr wire:click="toggleExpand({{ $log->id }})" class="hover:bg-gray-50/80 transition cursor-pointer {{ $expandedId === $log->id ? 'bg-emerald-50/20' : '' }}">
                        <td class="px-4 py-3.5 text-center text-gray-400">
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $expandedId === $log->id ? 'rotate-90 text-emerald-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 font-medium whitespace-nowrap">
                            {{ $log->created_at?->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px]">
                                    {{ substr($log->user?->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="font-bold text-gray-900">{{ $log->user?->name ?? 'System Automated' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($log->event === 'created')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Created
                            </span>
                            @elseif($log->event === 'updated')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Updated
                            </span>
                            @elseif($log->event === 'deleted')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Deleted
                            </span>
                            @elseif($log->event === 'login')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Login
                            </span>
                            @elseif($log->event === 'logout')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">
                                Logout
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $log->event)) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-700">
                            {{ $log->auditable_label }}
                        </td>
                        <td class="px-5 py-3.5 font-mono text-[11px] text-gray-500">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                    </tr>

                    {{-- Expanded Diff Drawer --}}
                    @if($expandedId === $log->id)
                    <tr class="bg-gray-50/70">
                        <td colspan="6" class="p-0">
                            <div class="px-8 py-5 border-y border-gray-200/60 space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($log->old_values)
                                    <div class="bg-white border border-rose-100 rounded-xl p-3 shadow-2xs">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-rose-700 uppercase tracking-wider mb-2 pb-1 border-b border-rose-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                            Prior State (Before)
                                        </div>
                                        <div class="space-y-1 font-mono text-[11px]">
                                            @foreach($log->old_values as $key => $val)
                                            <div class="flex items-start justify-between gap-2 py-0.5">
                                                <span class="text-gray-500">{{ $key }}:</span>
                                                <span class="text-rose-600 font-semibold break-all text-right">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($log->new_values)
                                    <div class="bg-white border border-emerald-100 rounded-xl p-3 shadow-2xs">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2 pb-1 border-b border-emerald-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            New State (After)
                                        </div>
                                        <div class="space-y-1 font-mono text-[11px]">
                                            @foreach($log->new_values as $key => $val)
                                            <div class="flex items-start justify-between gap-2 py-0.5">
                                                <span class="text-gray-500">{{ $key }}:</span>
                                                <span class="text-emerald-700 font-semibold break-all text-right">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                @if($log->url)
                                <div class="text-[11px] text-gray-400 font-mono flex items-center gap-1.5 pt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    Request URL: <span class="text-gray-600">{{ $log->url }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="font-medium text-gray-500">No audit events found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
