<div>
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
            btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();

            if (scannerActive) {
                if (html5QrCode) { html5QrCode.stop().catch(() => {}); }
                html5QrCode = null;
                container.style.display = 'none';
                scannerActive = false;
                btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();
                btn.classList.add('btn-outline-light');
                btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Scan QR to Test';
                return;
            }

            container.style.display = 'block';
            btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();
            btn.classList.add('btn-danger');
            btn.innerHTML = '<i class="fas fa-stop mr-1"></i> Stop Scanner';

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
                    btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="fas fa-check mr-1"></i> Found! Scan Again';

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
                btn.className = btn.className.replace(/\bbtn-(outline-light|danger|success)\b/g, '').trim();
                btn.classList.add('btn-outline-light');
                btn.innerHTML = '<i class="fas fa-camera mr-1"></i> Scan QR to Test';
                scannerActive = false;
            });

            scannerActive = true;
        };

        window.addEventListener('beforeunload', () => {
                if (html5QrCode) html5QrCode.stop().catch(() => {});
        });

    });
    </script>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-flask text-primary mr-2"></i> Sample Testing</h4>
            <p class="text-muted mb-0 small">View and test received samples. Scan QR codes for quick access.</p>
        </div>
        <div>
            <a href="{{ route('lab.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mr-1">
                <i class="fas fa-chart-bar mr-1"></i> Dashboard
            </a>
            <a href="{{ route('lab.queue') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                <i class="fas fa-inbox mr-1"></i> Receive Samples
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(52,152,219,.12);">
                        <i class="fas fa-hourglass-half text-primary fa-lg"></i>
                    </div>
                    <div>
                        <p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Pending Test</p>
                        <h2 class="mb-0 font-weight-bold text-primary">{{ $pendingCount }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100" onclick="document.getElementById('scanInput').focus()" style="cursor:pointer;">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(40,167,69,.12);">
                        <i class="fas fa-qrcode text-success fa-lg"></i>
                    </div>
                    <div>
                        <p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Quick Scan</p>
                        <h2 class="mb-0 font-weight-bold text-success"><i class="fas fa-camera mr-1"></i> Scan QR</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(231,76,60,.12);">
                        <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                    </div>
                    <div>
                        <p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Overdue</p>
                        <h2 class="mb-0 font-weight-bold text-danger">{{ $overdueCount }}</h2>
                        <small class="text-muted">&gt;{{ $overdueDays }} days</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3" style="width:48px;height:48px;background:rgba(155,89,182,.12);">
                        <i class="fas fa-file-alt text-purple fa-lg"></i>
                    </div>
                    <div>
                        <p class="mb-0 small text-uppercase text-muted" style="letter-spacing:.05em;">Completed</p>
                        <h2 class="mb-0 font-weight-bold"><a href="{{ route('samples.index') }}" class="text-purple"><i class="fas fa-check-circle mr-1"></i> View</a></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center flex-wrap">
                <div class="input-group input-group-sm mr-3" style="min-width:220px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 rounded-pill-left"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input wire:model.live.debounce="search" type="text" class="form-control border-left-0 rounded-pill-right" placeholder="Search code or farmer...">
                </div>
                <div class="btn-group btn-group-sm" role="group" wire:model.live="dateFilter">
                    <button class="btn rounded-pill-left {{ $dateFilter === '' ? 'btn-success' : 'btn-light border' }}" wire:click="$set('dateFilter','')">All</button>
                    <button class="btn {{ $dateFilter === 'today' ? 'btn-success' : 'btn-light border' }}" wire:click="$set('dateFilter','today')">Today</button>
                    <button class="btn {{ $dateFilter === 'week' ? 'btn-success' : 'btn-light border' }}" wire:click="$set('dateFilter','week')">This Week</button>
                    <button class="btn rounded-pill-right {{ $dateFilter === 'older' ? 'btn-success' : 'btn-light border' }}" wire:click="$set('dateFilter','older')">Overdue</button>
                </div>
            </div>
            <div class="mt-2 mt-md-0">
                <button wire:click="$refresh" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>

        <div class="card-body p-4">

            {{-- Scan Section --}}
            <div class="card border-0 mb-4 shadow-sm rounded-lg overflow-hidden" style="background:linear-gradient(135deg,#1a237e,#283593);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-white p-2 mr-3 shadow-sm" style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-qrcode text-dark fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-white">Find Sample by QR Code</h6>
                            <small class="text-white-50">Scan or type a QR code to jump directly to testing</small>
                        </div>
                    </div>
                    <form wire:submit.prevent="addScannedCode" id="scanForm">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="input-group input-group-sm mr-3" style="max-width:320px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-0 rounded-pill-left"><i class="fas fa-camera text-muted"></i></span>
                                </div>
                                <input wire:model="scanInput" type="text" id="scanInput" class="form-control form-control-sm bg-white border-0" placeholder="Type or scan QR code..." autocomplete="off" autofocus>
                            </div>
                            <button type="submit" class="btn btn-sm btn-light font-weight-bold px-3 rounded-pill shadow-sm mr-2 mt-2 mt-sm-0">
                                <i class="fas fa-search mr-1"></i> Find
                            </button>
                            <button type="button" id="scannerToggleBtn" onclick="toggleScanner()" class="btn btn-sm btn-outline-light rounded-pill px-3 mt-2 mt-sm-0">
                                <i class="fas fa-camera mr-1"></i> Scan QR
                            </button>
                        </div>
                    </form>

                    @error('scanInput')
                        <div class="text-warning small mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                    @enderror

                    <div wire:ignore id="qr-reader" style="display:none; max-width:280px; margin-top:12px; background:#fff; border-radius:8px; padding:8px;"></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th class="border-top-0 font-weight-bold py-3">Sample Code</th>
                            <th class="border-top-0 font-weight-bold py-3">QR</th>
                            <th class="border-top-0 font-weight-bold py-3">Farmer</th>
                            <th class="border-top-0 font-weight-bold py-3">Location</th>
                            <th class="border-top-0 font-weight-bold py-3">Plot / Crop</th>
                            <th class="border-top-0 font-weight-bold py-3">Soil Type</th>
                            <th class="border-top-0 font-weight-bold py-3">Received</th>
                            <th class="border-top-0 font-weight-bold py-3 text-center">Wait</th>
                            <th class="border-top-0 font-weight-bold py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($samples as $sample)
                            @php
                                $receivedAt = $sample->received_at ?? $sample->created_at;
                                $waitDays = $receivedAt ? max(0, (int) $receivedAt->diffInDays(now())) : 0;
                                $overdue = $waitDays > $overdueDays;
                            @endphp
                        <tr class="align-middle border-bottom" @if($overdue) style="background:#ffd6d6!important;" @endif>
                            <td class="py-3">
                                <span class="font-weight-bold text-dark">{{ $sample->sample_code }}</span>
                                <br><small class="text-muted">#{{ $sample->id }}</small>
                            </td>
                            <td class="py-3">
                                <code class="px-2 py-1 bg-light rounded-pill small">{{ $sample->qr_code_path ?? $sample->qrCode?->code ?? '—' }}</code>
                            </td>
                            <td class="py-3">
                                <span class="font-weight-bold">{{ $sample->farmer->name ?? '—' }}</span>
                                <br><small class="text-muted">{{ $sample->farmer->phone ?? '' }}</small>
                            </td>
                            <td class="py-3 small">
                                {{ $sample->farmer->village?->name ?? '—' }}
                                @if($sample->farmer->zone)
                                    <br><span class="text-muted">{{ $sample->farmer->zone->name }}</span>
                                @endif
                                @if($sample->farmer->district)
                                    <br><span class="text-muted">{{ $sample->farmer->district?->name }} / {{ $sample->farmer->state?->name }}</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span>{{ $sample->plot->plot_name ?? '—' }}</span>
                                <br><small class="text-muted">{{ $sample->plot->crop?->name ?? '' }}</small>
                            </td>
                            <td class="py-3 small text-muted">{{ $sample->plot->soilType?->name ?? '—' }}</td>
                            <td class="py-3 small text-muted">
                                {{ $sample->received_at?->format('d M Y') ?? '—' }}
                                <br><small>{{ $sample->received_at?->format('h:i A') ?? '' }}</small>
                            </td>
                            <td class="py-3 text-center">
                                @if($overdue)
                                    <span class="badge badge-danger badge-pill px-2 py-1">{{ $waitDays }}d</span>
                                @else
                                    <span class="badge badge-secondary badge-pill px-2 py-1">{{ $waitDays }}d</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('lab.testing', $sample->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-flask mr-1"></i> Test Now
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-flask fa-4x text-muted mb-3 d-block" style="opacity:.2;"></i>
                                    <h5 class="text-muted font-weight-normal mb-1">No samples pending testing</h5>
                                    <p class="text-muted small mb-0">
                                        @if($search) Try a different search term.
                                        @elseif($dateFilter === 'today') No samples received today.
                                        @elseif($dateFilter === 'older') No overdue samples.
                                        @else All received samples have been tested.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-file mr-1"></i> Page {{ $samples->currentPage() }} &middot;
                    {{ $samples->firstItem() ?? 0 }}–{{ $samples->lastItem() ?? 0 }} of {{ $samples->total() }}
                </small>
                <div>{{ $samples->links() }}</div>
            </div>
        </div>
    </div>

    <style>
        .opacity-50 { opacity: .5; }
        .align-middle td, .align-middle th { vertical-align: middle !important; }
        .table th { border-top: 0; }
        .text-purple { color: #9b59b6; }
        .rounded-pill-left { border-radius: 50rem 0 0 50rem !important; }
        .rounded-pill-right { border-radius: 0 50rem 50rem 0 !important; }
        .input-group .rounded-pill-left.input-group-text { border-radius: 50rem 0 0 50rem !important; }
    </style>
</div>
