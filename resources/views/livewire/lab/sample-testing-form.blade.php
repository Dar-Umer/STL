<div class="space-y-6">
    <script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openPdf', ({ url }) => {
            window.open(url, '_blank');
        });
    });
    </script>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-emerald-500/20">
                <i class="fas fa-vial"></i>
            </span>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Test Workbench</h2>
                    <span class="font-mono font-bold text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                        {{ $sample->sample_code }}
                    </span>
                    @if($sample->lab_number)
                        <span class="font-mono font-bold text-xs bg-emerald-600 text-white px-2.5 py-0.5 rounded-full shadow-2xs">
                            <i class="fas fa-tag text-[10px] mr-1"></i> Lab Code: {{ $sample->lab_number }}
                        </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Input certified chemical test results and review agronomic recommendations</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('lab.evaluations.selected.pdf', ['ids' => $sample->id]) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-print"></i>
                <span>Bench Worksheet</span>
            </a>
            <a href="{{ route('lab.testing-list') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Testing Queue</span>
            </a>
        </div>
    </div>

    {{-- Sample Metadata Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                    <i class="fas fa-id-card"></i>
                </span>
                <h3 class="text-sm font-bold text-gray-900">Sample Passport Details</h3>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                {{ $sample->status->label() }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="bg-gray-50/70 p-3 rounded-xl border border-gray-100">
                <span class="text-gray-400 block uppercase font-semibold text-[10px] tracking-wider">Beneficiary</span>
                <span class="font-bold text-gray-900 text-sm mt-0.5 block">{{ $sample->farmer->name ?? '—' }}</span>
                @if($sample->farmer?->phone)
                    <span class="text-gray-400 font-mono text-[11px]">{{ $sample->farmer->phone }}</span>
                @endif
            </div>

            <div class="bg-gray-50/70 p-3 rounded-xl border border-gray-100">
                <span class="text-gray-400 block uppercase font-semibold text-[10px] tracking-wider">Field Plot / Crop</span>
                <span class="font-bold text-gray-900 text-sm mt-0.5 block">{{ $sample->plot->plot_name ?? '—' }}</span>
                @if($sample->plot?->crop)
                    <span class="text-emerald-700 font-medium text-[11px]">{{ $sample->plot->crop->name }}</span>
                @endif
            </div>

            <div class="bg-gray-50/70 p-3 rounded-xl border border-gray-100">
                <span class="text-gray-400 block uppercase font-semibold text-[10px] tracking-wider">Field Collection</span>
                <span class="font-bold text-gray-900 text-sm mt-0.5 block font-mono">
                    {{ $sample->collected_at?->format('d M Y') ?? '—' }}
                </span>
                <span class="text-gray-400 text-[11px]">{{ $sample->collected_at?->format('h:i A') ?? '' }}</span>
            </div>

            <div class="bg-gray-50/70 p-3 rounded-xl border border-gray-100">
                <span class="text-gray-400 block uppercase font-semibold text-[10px] tracking-wider">Lab Check-in</span>
                <span class="font-bold text-gray-900 text-sm mt-0.5 block font-mono">
                    {{ $sample->received_at?->format('d M Y') ?? '—' }}
                </span>
                <span class="text-gray-400 text-[11px]">{{ $sample->received_at?->format('h:i A') ?? '' }}</span>
            </div>
        </div>
    </div>

    {{-- Parameter Testing Form --}}
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-list-check"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Chemical Parameter Scorecard</h3>
                        <p class="text-[11px] text-gray-400">Values trigger automatic Low/Medium/High rating brackets based on configured thresholds</p>
                    </div>
                </div>
                <span class="text-xs text-gray-400 font-semibold">
                    {{ $parameters->count() }} Parameters Configured
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-12">#</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Parameter</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Typical Range</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500" style="min-width: 170px;">Observed Value</th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500" style="min-width: 180px;">Rating Bracket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($parameters as $param)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4 text-xs font-mono text-gray-400">
                                    {{ $param->order_column ?? $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900 block">{{ $param->name }}</span>
                                    @if($param->method)
                                        <span class="text-[11px] text-gray-400">{{ $param->method }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                    {{ $param->unit ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($param->min_range !== null || $param->max_range !== null)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono bg-gray-100 text-gray-700">
                                            {{ $param->range }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <input wire:model.live="values.{{ $param->id }}" type="number" step="0.01"
                                               placeholder="0.00"
                                               @if($param->min_range !== null) min="{{ $param->min_range }}" @endif
                                               @if($param->max_range !== null) max="{{ $param->max_range }}" @endif
                                               class="w-36 rounded-xl border {{ isset($rangeErrors[$param->id]) ? 'border-amber-300 bg-amber-50/30' : 'border-gray-200 bg-gray-50' }} px-3 py-2 text-sm font-semibold font-mono text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        @if(isset($rangeErrors[$param->id]))
                                            <span class="block text-[10px] text-amber-600 font-semibold mt-1">
                                                <i class="fas fa-triangle-exclamation mr-1"></i>{{ $rangeErrors[$param->id] }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <select wire:model="ratings.{{ $param->id }}"
                                                class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                            <option value="">— Auto Rating —</option>
                                            <option value="Low">{{ $param->getRatingLabel('low') }}</option>
                                            <option value="Medium">{{ $param->getRatingLabel('medium') }}</option>
                                            <option value="High">{{ $param->getRatingLabel('high') }}</option>
                                        </select>
                                        @if($ratings[$param->id] ?? null)
                                            @php $tier = strtolower($ratings[$param->id]); @endphp
                                            <span class="w-3.5 h-3.5 rounded-full flex-shrink-0 shadow-xs border border-black/10"
                                                  style="background-color: {{ $param->getRatingColor($tier) }};"
                                                  title="{{ $ratings[$param->id] }}"></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recommendations Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Agronomic Recommendations</h3>
                        <p class="text-[11px] text-gray-400">Printed on the official Soil Health Card for farmer fertilizer application</p>
                    </div>
                </div>

                <button type="button" wire:click="generateRecommendations"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl shadow-xs transition active:scale-[0.98]">
                    <i class="fas fa-magic text-xs"></i>
                    <span>Auto-Generate with Rules Engine</span>
                </button>
            </div>

            <textarea wire:model="recommendations" rows="4"
                      placeholder="Enter soil amendment and fertilizer dosage instructions..."
                      class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"></textarea>
        </div>

        {{-- Bottom Action Footer --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('samples.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                Cancel
            </a>
            <button type="submit"
                    onclick="return confirm('This will finalize test analysis and mark the sample as REPORTED. Continue?');"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Commit & Save Test Results</span>
            </button>
        </div>
    </form>
</div>
