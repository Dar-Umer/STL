<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>Soil Test Parameters</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Configure test parameters, rating thresholds, indicator color palettes, and laboratory assignments.</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="$toggle('showInactive')" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl border transition {{ $showInactive ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                <i class="fas {{ $showInactive ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                <span>{{ $showInactive ? 'Hiding Inactive' : 'Show Inactive' }}</span>
            </button>
            @if(!$showForm)
                <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Parameter
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

    {{-- Form Card --}}
    @if($showForm)
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fas {{ $parameterId ? 'fa-edit' : 'fa-plus' }}"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $parameterId ? 'Edit Parameter: ' . $name : 'Define New Test Parameter' }}</h3>
                    <p class="text-xs text-gray-500">Configure measurement unit, range, rating labels and associated laboratories</p>
                </div>
            </div>
            <button type="button" wire:click="cancel" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition" title="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        1. Basic Specifications
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Parameter Name <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="name" type="text" placeholder="e.g. Available Nitrogen (N)"
                                   class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Measurement Unit
                            </label>
                            <input wire:model="unit" type="text" placeholder="e.g. kg/ha, ppm, %"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Display Order
                            </label>
                            <input wire:model="order_column" type="number" placeholder="1"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Testing Method / Technique
                            </label>
                            <input wire:model="method" type="text" placeholder="e.g. Alkaline Permanganate Method"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Min Range (Scale)
                            </label>
                            <input wire:model="min_range" type="number" step="0.01" placeholder="0"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Max Range (Scale)
                            </label>
                            <input wire:model="max_range" type="number" step="0.01" placeholder="1000"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>
                    </div>
                </div>

                {{-- Rating Thresholds & Labels --}}
                <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-200">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="fas fa-sliders-h text-emerald-600"></i>
                        <span>Rating Thresholds, Labels & Indicator Colors</span>
                    </h4>
                    <p class="text-xs text-gray-500 mb-4">Results evaluated within these cutoffs automatically receive corresponding ratings and alert colors on test reports.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        {{-- Low --}}
                        <div class="p-4 rounded-xl bg-white border border-gray-200/80 space-y-3 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Low / Deficient</span>
                                <input wire:model="rating_color_low" type="color" class="w-7 h-7 rounded border-0 cursor-pointer p-0">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase">Max Threshold (&le;)</label>
                                <input wire:model="rating_low_max" type="number" step="0.01" placeholder="e.g. 280"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase">Label Text</label>
                                <input wire:model="rating_label_low" type="text" placeholder="Deficient"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                        </div>

                        {{-- Medium --}}
                        <div class="p-4 rounded-xl bg-white border border-gray-200/80 space-y-3 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Medium / Optimal</span>
                                <input wire:model="rating_color_medium" type="color" class="w-7 h-7 rounded border-0 cursor-pointer p-0">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase">Max Threshold (&le;)</label>
                                <input wire:model="rating_medium_max" type="number" step="0.01" placeholder="e.g. 560"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase">Label Text</label>
                                <input wire:model="rating_label_medium" type="text" placeholder="Sufficient"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                        </div>

                        {{-- High --}}
                        <div class="p-4 rounded-xl bg-white border border-gray-200/80 space-y-3 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">High / Excess</span>
                                <input wire:model="rating_color_high" type="color" class="w-7 h-7 rounded border-0 cursor-pointer p-0">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase">Min Threshold (&ge;)</label>
                                <input wire:model="rating_high_min" type="number" step="0.01" placeholder="e.g. 560"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase">Label Text</label>
                                <input wire:model="rating_label_high" type="text" placeholder="High"
                                       class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        2. Lab Assignment & Operational Status
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Assign to Testing Laboratories
                            </label>
                            <select wire:model="selectedLabs" multiple class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition" style="min-height: 100px;">
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}" class="py-1">{{ $lab->name }} ({{ $lab->code }})</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Leave empty to allow testing at all laboratories.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Parameter Status
                            </label>
                            <select wire:model="is_active" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="1">Active (Available for testing & reporting)</option>
                                <option value="0">Inactive (Hidden from testing queues)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $parameterId ? 'Update Parameter' : 'Save Parameter' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Parameters Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-bold text-gray-900 text-sm">Parameter Catalog</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    {{ count($parameters) }} total
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-16">#</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Parameter</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Expected Range</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Rating Classification</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Assigned Labs</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($parameters as $param)
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-6 py-4 font-mono text-xs text-gray-400 font-bold">
                                {{ $param->order_column }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 block group-hover:text-emerald-700 transition">{{ $param->name }}</span>
                                @if($param->method)
                                    <span class="text-xs text-gray-400 block">{{ $param->method }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-medium bg-gray-100 text-gray-700">
                                    {{ $param->unit ?: '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-600">
                                {{ $param->min_range }} &ndash; {{ $param->max_range }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold text-white shadow-2xs" style="background-color: {{ $param->getRatingColor('low') }};">
                                        {{ $param->getRatingLabel('low') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold text-white shadow-2xs" style="background-color: {{ $param->getRatingColor('medium') }};">
                                        {{ $param->getRatingLabel('medium') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold text-white shadow-2xs" style="background-color: {{ $param->getRatingColor('high') }};">
                                        {{ $param->getRatingLabel('high') }}
                                    </span>
                                </div>
                                <span class="text-[11px] text-gray-400 font-mono block">
                                    L&le;{{ $param->rating_low_max }} &bull; M&le;{{ $param->rating_medium_max }} &bull; H&ge;{{ $param->rating_high_min }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($param->relationLoaded('labs') && $param->labs->count())
                                    <div class="flex flex-wrap gap-1 max-w-[200px]">
                                        @foreach($param->labs as $lab)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $lab->code }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">All Labs</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($param->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button wire:click="edit({{ $param->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 rounded-lg shadow-xs transition active:scale-[0.98]" title="Edit Parameter">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $param->id }})" wire:confirm="Delete this parameter? Existing results will be preserved." class="inline-flex items-center p-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 rounded-lg transition active:scale-[0.98]" title="Delete Parameter">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900">No parameters defined</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Define your first soil parameter using the "Add Parameter" button above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>