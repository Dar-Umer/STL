<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Lab Reagents &amp; Inventory
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Track chemical stock balances, automated consumption per test, and replenish supplies</p>
        </div>
        <button wire:click="openAdd" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Chemical
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search chemicals by name or SKU..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
            </div>

            @can('manage-users')
            <div class="w-full sm:w-64">
                <select wire:model.live="labId" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    <option value="">All Laboratory Facilities</option>
                    @foreach($labs as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                    @endforeach
                </select>
            </div>
            @endcan
        </div>
    </div>

    {{-- Chemicals Catalog Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Chemical &amp; Reagent Stock</h2>
            <span class="text-xs text-gray-500 font-medium">{{ $chemicals->total() }} items registered</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Chemical Name</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">SKU Code</th>
                        @can('manage-users')
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Assigned Lab</th>
                        @endcan
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Stock Balance</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($chemicals as $c)
                    @php $status = $c->stockStatus(); @endphp
                    <tr class="hover:bg-gray-50/70 transition group">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900 block">{{ $c->name }}</span>
                            @if(!$c->is_active)
                            <span class="text-[11px] text-gray-400 font-medium">Inactive in lab</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg font-medium">
                                {{ $c->sku }}
                            </span>
                        </td>
                        @can('manage-users')
                        <td class="px-6 py-4 text-xs font-medium text-gray-700">
                            {{ $c->lab?->name ?? '—' }}
                        </td>
                        @endcan
                        <td class="px-6 py-4 text-xs font-medium text-gray-600">
                            {{ $c->unit }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-baseline gap-1">
                                <span class="font-bold text-gray-900 text-base">{{ number_format((float) $c->current_stock, 2) }}</span>
                                @if($c->minimum_stock)
                                <span class="text-xs text-gray-400">/ min {{ number_format((float) $c->minimum_stock, 2) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($status === 'normal')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                In Stock
                            </span>
                            @elseif($status === 'low')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Low Stock
                            </span>
                            @elseif($status === 'critical')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Critical
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                {{ ucfirst($status) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <button wire:click="openStockForm({{ $c->id }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" title="Add Stock Inward">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Stock
                                </button>
                                <button wire:click="openHistory({{ $c->id }})" class="p-1.5 rounded-xl text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition" title="Stock Movement History">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <a href="{{ route('admin.inventory.consumption', $c->id) }}" class="p-1.5 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Consumption Rules per Test">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                </a>
                                <button wire:click="edit({{ $c->id }})" class="p-1.5 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Edit Chemical">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="toggleActive({{ $c->id }})" class="p-1.5 rounded-xl text-gray-500 hover:{{ $c->is_active ? 'text-rose-600 hover:bg-rose-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition" title="{{ $c->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($c->is_active)
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <p class="font-medium text-gray-500">No chemicals found</p>
                            <p class="text-xs text-gray-400 mt-1">Add laboratory reagents to start monitoring stock balances</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($chemicals->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $chemicals->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL: Add / Edit Chemical --}}
    @if($showAddForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">{{ $editId ? 'Edit' : 'Add' }} Chemical Reagent</h3>
                </div>
                <button type="button" wire:click="$set('showAddForm', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Chemical Name <span class="text-rose-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="e.g. Potassium Chloride (KCl)" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('name') border-rose-300 ring-2 ring-rose-100 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">SKU / Code <span class="text-rose-500">*</span></label>
                        <input wire:model="sku" type="text" placeholder="e.g. CHEM-KCL-01" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-mono text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('sku') border-rose-300 ring-2 ring-rose-100 @enderror">
                        @error('sku') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Unit <span class="text-rose-500">*</span></label>
                        <input wire:model="unit" type="text" placeholder="ml, g, kg, L..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('unit') border-rose-300 ring-2 ring-rose-100 @enderror">
                        @error('unit') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Minimum Stock Threshold (optional)</label>
                    <input wire:model="minimumStock" type="number" step="0.01" min="0" placeholder="e.g. 100.00" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('minimumStock') border-rose-300 ring-2 ring-rose-100 @enderror">
                    <p class="text-[11px] text-gray-400 mt-1">Triggers low-stock alerts when current stock falls below this level</p>
                    @error('minimumStock') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                @can('manage-users')
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Assigned Laboratory</label>
                    <select wire:model="selectedLab" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('selectedLab') border-rose-300 ring-2 ring-rose-100 @enderror">
                        <option value="">Select Laboratory</option>
                        @foreach($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedLab') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                @endcan
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                <button type="button" wire:click="$set('showAddForm', false)" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                    Cancel
                </button>
                <button type="button" wire:click="save" class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $editId ? 'Update Chemical' : 'Save Chemical' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Add Stock Quantity --}}
    @if($showStockForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Add Inward Stock</h3>
                </div>
                <button type="button" wire:click="$set('showStockForm', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Inward Quantity <span class="text-rose-500">*</span></label>
                    <input wire:model="stockQuantity" type="number" step="0.01" min="0.01" placeholder="e.g. 50.00" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('stockQuantity') border-rose-300 ring-2 ring-rose-100 @enderror">
                    @error('stockQuantity') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Notes / Supplier Reference (optional)</label>
                    <textarea wire:model="stockNotes" rows="3" placeholder="Batch number, invoice reference, or received notes..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"></textarea>
                    @error('stockNotes') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                <button type="button" wire:click="$set('showStockForm', false)" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                    Cancel
                </button>
                <button type="button" wire:click="addStock" class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Confirm Stock Inward
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Stock History Ledger --}}
    @if($showHistory && $historyChemical)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Stock Movements — {{ $historyChemical->name }}</h3>
                        <p class="text-xs text-gray-400">SKU: {{ $historyChemical->sku }} &middot; Current: {{ number_format((float) $historyChemical->current_stock, 2) }} {{ $historyChemical->unit }}</p>
                    </div>
                </div>
                <button type="button" wire:click="closeHistory" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Date &amp; Time</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Type</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Quantity</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Before</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">After</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Notes</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Recorded By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($historyChemical->stockMovements as $m)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-4 py-3 text-xs text-gray-600">
                                {{ $m->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($m->type === 'in')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    + IN
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    - OUT
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-bold {{ $m->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $m->type === 'in' ? '+' : '-' }}{{ number_format((float) $m->quantity, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-xs text-gray-500 font-mono">
                                {{ number_format((float) $m->balance_before, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-xs font-bold text-gray-900 font-mono">
                                {{ number_format((float) $m->balance_after, 2) }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $m->reference_type ? class_basename($m->reference_type) . ' #' . $m->reference_id : 'Manual' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate">
                                {{ $m->notes ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-700">
                                {{ $m->creator?->name ?? '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                No stock movement records found for this chemical.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                <button type="button" wire:click="closeHistory" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
