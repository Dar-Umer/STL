<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>Classification Dropdowns</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Manage standardized master options for soil textures, natural colors, and agricultural crop varieties.</p>
        </div>
        <div>
            @if(!$showForm)
                <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Option
                </button>
            @endif
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

    {{-- Tabs --}}
    <div class="flex items-center gap-2 p-1.5 bg-gray-100 rounded-2xl w-fit">
        <button wire:click.prevent="$set('activeTab', 'soil_types')" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeTab === 'soil_types' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <i class="fas fa-layer-group mr-1.5 text-emerald-600"></i> Soil Types
        </button>
        <button wire:click.prevent="$set('activeTab', 'soil_colors')" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeTab === 'soil_colors' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <i class="fas fa-palette mr-1.5 text-amber-600"></i> Soil Colors
        </button>
        <button wire:click.prevent="$set('activeTab', 'crops')" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeTab === 'crops' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
            <i class="fas fa-seedling mr-1.5 text-green-600"></i> Crops
        </button>
    </div>

    {{-- Form Card --}}
    @if($showForm)
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fas {{ $itemId ? 'fa-edit' : 'fa-plus' }}"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $itemId ? 'Edit Option' : 'Add Option to ' . ucwords(str_replace('_', ' ', $activeTab)) }}</h3>
                    <p class="text-xs text-gray-500">Master value for dropdown selection in field sample collection</p>
                </div>
            </div>
            <button type="button" wire:click="cancel" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition" title="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Option Name / Value <span class="text-rose-500">*</span>
                    </label>
                    <input wire:model="name" type="text" placeholder="Enter option name (e.g. Clay Loam, Apple, Black)..."
                           class="w-full max-w-lg rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Save Option</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @php
        $items = match($activeTab) {
            'soil_types' => $soilTypes,
            'soil_colors' => $soilColors,
            'crops' => $crops,
        };
    @endphp

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-bold text-gray-900 text-sm">{{ ucwords(str_replace('_', ' ', $activeTab)) }}</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    {{ count($items) }} options configured
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-16">#</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Option Value</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $i => $item)
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-6 py-4 font-mono text-xs text-gray-400 font-bold">
                                {{ $i + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 group-hover:text-emerald-700 transition">{{ $item->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="edit({{ $item->id }}, '{{ $activeTab }}')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 rounded-lg shadow-xs transition active:scale-[0.98]">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                    <i class="fas fa-list"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900">No dropdown options defined</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Click "Add Option" above to create your first item in this category.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
