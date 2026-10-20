<div class="space-y-6">
    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <script>
    let beepCtx = null;
    function beepTone(freq) {
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
            osc.frequency.value = freq || 1200;
            gain.gain.value = 0.15;
            osc.start(beepCtx.currentTime);
            osc.stop(beepCtx.currentTime + 0.12);
        } catch(e) {}
    }

    function refocusScanInput() {
        const reader = document.getElementById('qr-reader');
        if (reader && reader.style.display !== 'none') return;

        setTimeout(() => {
            const inp = document.getElementById('scanInput');
            const active = document.activeElement;
            const busy = active && ['INPUT', 'TEXTAREA', 'SELECT'].includes(active.tagName) && active.id !== 'scanInput';
            if (inp && !busy) inp.focus();
        }, 150);
    }

    document.addEventListener('livewire:initialized', () => {
        let html5QrCode = null;
        let scannerActive = false;

        window.addEventListener('scan-added', () => { beepTone(1200); refocusScanInput(); });
        window.addEventListener('scan-duplicate', () => { beepTone(650); });
        window.addEventListener('scan-rejected', () => { beepTone(250); refocusScanInput(); });

        window.toggleScanner = function() {
            const container = document.getElementById('qr-reader');
            const btn = document.getElementById('scannerToggleBtn');

            if (scannerActive) {
                if (html5QrCode) { html5QrCode.stop().catch(() => {}); }
                html5QrCode = null;
                container.style.display = 'none';
                scannerActive = false;
                btn.className = 'inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition';
                btn.innerHTML = '<i class="fas fa-camera"></i> Camera';
                refocusScanInput();
                return;
            }

            container.style.display = 'block';
            btn.className = 'inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition';
            btn.innerHTML = '<i class="fas fa-stop"></i> Stop';

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

                    beepTone(1200);

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
                btn.innerHTML = '<i class="fas fa-camera"></i> Camera';
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
            <span class="w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-amber-500/20">
                <i class="fas fa-inbox"></i>
            </span>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Receive Soil Samples</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Scan QR codes or bulk check-in samples entering laboratory intake</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('lab.evaluations') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 rounded-xl shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-clipboard-list text-emerald-600"></i>
                <span>Evaluations & Lab Sheets</span>
            </a>
            <a href="{{ route('lab.dashboard') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Lab Overview</span>
            </a>
            <button wire:click="$refresh"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    @if(session('receivedBatch'))
        <div class="p-5 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent border border-emerald-500/30 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-emerald-600/30 flex-shrink-0">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-sm font-bold text-gray-900">Samples Received &bull; Evaluation Sheet Generated!</h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ session('receivedBatch')['batch_code'] }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mt-0.5">
                        Assigned sequential Lab Codes <span class="font-mono font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">{{ session('receivedBatch')['from'] }}</span> to 
                        <span class="font-mono font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">{{ session('receivedBatch')['to'] }}</span> 
                        ({{ session('receivedBatch')['count'] }} sample{{ session('receivedBatch')['count'] > 1 ? 's' : '' }}). Print worksheet below for lab bench analysis.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <a href="{{ route('lab.evaluations.batch.pdf', session('receivedBatch')['id']) }}" target="_blank"
                   class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-md shadow-emerald-600/25 transition">
                    <i class="fas fa-file-pdf"></i>
                    <span>Print Evaluation Sheet</span>
                </a>
                <a href="{{ route('lab.evaluations') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold transition">
                    <span>View Evaluations</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    @endif

    {{-- Modern PTA Dark Scanner Hero Card --}}
    <div class="bg-gray-900 rounded-2xl shadow-xl border border-gray-800 text-white p-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex-1 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400 text-base">
                        <i class="fas fa-qrcode"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-white">Rapid QR Code Intake Scanner</h3>
                        <p class="text-xs text-gray-400">Scan container barcode or type sample code &mdash; duplicate protection enabled</p>
                    </div>
                </div>

                <form wire:submit.prevent="addScannedCode" id="scanForm" class="flex flex-wrap items-center gap-2.5 pt-1">
                    <div class="relative flex-1 min-w-[260px] max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <input wire:model="scanInput" type="text" id="scanInput"
                               placeholder="Type or scan QR code string..." autofocus autocomplete="off"
                               class="w-full rounded-xl border border-gray-700 bg-gray-800/90 pl-9 pr-4 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:bg-gray-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                    </div>

                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold text-gray-900 bg-white hover:bg-gray-100 rounded-xl shadow-xs transition active:scale-[0.98]">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add</span>
                    </button>

                    <button type="button" id="scannerToggleBtn" onclick="toggleScanner()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition active:scale-[0.98]">
                        <i class="fas fa-camera"></i>
                        <span>Camera</span>
                    </button>

                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-800 border border-gray-700 text-gray-300 ml-auto">
                        <i class="fas fa-layer-group text-emerald-400 text-[10px]"></i>
                        {{ count($scannedSamples) }} staged
                    </span>
                </form>

                @error('scanInput')
                    <p class="text-xs font-medium text-amber-400 flex items-center gap-1.5">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            @if(count($scannedSamples) > 0)
                <div class="flex items-center gap-2">
                    <button wire:click="receiveScanned" wire:confirm="Receive {{ count($scannedSamples) }} scanned sample(s)?"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl shadow-lg shadow-emerald-600/30 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Commit Intake ({{ count($scannedSamples) }})</span>
                    </button>
                    <button wire:click="clearScanned"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-xs font-medium text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 rounded-xl border border-gray-700 transition">
                        <span>Clear</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- Camera preview container --}}
        <div wire:ignore id="qr-reader" style="display:none; max-width:280px; margin-top:16px; background:#fff; border-radius:12px; padding:8px;"></div>

        {{-- Staged Scanned Chips --}}
        @if(count($scannedSamples) > 0)
            <div class="mt-4 pt-4 border-t border-gray-800 flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mr-1">Staged for Intake:</span>
                @foreach($scannedSamples as $idx => $item)
                    <span wire:key="chip-{{ $item['id'] }}"
                          class="inline-flex items-center gap-2 px-3 py-1 bg-gray-800 border border-gray-700 rounded-full text-xs text-white">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <strong class="font-mono text-emerald-300">{{ $item['sample_code'] }}</strong>
                        <span class="text-gray-400">&middot;</span>
                        <span class="text-gray-300 truncate max-w-[140px]">{{ $item['farmer'] }}</span>
                        <button type="button" wire:click="removeScannedSample({{ $idx }})" class="text-gray-400 hover:text-rose-400 ml-1 transition" title="Remove">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 flex items-center gap-4">
            <span class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fas fa-inbox"></i>
            </span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Receipt</p>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['toReceive']) }}</span>
                    <span class="text-[11px] text-gray-400">
                        ({{ $stats['fieldPending'] }} Field &middot; {{ $stats['selfPending'] }} Self)
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 flex items-center gap-4">
            <span class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fas fa-circle-check"></i>
            </span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Received Today</p>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['receivedToday']) }}</span>
                    <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Checked In</span>
                </div>
            </div>
        </div>

        <a href="{{ route('lab.testing-list') }}" class="bg-gradient-to-br from-blue-50/50 via-white to-blue-50/30 rounded-2xl shadow-xs hover:shadow-md border border-blue-100 p-5 flex items-center justify-between group transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center gap-4">
                <span class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-flask"></i>
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Testing Workbench</p>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">Enter Test Results &rarr;</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Intake Queue Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        {{-- Filters & Bulk Action Header --}}
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search code or farmer..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-9 pr-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                {{-- Segmented Source Filter --}}
                <div class="inline-flex rounded-xl bg-gray-100 p-1 border border-gray-200 text-xs">
                    <button type="button" wire:click="$set('sourceFilter', '')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $sourceFilter === '' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        All ({{ $stats['toReceive'] }})
                    </button>
                    <button type="button" wire:click="$set('sourceFilter', 'field')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $sourceFilter === 'field' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        <i class="fas fa-tractor mr-1 text-[10px]"></i> Field ({{ $stats['fieldPending'] }})
                    </button>
                    <button type="button" wire:click="$set('sourceFilter', 'self')"
                            class="px-3 py-1 rounded-lg font-semibold transition {{ $sourceFilter === 'self' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900' }}">
                        <i class="fas fa-hand-holding mr-1 text-[10px]"></i> Self ({{ $stats['selfPending'] }})
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="sort"
                        class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    <option value="desc">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>

                @if($stats['toReceive'] > 0)
                    <button wire:click="receiveAllPending" wire:confirm="Receive ALL {{ $stats['toReceive'] }} pending samples? This cannot be undone."
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Receive All ({{ $stats['toReceive'] }})</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Bulk Selection Alert Bar --}}
        @if(count($selected) > 0)
            <div class="bg-emerald-50 border-y border-emerald-200 px-6 py-3 flex flex-wrap items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-900">
                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[11px]">
                        {{ count($selected) }}
                    </span>
                    <span>samples selected from this view</span>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="receiveSelected" wire:confirm="Receive {{ count($selected) }} selected sample(s)?"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition active:scale-[0.98]">
                        <i class="fas fa-check-double text-[11px]"></i>
                        <span>Receive Selected</span>
                    </button>
                    <button wire:click="$set('selected', [])"
                            class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-200 rounded-xl transition">
                        Clear
                    </button>
                </div>
            </div>
        @endif

        {{-- Queue Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-10">
                            <input type="checkbox"
                                   {{ !empty($pageIds) && count(array_intersect($pageIds, $selected)) === count($pageIds) ? 'checked' : '' }}
                                   wire:click="toggleSelectAll"
                                   class="rounded text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                   title="Select all on this page">
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Sample Code</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">QR Code</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Location</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Plot</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Waiting</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Source</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($samples as $sample)
                        @php
                            $waitDays = (int) ($sample->collected_at ?? $sample->created_at)->diffInDays(now());
                            $isOverdue = $waitDays >= $overdueDays;
                        @endphp
                        <tr wire:key="row-{{ $sample->id }}" class="hover:bg-gray-50/60 transition {{ $isOverdue ? 'bg-amber-50/20' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $sample->id }}" wire:model="selected"
                                       class="rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-gray-900">{{ $sample->sample_code }}</span>
                                <span class="block text-[11px] text-gray-400">#{{ $sample->id }}</span>
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
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $sample->plot->plot_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($isOverdue)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                        {{ $waitDays }} {{ Str::plural('Day', $waitDays) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <i class="fas fa-clock text-[10px] text-gray-400"></i>
                                        {{ $waitDays }} {{ Str::plural('Day', $waitDays) }}
                                    </span>
                                @endif
                                <span class="block text-[11px] text-gray-400 mt-0.5">{{ ($sample->collected_at ?? $sample->created_at)->format('d M') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($sample->source === 'self')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-200">
                                        <i class="fas fa-hand-holding text-[10px]"></i>
                                        Self
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        <i class="fas fa-tractor text-[10px]"></i>
                                        Field
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="markReceived({{ $sample->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                                    <i class="fas fa-check text-[10px]"></i>
                                    <span>Receive</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                <span class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-xl mb-3">
                                    <i class="fas fa-inbox"></i>
                                </span>
                                <h4 class="text-sm font-semibold text-gray-700">
                                    {{ $search || $sourceFilter ? 'No samples match your filters' : 'Queue is clear — no pending intake' }}
                                </h4>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $search || $sourceFilter ? 'Try clearing search or changing the source pill filter.' : 'All collected samples have been received into the laboratory.' }}
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
