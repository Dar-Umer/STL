<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Recommendation Rules
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Define automated agronomic advice triggered by soil test results and crop conditions</p>
        </div>
        @if(!$showForm)
        <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Rule
        </button>
        @endif
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($showForm)
    {{-- Create / Edit Form Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-base font-bold text-gray-900">Configure Rule Details</h2>
            </div>
            <button type="button" wire:click="cancel" class="text-xs font-semibold text-gray-500 hover:text-gray-700">Cancel</button>
        </div>

        <form wire:submit="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Rule Name <span class="text-rose-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="e.g. Acidic Soil Lime Application" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('name') border-rose-300 ring-2 ring-rose-100 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Priority</label>
                    <input wire:model="priority" type="number" min="0" placeholder="0" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('priority') border-rose-300 ring-2 ring-rose-100 @enderror">
                    @error('priority') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-3 flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Rule Active</span>
                    </label>
                </div>

                <div class="md:col-span-12">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Description (optional)</label>
                    <textarea wire:model="description" rows="2" placeholder="Brief context or internal purpose of this rule..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"></textarea>
                    @error('description') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Conditions Section --}}
            <div class="border-t border-gray-100 pt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Conditions</h3>
                        <p class="text-xs text-gray-500">All added conditions must match simultaneously for this recommendation to trigger</p>
                    </div>
                    <button type="button" wire:click="addCondition" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Condition
                    </button>
                </div>
                @error('conditions') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror

                <div class="space-y-3">
                    @foreach($conditions as $index => $cond)
                    <div class="bg-gray-50/70 border border-gray-200/70 rounded-2xl p-4 transition hover:border-gray-300">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200/50">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100/60 text-emerald-800">
                                Condition #{{ $index + 1 }}
                            </span>
                            <button type="button" wire:click="removeCondition({{ $index }})" class="p-1 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Remove condition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Type</label>
                                <select wire:model.live="conditions.{{ $index }}.type" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                                    <option value="parameter_value">Parameter Value</option>
                                    <option value="parameter_rating">Parameter Rating</option>
                                    <option value="plant_age">Plant Age</option>
                                    <option value="crop_is">Crop Is</option>
                                </select>
                            </div>

                            @if(in_array($cond['type'] ?? '', ['parameter_value', 'parameter_rating']))
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Parameter</label>
                                <select wire:model="conditions.{{ $index }}.parameter_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                                    <option value="">Select Parameter</option>
                                    @foreach($parameters as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->unit }})</option>
                                    @endforeach
                                </select>
                                @error('conditions.'.$index.'.parameter_id') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            @endif

                            @if(in_array($cond['type'] ?? '', ['parameter_value', 'plant_age']))
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Operator</label>
                                <select wire:model="conditions.{{ $index }}.operator" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                                    <option value="gte">&ge; (greater or equal)</option>
                                    <option value="gt">&gt; (greater than)</option>
                                    <option value="lte">&le; (less or equal)</option>
                                    <option value="lt">&lt; (less than)</option>
                                    <option value="eq">= (equal)</option>
                                    <option value="between">Between</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Value</label>
                                <input wire:model="conditions.{{ $index }}.value" type="number" step="0.01" placeholder="e.g. 5.5" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                                @error('conditions.'.$index.'.value') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            @if(($cond['operator'] ?? '') === 'between')
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Max Value</label>
                                <input wire:model="conditions.{{ $index }}.value_max" type="number" step="0.01" placeholder="Max limit" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                            </div>
                            @endif
                            @endif

                            @if(($cond['type'] ?? '') === 'parameter_rating')
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Rating</label>
                                <select wire:model="conditions.{{ $index }}.rating" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                                @error('conditions.'.$index.'.rating') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            @endif

                            @if(($cond['type'] ?? '') === 'crop_is')
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Crop</label>
                                <select wire:model="conditions.{{ $index }}.crop_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100">
                                    <option value="">Select Crop</option>
                                    @foreach($crops as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('conditions.'.$index.'.crop_id') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recommendation Content --}}
            <div class="border-t border-gray-100 pt-6">
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Recommendation Text <span class="text-rose-500">*</span></label>
                <textarea wire:model="recommendation" rows="4" placeholder="Advise the farmer on treatment, fertilizer dosage, or corrective agronomic practices when conditions trigger..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('recommendation') border-rose-300 ring-2 ring-rose-100 @enderror"></textarea>
                @error('recommendation') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-gray-100 pt-5 flex items-center justify-end gap-3">
                <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Rule
                </button>
            </div>
        </form>
    </div>
    @else
    {{-- Rules Catalog Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Defined Recommendation Rules</h2>
            <span class="text-xs text-gray-500 font-medium">{{ $rules->total() }} rules total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Priority</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Name &amp; Description</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Conditions</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Recommendation</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rules as $rule)
                    <tr class="hover:bg-gray-50/70 transition group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 font-bold text-xs text-gray-700">
                                {{ $rule->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900 block">{{ $rule->name }}</span>
                            @if($rule->description)
                            <span class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $rule->description }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ count($rule->conditions ?? []) }} condition(s)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-600 line-clamp-2 max-w-sm">{{ $rule->recommendation }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleActive({{ $rule->id }})" class="relative inline-flex items-center cursor-pointer">
                                <span class="sr-only">Toggle Active</span>
                                <div class="w-9 h-5 {{ $rule->is_active ? 'bg-emerald-600' : 'bg-gray-300' }} rounded-full transition-colors relative">
                                    <div class="w-3.5 h-3.5 bg-white rounded-full transition-transform absolute top-[3px] {{ $rule->is_active ? 'left-[19px]' : 'left-[3px]' }}"></div>
                                </div>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <button wire:click="edit({{ $rule->id }})" class="p-2 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Edit Rule">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="delete({{ $rule->id }})" onclick="return confirm('Delete this recommendation rule?')" class="p-2 rounded-xl text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Rule">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="font-medium text-gray-500">No recommendation rules found</p>
                            <p class="text-xs text-gray-400 mt-1">Click "New Rule" to set up your first agronomic decision rule</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rules->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $rules->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
