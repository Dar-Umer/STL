<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Sample Bag QR Codes
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Generate printable QR code batches to affix on physical soil sample collection bags</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left: Generator Form --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Batch Generator</h2>
                    </div>
                </div>

                <form wire:submit="generate" class="p-6 space-y-4">
                    @if(auth()->user()->hasRole('Admin'))
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Lab Facility</label>
                        <select wire:model="labId" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('labId') border-rose-300 ring-2 ring-rose-100 @enderror">
                            <option value="">Select Target Lab</option>
                            @foreach($labs as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                        @error('labId') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    @if((auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Lab Manager')) && $zones->count())
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Target Zone</label>
                        <select wire:model.live="zoneId" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('zoneId') border-rose-300 ring-2 ring-rose-100 @enderror">
                            <option value="">Select Agricultural Zone</option>
                            @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" @if($zone->code === 'N/A') disabled class="text-gray-400" @endif>{{ $zone->name }} ({{ $zone->code ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Select zone to populate the prefix automatically</p>
                        @error('zoneId') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Prefix Code</label>
                        @if(auth()->user()->hasRole('Admin'))
                        <input wire:model="prefix" type="text" placeholder="e.g. A, B, CSTL" maxlength="5" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-mono uppercase text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('prefix') border-rose-300 ring-2 ring-rose-100 @enderror">
                        <p class="text-[11px] text-gray-400 mt-1">Alphanumeric prefix (max 5 letters)</p>
                        @else
                        <input wire:model="prefix" type="text" readonly class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-sm font-mono text-gray-700 cursor-not-allowed">
                        <p class="text-[11px] text-gray-400 mt-1">Zone code — auto-assigned</p>
                        @if($prefix === 'N/A')
                        <p class="text-xs text-rose-500 mt-1">No zone code assigned. Contact your admin.</p>
                        @endif
                        @endif
                        @error('prefix') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Number of QR Codes</label>
                        <input wire:model="count" type="number" min="1" max="500" placeholder="50" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('count') border-rose-300 ring-2 ring-rose-100 @enderror">
                        <p class="text-[11px] text-gray-400 mt-1">Generate between 1 and 500 codes in this batch</p>
                        @error('count') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]" wire:loading.attr="disabled">
                            <span wire:loading.remove class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                Generate &amp; Print Codes
                            </span>
                            <span wire:loading class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Generating batch...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: QR Codes Gallery --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50/70 to-white">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Generated Codes Catalog</h2>
                        <span class="text-xs text-gray-400 font-medium">({{ $codes->total() }} total)</span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->hasRole('Admin'))
                        <select wire:model.live="labId" class="px-3 py-1.5 rounded-xl border border-gray-200 bg-white text-xs text-gray-900 focus:outline-none focus:border-emerald-500">
                            <option value="">All Labs</option>
                            @foreach($labs as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                        @endif

                        <select wire:model.live="filter" class="px-3 py-1.5 rounded-xl border border-gray-200 bg-white text-xs text-gray-900 focus:outline-none focus:border-emerald-500">
                            <option value="">All Prefixes</option>
                            @foreach($prefixes as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Action Toolbar when items selected --}}
                @if($codes->count())
                <div class="px-6 py-3 bg-gray-50/70 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2 text-xs">
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 cursor-pointer font-medium text-gray-700">
                            <input type="checkbox" id="selectAll" wire:model.live="selectAll" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span>Select All</span>
                        </label>
                        <span class="px-2.5 py-0.5 rounded-full bg-gray-200 font-semibold text-gray-700 text-[11px]">
                            {{ count($selectedIds) }} selected
                        </span>
                    </div>

                    @if(count($selectedIds))
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="printSelected" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print Selected
                        </button>
                        @if(auth()->user()->hasRole('Admin'))
                        <button type="button" wire:click="deleteSelected" onclick="return confirm('Delete {{ count($selectedIds) }} QR codes? This cannot be undone.')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-medium shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete Selected
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                {{-- Codes Grid --}}
                <div class="p-6">
                    @if($codes->count())
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($codes as $code)
                        <div class="relative bg-white border {{ $code->is_used ? 'border-rose-200 bg-rose-50/10' : 'border-gray-200 hover:border-emerald-300' }} rounded-2xl p-3 flex flex-col items-center text-center transition shadow-xs group">
                            {{-- Checkbox --}}
                            <div class="absolute top-2.5 left-2.5 z-10">
                                <input type="checkbox" id="code-{{ $code->id }}" value="{{ $code->id }}" wire:model.live="selectedIds" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            </div>

                            <div class="w-20 h-20 my-2 flex items-center justify-center p-1 bg-white rounded-xl border border-gray-100 shadow-2xs">
                                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->errorCorrection('H')->generate($code->code) !!}
                            </div>

                            <span class="font-mono text-xs font-bold text-gray-900 tracking-wider">
                                {{ $code->code }}
                            </span>

                            <div class="mt-1.5">
                                @if($code->is_used)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                    Used
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                    Available
                                </span>
                                @endif
                            </div>

                            <div class="mt-3 w-full pt-2 border-t border-gray-100">
                                <a href="{{ route(auth()->user()->hasRole('Admin') ? 'admin.qr-codes.print' : 'field.qr-codes.print', ['prefix' => $code->prefix] + (auth()->user()->hasRole('Admin') ? ['lab_id' => $code->lab_id] : [])) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1 py-1 px-2 rounded-lg text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition" title="Print all {{ $code->prefix }} codes">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Print Batch
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <p class="font-medium text-gray-500">No QR codes generated yet</p>
                        <p class="text-xs text-gray-400 mt-1">Use the generator on the left to create sample bag identification codes</p>
                    </div>
                    @endif
                </div>

                @if($codes->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $codes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>