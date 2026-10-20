<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.inventory') }}" class="p-2 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Back to Inventory">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                    Consumption Rules — {{ $chemical->name }}
                </h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 font-medium ml-11">
                Chemical SKU: <span class="font-mono text-gray-700">{{ $chemical->sku }}</span> &middot; Base Unit: <span class="font-semibold text-emerald-700">{{ $chemical->unit }}</span>
            </p>
        </div>
        <button wire:click="save" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Consumption Rules
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

    {{-- Rules Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div>
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Per-Test Chemical Consumption Rates</h2>
                <p class="text-xs text-gray-400 mt-0.5">When a sample report is approved, these quantities are automatically deducted from this reagent's inventory stock.</p>
            </div>
            <span class="text-xs text-gray-500 font-medium">{{ count($parameters) }} parameters</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-20">Auto Deduct</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Test Parameter</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Test Unit</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-80">Reagent Consumed per Test ({{ $chemical->unit }})</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($parameters as $param)
                    @php $isEnabled = $rules[$param->id]['enabled'] ?? false; @endphp
                    <tr class="hover:bg-gray-50/70 transition group {{ $isEnabled ? 'bg-emerald-50/20' : '' }}">
                        <td class="px-6 py-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="rules.{{ $param->id }}.enabled" id="param-{{ $param->id }}" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <label for="param-{{ $param->id }}" class="font-bold text-gray-900 cursor-pointer select-none">
                                {{ $param->name }}
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $param->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="relative w-48">
                                <input type="number" step="0.01" min="0" wire:model="rules.{{ $param->id }}.amount" placeholder="0.00" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition disabled:opacity-40 disabled:cursor-not-allowed" @if(!$isEnabled) disabled @endif>
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-semibold text-gray-400">
                                    {{ $chemical->unit }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            No laboratory test parameters configured yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
            <button wire:click="save" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Consumption Rules
            </button>
        </div>
    </div>
</div>
