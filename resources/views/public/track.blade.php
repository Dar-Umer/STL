<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Report &mdash; {{ setting('app_name', 'Soil Lab') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f0f2f5; color:#222; }

        .track-header { background:linear-gradient(135deg,#0a4b2a,#018840); padding:16px 0; border-bottom:3px solid #f3b229; }
        .track-header h1 { color:#fff; font-size:1rem; font-weight:800; margin:0; }
        .track-header .back-link { color:rgba(255,255,255,.7); font-size:.8rem; text-decoration:none; }
        .track-header .back-link:hover { color:#fff; }

        .track-hero { background:linear-gradient(135deg,#0a4b2a,#018840,#0a4b2a); padding:50px 0 40px; text-align:center; color:#fff; }
        .track-hero .icon { font-size:3rem; margin-bottom:10px; opacity:.85; }
        .track-hero h2 { font-size:1.6rem; font-weight:800; margin-bottom:6px; }
        .track-hero p { font-size:.9rem; opacity:.75; max-width:500px; margin:0 auto; }

        .lookup-box { background:#fff; border-radius:14px; padding:30px 24px; margin-top:-30px; position:relative; z-index:2; box-shadow:0 8px 30px rgba(0,0,0,.06); border:1px solid #e2e6ea; max-width:600px; margin-left:auto; margin-right:auto; margin-bottom:24px; }
        .lookup-input { border:2px solid #dce1e8; border-radius:25px; padding:12px 20px; font-size:.92rem; transition:all .2s; }
        .lookup-input:focus { border-color:#018840; box-shadow:0 0 0 3px rgba(1,136,64,.12); }
        .lookup-btn { border-radius:25px; padding:12px 24px; font-weight:600; background:#018840; border:none; font-size:.92rem; }
        .lookup-btn:hover { background:#0a4b2a; }

        .section-title { font-size:1.05rem; font-weight:800; color:#0a3d22; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .section-title i { color:#018840; }

        .sample-card { background:#fff; border-radius:10px; border:1px solid #e2e6ea; padding:14px 18px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; transition:all .15s; }
        .sample-card:hover { border-color:#018840; box-shadow:0 2px 10px rgba(1,136,64,.06); }
        .sample-card .badge-status { padding:2px 12px; border-radius:50px; font-size:.7rem; font-weight:600; }
        .badge-reported { background:#d4edda; color:#155724; }
        .badge-received { background:#cce5ff; color:#004085; }
        .badge-collected { background:#fff3cd; color:#856404; }
        .badge-other { background:#e2e3e5; color:#383d41; }

        .status-timeline { display:flex; align-items:center; gap:6px; margin-top:6px; flex-wrap:wrap; }
        .status-dot { width:8px; height:8px; border-radius:50%; display:inline-block; }
        .status-dot.done { background:#018840; }
        .status-dot.pending { background:#dce1e8; }
        .status-dot.active { background:#f3b229; }
        .status-step { font-size:.68rem; color:#64748b; display:flex; align-items:center; gap:4px; }
        .status-step .label { display:none; }
        @media (min-width:576px) {
            .status-step .label { display:inline; }
        }

        .empty-state { text-align:center; padding:50px 20px; }
        .empty-state i { font-size:3.5rem; color:#dce1e8; margin-bottom:14px; display:block; }
        .empty-state h5 { color:#64748b; font-weight:600; }
        .empty-state p { color:#94a3b8; font-size:.85rem; }

        .site-footer { background:#072b17; padding:24px 0; color:rgba(255,255,255,.55); font-size:.75rem; text-align:center; margin-top:40px; }
    </style>
    @if(setting('recaptcha_enabled', false) && app()->environment('production'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body>

    <div class="track-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-search mr-2"></i> Track Soil Report</h1>
            <a href="{{ route('home') }}" class="back-link"><i class="fas fa-arrow-left mr-1"></i> Back to Home</a>
        </div>
    </div>

    <div class="track-hero">
        <div class="container">
            <div class="icon"><i class="fas fa-file-medical-alt"></i></div>
            <h2>Find Your Soil Test Report</h2>
            <p>Enter your registered mobile number to check status and download your Soil Health Card.</p>
        </div>
    </div>

    <div class="container" style="padding-bottom:30px;">
        <div class="lookup-box">
            <form method="POST" action="{{ route('public.track.lookup') }}">
                @csrf
                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-pill py-2 mb-3 text-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}</div>
                @endif
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <div class="flex-fill w-100 mr-sm-2 mb-3 mb-sm-0">
                        <input type="tel" name="phone" class="form-control lookup-input" placeholder="Enter 10-digit mobile number..." value="{{ old('phone', $phone ?? '') }}" required pattern="[0-9]{10,}">
                    </div>
                    <button type="submit" class="btn btn-primary lookup-btn"><i class="fas fa-search mr-1"></i> Search</button>
                </div>
                @if(setting('recaptcha_enabled', false) && app()->environment('production'))
                    <div class="form-group mt-3">
                        <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key', '') }}"></div>
                        @error('g-recaptcha-response') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                @endif
                <small class="text-muted d-block mt-2"><i class="fas fa-info-circle mr-1"></i> Enter the mobile number you registered with the soil testing laboratory.</small>
            </form>
        </div>

        @if(session('error'))
            <div class="text-center py-4">
                <div class="alert alert-danger d-inline-block rounded-pill px-4 border-0">{{ session('error') }}</div>
            </div>
        @endif

        @if(isset($samples) && $phone)
            @if($samples->count())
                <h5 class="section-title"><i class="fas fa-list"></i> Your Samples</h5>
                <p class="text-muted mb-3" style="margin-top:-10px;font-size:.85rem;"><i class="fas fa-check-circle text-success mr-1"></i> {{ $samples->count() }} sample(s) found for <strong>{{ $phone }}</strong></p>
                @foreach($samples as $sample)
                    <div class="sample-card">
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex align-items-center mb-1 flex-wrap" style="gap:6px;">
                                <strong class="mr-1">{{ $sample->sample_code }}</strong>
                                <span class="badge-status 
                                    {{ $sample->status->value === 'reported' ? 'badge-reported' : '' }}
                                    {{ $sample->status->value === 'received' ? 'badge-received' : '' }}
                                    {{ $sample->status->value === 'collected' ? 'badge-collected' : '' }}
                                    {{ !in_array($sample->status->value, ['reported','received','collected']) ? 'badge-other' : '' }}
                                ">{{ $sample->status->label() }}</span>
                            </div>
                            <small class="text-muted d-block">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $sample->plot->plot_name ?? ($sample->farmer->name ?? '—') }}
                                @if($sample->plot->crop) &middot; {{ $sample->plot->crop->name }} @endif
                                &middot; {{ $sample->created_at->format('d M Y') }}
                            </small>
                            <div class="status-timeline">
                                <span class="status-step"><span class="status-dot {{ in_array($sample->status->value, ['collected','received','tested','reported']) ? 'done' : 'pending' }}"></span> <span class="label">Collected</span></span>
                                <span style="color:#dce1e8;font-size:.6rem;">&rarr;</span>
                                <span class="status-step"><span class="status-dot {{ in_array($sample->status->value, ['received','tested','reported']) ? 'done' : 'pending' }}"></span> <span class="label">Received</span></span>
                                <span style="color:#dce1e8;font-size:.6rem;">&rarr;</span>
                                <span class="status-step"><span class="status-dot {{ in_array($sample->status->value, ['tested','reported']) ? 'done' : 'pending' }}"></span> <span class="label">Tested</span></span>
                                <span style="color:#dce1e8;font-size:.6rem;">&rarr;</span>
                                <span class="status-step"><span class="status-dot {{ $sample->status->value === 'reported' ? 'done' : 'pending' }}"></span> <span class="label">Reported</span></span>
                            </div>
                        </div>
                        <div class="ml-3 text-right" style="min-width:110px;flex-shrink:0;">
                            @if($sample->status->value === 'reported')
                                <a href="{{ route('public.report', $sample->report_token) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"><i class="fas fa-download mr-1"></i> Download</a>
                            @else
                                <span class="text-muted small font-weight-medium">In progress</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No samples found</h5>
                    <p>No soil samples are registered under the mobile number <strong>{{ $phone }}</strong>. Please verify the number or contact your nearest soil testing laboratory.</p>
                </div>
            @endif
        @endif

        @if(!$phone)
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h5>Enter your mobile number</h5>
                <p>Type your registered 10-digit mobile number above and click Search to find your soil test report.</p>
            </div>
        @endif
    </div>

    <footer class="site-footer">
        <div class="container">
            &copy; {{ date('Y') }} {{ setting('app_name', 'Soil Lab') }} &mdash; Government of Jammu &amp; Kashmir (UT)
        </div>
    </footer>

</body>
</html>
