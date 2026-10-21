<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>Sample Collection Requests</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Public farmer collection requests, field official assignments, and real-time intake tracking.</p>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                {{ \App\Models\SampleRequest::where('status', 'pending')->count() }} Pending Requests
            </span>
        </div>
    </div>

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

    {{-- Filters & Search Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="w-full sm:max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by request ID, farmer name, phone..."
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <select wire:model.live="statusFilter" class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-xs font-medium text-gray-700 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="assigned">Assigned</option>
                <option value="collected">Collected</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200 whitespace-nowrap">
                {{ $requests->total() }} requests
            </span>
        </div>
    </div>

    {{-- Requests Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Request ID</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Farmer Details</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Contact</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Zone / District</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Assigned Official</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Submitted</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                        @php
                            $statusStyle = match($req->status) {
                                'collected' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'assigned' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'cancelled' => 'bg-gray-100 text-gray-600 border-gray-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                            $dotColor = match($req->status) {
                                'collected' => 'bg-emerald-500',
                                'assigned' => 'bg-indigo-500',
                                'cancelled' => 'bg-gray-400',
                                default => 'bg-amber-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ $req->request_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 block group-hover:text-emerald-700 transition">{{ $req->farmer_name }}</span>
                                @if($req->aadhar_no)
                                    <span class="text-[11px] text-gray-500 font-mono block mt-0.5">UID: {{ $req->aadhar_no }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono font-medium text-gray-700">{{ $req->farmer_phone }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div class="font-medium text-gray-900">{{ $req->zone->name ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusStyle }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    {{ ucfirst($req->status) }}
                                </span>
                                @if($req->soilSample)
                                    <a href="{{ route('lab.testing', $req->soilSample->id) }}" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono text-indigo-700 bg-indigo-50 border border-indigo-100 ml-1" title="View Associated Sample">
                                        #{{ $req->soilSample->sample_code }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($req->fieldOfficial)
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold flex items-center justify-center">
                                            {{ strtoupper(substr($req->fieldOfficial->name, 0, 1)) }}
                                        </span>
                                        <span class="font-medium text-gray-900">{{ $req->fieldOfficial->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-400">
                                {{ $req->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(in_array($req->status, ['pending', 'assigned']))
                                <div class="flex items-center justify-end gap-1.5" x-data="{ open: false }">
                                    <div class="relative">
                                        <button @click="open = !open" @click.outside="open = false" type="button"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-xs transition active:scale-[0.98]">
                                            <i class="fas fa-user-plus text-[10px] text-gray-400"></i>
                                            <span>Assign</span>
                                            <svg class="w-3 h-3 text-gray-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div x-show="open" x-cloak style="display: none;"
                                             class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50 text-left">
                                            <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Select Field Official</div>
                                            @foreach($fieldOfficials as $fo)
                                                <button type="button" @click="open = false" wire:click="assignOfficer({{ $req->id }}, {{ $fo->id }})"
                                                        class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 hover:text-emerald-700 transition">
                                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold flex items-center justify-center">
                                                        {{ strtoupper(substr($fo->name, 0, 1)) }}
                                                    </span>
                                                    <span class="truncate">{{ $fo->name }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button wire:click="cancelRequest({{ $req->id }})" wire:confirm="Cancel this collection request?"
                                            class="inline-flex items-center p-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 rounded-lg transition" title="Cancel Request">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900">No sample requests found</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Public collection requests submitted by farmers will appear in this list.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
