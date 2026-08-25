<div>
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
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-light');
                btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Camera';
                refocusScanInput();
                return;
            }

            container.style.display = 'block';
            btn.classList.remove('btn-outline-light');
            btn.classList.add('btn-danger');
            btn.innerHTML = '<i class="fas fa-stop mr-1"></i> Stop';

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
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-light');
                btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Camera';
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
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold text-dark"><i class="fas fa-inbox text-warning mr-2"></i>Receive Samples</h4>
            <p class="text-muted mb-0 small">Scan QR codes, search, or tick rows to receive samples into the lab.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('lab.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mr-1">
                <i class="fas fa-arrow-left mr-1"></i> Dashboard
            </a>
            <button wire:click="$refresh" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
        </div>
    </div>

    {{-- Scanner Section --}}
    <div class="card bg-gradient-dark text-white border-0 shadow-lg mb-4 rounded-lg overflow-hidden">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-white p-2 mr-3" style="width:42px;height:42px; display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-qrcode text-dark fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-white">QR Code Scanner</h6>
                            <small class="text-white-50">Scan or type a QR code to queue for receiving &mdash; duplicates are detected automatically</small>
                        </div>
                    </div>
                    <form wire:submit.prevent="addScannedCode" id="scanForm">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="input-group input-group-sm" style="max-width:280px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-0"><i class="fas fa-camera text-muted"></i></span>
                                </div>
                                <input wire:model="scanInput" type="text" id="scanInput" class="form-control form-control-sm border-0" placeholder="Type or scan QR code..." autofocus autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-sm btn-light font-weight-bold px-3 rounded-pill">
                                <i class="fas fa-plus mr-1"></i> Add
                            </button>
                            <button type="button" id="scannerToggleBtn" onclick="toggleScanner()" class="btn btn-sm btn-outline-light rounded-pill px-3">
                                <i class="fas fa-camera mr-1"></i> Camera
                            </button>
                            <span class="badge badge-light badge-pill px-3 py-2 ml-auto">
                                <i class="fas fa-layer-group mr-1 text-muted"></i> {{ count($scannedSamples) }} scanned
                            </span>
                        </div>
                    </form>
                    @error('scanInput')
                        <div class="text-warning small mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-4 text-lg-right">
                    @if(count($scannedSamples) > 0)
                        <button wire:click="receiveScanned" wire:confirm="Receive {{ count($scannedSamples) }} scanned sample(s)?" class="btn btn-success btn-sm px-4 rounded-pill mr-1 shadow-sm font-weight-bold">
                            <i class="fas fa-check-double mr-1"></i> Receive {{ count($scannedSamples) }}
                        </button>
                        <button wire:click="clearScanned" class="btn btn-outline-light btn-sm rounded-pill px-3">
                            <i class="fas fa-times mr-1"></i> Clear
                        </button>
                    @endif
                </div>
            </div>

            <div wire:ignore id="qr-reader" style="display:none; max-width:280px; margin-top:12px; background:#fff; border-radius:8px; padding:8px;"></div>

            @if(count($scannedSamples) > 0)
                <div class="mt-3 d-flex flex-wrap align-items-center rounded p-2" style="background:rgba(255,255,255,.08);">
                    <small class="text-white-50 mr-2 font-weight-bold text-uppercase" style="letter-spacing:.05em;">Queue:</small>
                    @foreach($scannedSamples as $idx => $item)
                        <span wire:key="chip-{{ $item['id'] }}" class="badge badge-light mr-1 mb-1 d-inline-flex align-items-center px-3 py-2 text-dark shadow-sm rounded-pill">
                            <i class="fas fa-check-circle text-success mr-1"></i>
                            <span class="font-weight-bold">{{ $item['sample_code'] }}</span>
                            <span class="mx-1 text-muted">&middot;</span>
                            <span>{{ Str::limit($item['farmer'], 18) }}</span>
                            <button type="button" class="close ml-2 text-dark" style="font-size:14px;line-height:1;opacity:.5;" wire:click="removeScannedSample({{ $idx }})" title="Remove">&times;</button>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="d-flex align-items-center p-3 h-100 rounded-lg" style="background:rgba(255,193,7,.08);">
                <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width:48px;height:48px;">
                    <i class="fas fa-inbox text-white"></i>
                </div>
                <div>
                    <span class="d-block text-muted small text-uppercase" style="letter-spacing:.05em;">Pending Receipt</span>
                    <span class="h4 mb-0 font-weight-bold">{{ $stats['toReceive'] }}</span>
                    <small class="text-muted ml-1">
                        (<i class="fas fa-tractor mr-1"></i>{{ $stats['fieldPending'] }}
                        <i class="fas fa-hand-holding mx-1"></i>{{ $stats['selfPending'] }})
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="d-flex align-items-center p-3 h-100 rounded-lg" style="background:rgba(40,167,69,.08);">
                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width:48px;height:48px;">
                    <i class="fas fa-check-double text-white"></i>
                </div>
                <div>
                    <span class="d-block text-muted small text-uppercase" style="letter-spacing:.05em;">Received Today</span>
                    <span class="h4 mb-0 font-weight-bold">{{ $stats['receivedToday'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('lab.testing-list') }}" class="text-decoration-none">
                <div class="d-flex align-items-center p-3 h-100 rounded-lg" style="background:rgba(220,53,69,.08);">
                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width:48px;height:48px;">
                        <i class="fas fa-flask text-white"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted small text-uppercase" style="letter-spacing:.05em;">Testing</span>
                        <span class="h5 mb-0 font-weight-bold text-danger"><i class="fas fa-arrow-right mr-1"></i> Go to Testing</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-4">

            {{-- Filters + Receive All --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Search --}}
                    <div class="input-group input-group-sm" style="max-width:280px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-0 rounded-pill-left"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input wire:model.live.debounce.400ms="search" type="text" class="form-control form-control-sm bg-light border-0" placeholder="Search sample code or farmer..." style="border-radius:0 50px 50px 0;">
                    </div>
                    {{-- Source pills --}}
                    <div class="btn-group btn-group-toggle btn-group-sm" data-toggle="buttons">
                        <button type="button" class="btn {{ $sourceFilter === '' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3" wire:click="$set('sourceFilter', '')">
                            All
                        </button>
                        <button type="button" class="btn {{ $sourceFilter === 'field' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3" wire:click="$set('sourceFilter', 'field')">
                            <i class="fas fa-tractor mr-1"></i>Field ({{ $stats['fieldPending'] }})
                        </button>
                        <button type="button" class="btn {{ $sourceFilter === 'self' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3" wire:click="$set('sourceFilter', 'self')">
                            <i class="fas fa-hand-holding mr-1"></i>Self ({{ $stats['selfPending'] }})
                        </button>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                    <select wire:model.live="sort" class="custom-select custom-select-sm border-0 bg-light rounded-pill px-3" style="width:auto;">
                        <option value="desc">Newest first</option>
                        <option value="asc">Oldest first</option>
                    </select>
                    @if($stats['toReceive'] > 0)
                        <button wire:click="receiveAllPending" wire:confirm="Receive ALL {{ $stats['toReceive'] }} pending samples? This cannot be undone." class="btn btn-sm btn-success rounded-pill px-4 shadow-sm font-weight-bold">
                            <i class="fas fa-check-double mr-1"></i> Receive All ({{ $stats['toReceive'] }})
                        </button>
                    @endif
                </div>
            </div>

            {{-- Bulk action bar --}}
            @if(count($selected) > 0)
                <div class="alert alert-primary d-flex flex-wrap justify-content-between align-items-center py-2 px-3 rounded-lg mb-3">
                    <span class="font-weight-bold"><i class="fas fa-tasks mr-1"></i> {{ count($selected) }} sample(s) selected</span>
                    <span>
                        <button wire:click="receiveSelected" wire:confirm="Receive {{ count($selected) }} selected sample(s)?" class="btn btn-sm btn-success rounded-pill px-3 mr-1 font-weight-bold">
                            <i class="fas fa-check-double mr-1"></i> Receive Selected
                        </button>
                        <button wire:click="$set('selected', [])" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Clear</button>
                    </span>
                </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th class="border-top-0 font-weight-bold py-3" style="width:36px;">
                                <input type="checkbox"
                                       {{ !empty($pageIds) && count(array_intersect($pageIds, $selected)) === count($pageIds) ? 'checked' : '' }}
                                       wire:click="toggleSelectAll"
                                       title="Select all on this page">
                            </th>
                            <th class="border-top-0 font-weight-bold py-3">Sample Code</th>
                            <th class="border-top-0 font-weight-bold py-3">QR Code</th>
                            <th class="border-top-0 font-weight-bold py-3">Farmer</th>
                            <th class="border-top-0 font-weight-bold py-3">Location</th>
                            <th class="border-top-0 font-weight-bold py-3">Plot</th>
                            <th class="border-top-0 font-weight-bold py-3">Waiting</th>
                            <th class="border-top-0 font-weight-bold py-3">Source</th>
                            <th class="border-top-0 font-weight-bold py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($samples as $sample)
                        @php
                            $waitDays = (int) ($sample->collected_at ?? $sample->created_at)->diffInDays(now());
                            $isOverdue = $waitDays >= $overdueDays;
                        @endphp
                        <tr wire:key="row-{{ $sample->id }}" class="border-bottom {{ $isOverdue ? 'table-warning' : '' }}">
                            <td class="py-3">
                                <input type="checkbox" value="{{ $sample->id }}" wire:model="selected">
                            </td>
                            <td class="py-3">
                                <span class="font-weight-bold text-dark">{{ $sample->sample_code }}</span>
                                <br><small class="text-muted">#{{ $sample->id }}</small>
                            </td>
                            <td class="py-3">
                                <code class="px-2 py-1 bg-light rounded small">{{ $sample->qr_code_path ?? $sample->qrCode?->code ?? '—' }}</code>
                            </td>
                            <td class="py-3">
                                <span class="font-weight-bold">{{ $sample->farmer->name ?? '—' }}</span>
                                @if($sample->farmer?->phone)
                                <br><small class="text-muted">{{ $sample->farmer->phone }}</small>
                                @endif
                            </td>
                            <td class="py-3 text-muted small">
                                {{ $sample->farmer->village?->name ?? '—' }}
                                @if($sample->farmer?->district)
                                <br><small>{{ $sample->farmer->district->name }} / {{ $sample->farmer->state?->name ?? '' }}</small>
                                @endif
                            </td>
                            <td class="py-3 small">{{ $sample->plot->plot_name ?? '—' }}</td>
                            <td class="py-3">
                                <span class="badge badge-pill px-2 py-1 {{ $isOverdue ? 'badge-danger' : 'badge-light text-muted border' }}"
                                      title="{{ $sample->collected_at?->format('d M Y, h:i A') ?? $sample->created_at->format('d M Y, h:i A') }}">
                                    <i class="fas {{ $isOverdue ? 'fa-exclamation-triangle' : 'fa-clock' }} mr-1"></i>{{ $waitDays }} {{ Str::plural('Day', $waitDays) }}
                                </span>
                                <br><small class="text-muted">{{ ($sample->collected_at ?? $sample->created_at)->format('d M') }}</small>
                            </td>
                            <td class="py-3">
                                @if($sample->source === 'self')
                                <span class="badge badge-pill badge-info px-3 py-1"><i class="fas fa-hand-holding mr-1"></i>Self</span>
                                @else
                                <span class="badge badge-pill badge-secondary px-3 py-1"><i class="fas fa-tractor mr-1"></i>Field</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <button wire:click="markReceived({{ $sample->id }})" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm font-weight-bold">
                                    <i class="fas fa-check mr-1"></i> Receive
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                        <i class="fas fa-inbox fa-2x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted font-weight-normal mb-1">
                                        {{ $search || $sourceFilter ? 'No samples match your filters' : 'No samples to receive' }}
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        @if($search || $sourceFilter)
                                            Try clearing the search or switching the source filter.
                                        @else
                                            All samples have been received into the lab.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex flex-wrap justify-content-between align-items-center">
                <small class="text-muted mb-2 mb-sm-0">
                    <i class="fas fa-file mr-1"></i> Page {{ $samples->currentPage() }} &middot;
                    {{ $samples->firstItem() ?? 0 }}–{{ $samples->lastItem() ?? 0 }} of {{ $samples->total() }}
                </small>
                <div>{{ $samples->links() }}</div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-dark { background: linear-gradient(135deg, #2d3436 0%, #1a1a2e 100%); }
        .rounded-pill-left { border-radius: 50px 0 0 50px !important; }
        .align-middle td, .align-middle th { vertical-align: middle !important; }
        .table th { border-top: 0; }
        .gap-2 { gap: .5rem; }
    </style>
</div>
