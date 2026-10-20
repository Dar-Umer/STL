<div class="space-y-6">
    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <script>
    let beepCtx = null;
    function beep() {
        try {
            if (!beepCtx) {
                beepCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (beepCtx.state === 'suspended') {
                beepCtx.resume();
            }
            const osc = beepCtx.createOscillator();
            const gain = beepCtx.createGain();
            osc.connect(gain);
            gain.connect(beepCtx.destination);
            osc.frequency.value = 1200;
            gain.gain.value = 0.15;
            osc.start(beepCtx.currentTime);
            osc.stop(beepCtx.currentTime + 0.12);
        } catch(e) {}
    }

    document.addEventListener('livewire:initialized', () => {
        let html5QrCode = null;
        let scannerActive = false;

        window.toggleScanner = function() {
            const container = document.getElementById('qr-reader');
            const btn = document.getElementById('scannerToggleBtn');

            if (scannerActive) {
                if (html5QrCode) { html5QrCode.stop().catch(() => {}); }
                html5QrCode = null;
                container.style.display = 'none';
                scannerActive = false;
                btn.className = 'inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition';
                btn.innerHTML = '<i class="fas fa-camera"></i> Scan QR';
                return;
            }

            container.style.display = 'block';
            btn.className = 'inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition';
            btn.innerHTML = '<i class="fas fa-stop"></i> Stop Scanner';

            html5QrCode = new Html5Qrcode('qr-reader');

            html5QrCode.start(
                { facingMode: 'environment' },
                {
                    fps: 20,
                    qrbox: { width: 280, height: 280 },
                    aspectRatio: 1.333,
                    disableFlip: true,
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                },
                (decodedText) => {
                    html5QrCode.stop().catch(() => {});
                    html5QrCode = null;
                    scannerActive = false;
                    container.style.display = 'none';

                    beep();

                    const inp = document.getElementById('scanInput');
                    const frm = document.getElementById('scanForm');
                    if (inp && frm) {
                        inp.value = decodedText;
                        inp.dispatchEvent(new Event('input', { bubbles: true }));
                        frm.requestSubmit();
                    }
                }
            ).catch((err) => {
                container.style.display = 'none';
                btn.className = 'inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition';
                btn.innerHTML = '<i class="fas fa-camera"></i> Scan QR';
                scannerActive = false;
            });

            scannerActive = true;
        };

        window.addEventListener('beforeunload', () => {
            if (html5QrCode) html5QrCode.stop().catch(() => {});
        });
    });
    </script>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-emerald-500/20">
                <i class="fas fa-flask"></i>
            </span>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Sample Testing Queue</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Laboratory chemical analysis workbench for checked-in soil samples</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('lab.dashboard') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Lab Overview</span>
            </a>
            <a href="{{ route('lab.queue') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-inbox text-[11px]"></i>
                <span>Receive Samples</span>
            </a>
        </div>
    </div>

    {{-- 4 KPI Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Pending Test --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Testing</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-hourglass-half"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($pendingCount) }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Awaiting Analysis
                </span>
            </div>
        </div>

        {{-- Quick Scan trigger --}}
        <div onclick="document.getElementById('scanInput') ? document.getElementById('scanInput').focus() : null"
             class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group cursor-pointer">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Fast Scan</span>
                <span class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-qrcode"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-xl font-bold text-gray-900 tracking-tight">Scan Sample QR</span>
                <span class="text-xs text-cyan-600 font-semibold group-hover:translate-x-0.5 transition-transform">Focus &rarr;</span>
            </div>
        </div>

        {{-- Overdue Samples --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Overdue SLA</span>
                <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-triangle-exclamation"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-rose-600 tracking-tight">{{ number_format($overdueCount) }}</span>
                <span class="inline-flex items-center text-[11px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full">
                    &gt;{{ $overdueDays }} Days Wait
                </span>
            </div>
        </div>

        {{-- Completed Archive --}}
        <a href="{{ route('samples.index') }}"
           class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg group flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Test History</span>
                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-circle-check"></i>
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-base font-bold text-indigo-700">View Full Catalog</span>
                <span class="text-xs text-indigo-500 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
        </a>
    </div>

    {{-- Dark QR Finder Hero Bar --}}
    <div class="bg-gray-900 rounded-2xl shadow-xl border border-gray-800 text-white p-6 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400 text-base flex-shrink-0">
                    <i class="fas fa-qrcode"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-white">Find Sample by QR Barcode</h3>
                    <p class="text-xs text-gray-400">Scan or type container barcode to jump directly to the parameter input form</p>
                </div>
            </div>

            <form wire:submit.prevent="addScannedCode" id="scanForm" class="flex flex-wrap items-center gap-2.5">
                <div class="relative flex-1 min-w-[240px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <input wire:model="scanInput" type="text" id="scanInput"
                           placeholder="Scan or enter code..." autofocus autocomplete="off"
                           class="w-full rounded-xl border border-gray-700 bg-gray-800 pl-9 pr-3 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-gray-900 bg-white hover:bg-gray-100 rounded-xl shadow-xs transition active:scale-[0.98]">
                    <i class="fas fa-search text-emerald-600 text-xs"></i>
                    <span>Find</span>
                </button>

                <button type="button" id="scannerToggleBtn" onclick="toggleScanner()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition active:scale-[0.98]">
                    <i class="fas fa-camera"></i>
                    <span>Scan QR</span>
                </button>
            </form>
        </div>

        @error('scanInput')
            <p class="text-xs font-medium text-amber-400 flex items-center gap-1.5 mt-3">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $message }}</span>
            </p>
        @enderror

        <div wire:ignore id="qr-reader" style="display:none; max-width:280px; margin-top:16px; background:#fff; border-radius:12px; padding:8px;"></div>
    </div>

    {{-- Testing Catalog Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        {{-- Filters & Controls Bar --}}
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Sample code, farmer, plot..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-9 pr-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                {{-- Segmented Date Filter --}}
                <div class="inline-flex rounded-xl bg-gray-100 p-1 border border-gray-200 text-xs">
                    <button type="button" wire:click="$set('dateFilter', '')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $dateFilter === '' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        All ({{ $pendingCount }})
                    </button>
                    <button type="button" wire:click="$set('dateFilter', 'today')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $dateFilter === 'today' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        Today
                    </button>
                    <button type="button" wire:click="$set('dateFilter', 'week')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $dateFilter === 'week' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        This Week
                    </button>
                    <button type="button" wire:click="$set('dateFilter', 'older')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $dateFilter === 'older' ? 'bg-white text-rose-700 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        Overdue ({{ $overdueCount }})
                    </button>
                </div>
            </div>

            <button wire:click="$refresh"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Refresh</span>
            </button>
        </div>

        {{-- Queue Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Sample Code</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">QR Code</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Location</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Plot / Crop</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Soil Type</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Received</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">Wait</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($samples as $sample)
                        @php
                            $receivedAt = $sample->received_at ?? $sample->created_at;
                            $waitDays = $receivedAt ? max(0, (int) $receivedAt->diffInDays(now())) : 0;
                            $overdue = $waitDays > $overdueDays;
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition {{ $overdue ? 'bg-rose-50/20' : '' }}">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-gray-900">{{ $sample->sample_code }}</span>
                                @if($sample->lab_number)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-mono font-bold block w-fit">
                                        <i class="fas fa-tag text-[9px] text-emerald-500"></i> {{ $sample->lab_number }}
                                    </span>
                                @else
                                    <span class="block text-[11px] text-gray-400">#{{ $sample->id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <code class="px-2 py-0.5 bg-gray-100 rounded text-xs font-mono text-gray-700">
                                    {{ $sample->qr_code_path ?? $sample->qrCode?->code ?? '—' }}
                                </code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 block">{{ $sample->farmer->name ?? '—' }}</span>
                                @if($sample->farmer?->phone)
                                    <span class="text-xs text-gray-400 font-mono">{{ $sample->farmer->phone }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div>{{ $sample->farmer->village?->name ?? '—' }}</div>
                                @if($sample->farmer?->district)
                                    <div class="text-[11px] text-gray-400">{{ $sample->farmer->district->name }} / {{ $sample->farmer->state?->name ?? '' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="font-medium text-gray-900">{{ $sample->plot->plot_name ?? '—' }}</span>
                                @if($sample->plot?->crop)
                                    <span class="block text-[11px] text-emerald-700 font-medium">{{ $sample->plot->crop->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $sample->plot->soilType?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div>{{ $sample->received_at?->format('d M Y') ?? '—' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $sample->received_at?->format('h:i A') ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($overdue)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                        {{ $waitDays }}d
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ $waitDays }}d
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('lab.testing', $sample->id) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                                    <i class="fas fa-vial text-xs"></i>
                                    <span>Test Now</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                <span class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-xl mb-3">
                                    <i class="fas fa-flask"></i>
                                </span>
                                <h4 class="text-sm font-semibold text-gray-700">
                                    {{ $search ? 'No samples match your search' : 'No samples pending testing' }}
                                </h4>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $search ? 'Try a different search keyword.' : 'All received samples have been analyzed and reported.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($samples->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $samples->links() }}
        </div>
        @endif
    </div>
</div>
