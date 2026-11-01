<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $metaTitle = setting('front.meta_title', '');
        $metaDesc = setting('front.meta_description', '');
        $metaKw = setting('front.meta_keywords', '');
        $ogPath = setting('front.og_image_path', '');
        $ogUrl = $ogPath ? url('storage/' . $ogPath) : '';
    @endphp
    <title>{{ $metaTitle ?: setting('app_name', 'Soil Testing Laboratory') . ' — Department of Horticulture, Pulwama, J&K' }}</title>
    @if($metaDesc)<meta name="description" content="{{ $metaDesc }}">@endif
    @if($metaKw)<meta name="keywords" content="{{ $metaKw }}">@endif
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $metaTitle ?: setting('app_name', 'Soil Testing Laboratory, Pulwama') }}">
    @if($metaDesc)<meta property="og:description" content="{{ $metaDesc }}">@endif
    @if($ogUrl)<meta property="og:image" content="{{ $ogUrl }}">
    <meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">@endif
    <meta name="twitter:card" content="summary_large_image">
    @if($ogUrl)<meta name="twitter:image" content="{{ $ogUrl }}">@endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Arabic:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">

    <style>
        :root {
            --gov-green-950: #042113;
            --gov-green-900: #07351d;
            --gov-green-800: #0d4a2b;
            --gov-green-700: #105c36;
            --gov-green-600: #157343;
            --gov-green-500: #1e874f;
            --gov-green-100: #eaf6ef;
            --gov-green-50:  #f3faf6;
            --gov-gold:      #c78c06;
            --gov-gold-light:#fef3c7;
            --gov-amber:     #d97706;
            --gov-slate-900: #0f172a;
            --gov-slate-800: #1e293b;
            --gov-slate-700: #334155;
            --gov-slate-600: #475569;
            --gov-slate-500: #64748b;
            --gov-slate-200: #e2e8f0;
            --gov-slate-100: #f1f5f9;
            --gov-slate-50:  #f8fafc;
            --gov-border:    #dbe3eb;
            --font-base: 100%;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; font-size: var(--font-base); }
        body { font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:var(--gov-slate-50); color:var(--gov-slate-800); overflow-x:hidden; line-height:1.55; }
        body.font-adjusted { --font-base: inherit; }

        .skip-link { position:absolute; top:-100px; left:0; background:var(--gov-green-700); color:#fff; padding:8px 16px; z-index:9999; font-weight:600; text-decoration:none; }
        .skip-link:focus { top:0; }

        /* ─── UTILITY ACCESSIBILITY BAR ─── */
        .util-bar { background:var(--gov-green-950); padding:7px 0; font-size:0.75rem; border-bottom:1px solid rgba(255,255,255,0.08); }
        .util-bar .util-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
        .util-bar .util-left { display:flex; align-items:center; gap:16px; color:rgba(255,255,255,0.7); }
        .util-bar .util-left a { color:rgba(255,255,255,0.7); text-decoration:none; transition:color .15s; }
        .util-bar .util-left a:hover { color:#fff; }
        .util-bar .util-right { display:flex; align-items:center; gap:12px; }
        .util-bar .txt-size { display:flex; align-items:center; gap:4px; }
        .util-bar .txt-size button { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.85); padding:1px 8px; border-radius:4px; cursor:pointer; font-size:0.72rem; transition:.15s; }
        .util-bar .txt-size button:hover { background:rgba(255,255,255,0.22); color:#fff; }
        .util-bar .lang-switch a { color:rgba(255,255,255,0.7); text-decoration:none; padding:0 4px; font-size:0.72rem; transition:color .15s; }
        .util-bar .lang-switch a:hover, .util-bar .lang-switch a.active { color:#fcd34d; font-weight:700; }
        .util-bar .lang-switch .sep { color:rgba(255,255,255,0.3); }
        .util-bar .helpline { color:#fcd34d; font-weight:600; text-decoration:none; white-space:nowrap; }

        @media (max-width:768px) {
            .util-bar { padding:5px 0; font-size:0.7rem; }
            .util-bar .util-date, .util-bar .util-access, .util-bar .txt-size { display:none; }
            .util-bar .util-inner { flex-wrap:nowrap; justify-content:space-between; }
            .util-bar .util-left { gap:6px; }
            .util-bar .helpline { font-size:0.72rem; }
            .util-bar .lang-switch a { padding:0 3px; font-size:0.7rem; }
        }

        /* ─── OFFICIAL GOVERNMENT MASTHEAD ─── */
        .gov-header { background:linear-gradient(135deg,#0a4425 0%,#0e5831 60%,#157242 100%); padding:16px 0 14px; position:relative; box-shadow:0 3px 10px rgba(0,0,0,0.12); }
        .gov-header .header-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; }
        
        .gov-header .brand-main { display:flex; align-items:center; gap:16px; }
        .gov-header .emblem-wrapper { width:62px; height:62px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 3px 10px rgba(0,0,0,0.18); padding:5px; border:2px solid #fef3c7; }
        .gov-header .emblem-wrapper img { max-width:100%; max-height:100%; object-fit:contain; }
        
        .gov-header .brand-text { color:#fff; }
        .gov-header .brand-text .sup-title { font-size:0.73rem; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#fde68a; margin-bottom:1px; }
        .gov-header .brand-text .dept-title { font-size:1.15rem; font-weight:800; line-height:1.25; margin-bottom:2px; letter-spacing:-0.2px; }
        .gov-header .brand-text .sub-script { font-size:0.78rem; opacity:0.9; color:#f1f5f9; font-weight:500; font-family:'Noto Sans Arabic',Inter,sans-serif; }

        .gov-header .portal-badge { display:flex; align-items:center; gap:14px; text-align:right; }
        .gov-header .portal-badge .flag-img { width:46px; height:31px; border-radius:3px; box-shadow:0 2px 8px rgba(0,0,0,0.25); flex-shrink:0; }
        .gov-header .portal-badge h1 { color:#fff; font-size:1.25rem; font-weight:800; margin:0; line-height:1.2; letter-spacing:-0.3px; }
        .gov-header .portal-badge .badge-sub { color:#fde68a; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }

        @media (max-width:768px) {
            .gov-header { padding:12px 0; }
            .gov-header .portal-badge { display:none; }
            .gov-header .brand-main { gap:12px; }
            .gov-header .emblem-wrapper { width:48px; height:48px; }
            .gov-header .brand-text .dept-title { font-size:0.95rem; }
        }

        /* ─── MAIN STICKY NAVIGATION ─── */
        .main-nav { background:#07351d; position:sticky; top:0; z-index:1002; box-shadow:0 2px 10px rgba(0,0,0,0.15); border-top:1px solid rgba(255,255,255,0.06); }
        .main-nav .nav-inner { display:flex; align-items:center; justify-content:space-between; }
        .main-nav .nav-list { display:flex; list-style:none; margin:0; padding:0; align-items:center; }
        .main-nav .nav-list > li > a { display:inline-flex; align-items:center; gap:6px; padding:13px 16px; color:rgba(255,255,255,0.9); font-size:0.82rem; font-weight:600; text-decoration:none; transition:all .2s; border-bottom:3px solid transparent; }
        .main-nav .nav-list > li > a:hover { color:#fff; background:rgba(255,255,255,0.05); border-bottom-color:#fcd34d; }
        .main-nav .nav-list > li.active > a { color:#fff; background:rgba(255,255,255,0.08); border-bottom-color:#fcd34d; }

        .main-nav .login-btn { background:#d97706; color:#fff !important; font-weight:700; font-size:0.8rem; padding:8px 20px; border-radius:6px; text-decoration:none; transition:all .2s; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 6px rgba(0,0,0,0.15); }
        .main-nav .login-btn:hover { background:#b45309; transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,0,0,0.2); }
        .main-nav .hamburger { display:none; background:none; border:none; color:#fff; font-size:1.3rem; padding:8px 12px; cursor:pointer; }
        .drawer-header { display:none; }
        .drawer-login { display:none; }

        @media (max-width:880px) {
            .nav-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); z-index:1001; backdrop-filter:blur(3px); }
            .nav-overlay.open { display:block; }
            .main-nav .nav-list { display:flex; flex-direction:column; position:fixed; top:0; left:-310px; width:290px; max-width:86vw; height:100vh; background:#07351d; z-index:1002; padding:0 0 24px; overflow-y:auto; transition:left .28s cubic-bezier(0.4, 0, 0.2, 1); box-shadow:4px 0 25px rgba(0,0,0,0.4); }
            .main-nav .nav-list.open { left:0; }
            .drawer-header { display:flex; align-items:center; justify-content:space-between; padding:16px 18px; border-bottom:1px solid rgba(255,255,255,0.12); background:#042113; }
            .drawer-brand { color:#fff; font-size:0.86rem; font-weight:800; display:flex; align-items:center; gap:8px; }
            .drawer-close { background:none; border:none; color:rgba(255,255,255,0.7); font-size:1.3rem; cursor:pointer; padding:4px; line-height:1; }
            .drawer-close:hover { color:#fff; }
            .main-nav .nav-list > li { width:100%; }
            .main-nav .nav-list > li > a { width:100%; padding:14px 20px; border-bottom:1px solid rgba(255,255,255,0.05); font-size:0.85rem; }
            .drawer-login { display:block; padding:14px 18px; margin-top:8px; }
            .drawer-login-btn { display:flex !important; align-items:center; justify-content:center; gap:8px; background:#d97706; color:#fff !important; font-weight:700; font-size:0.84rem; padding:12px 18px; border-radius:6px; text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.2); }
            .main-nav .hamburger { display:block; }
        }

        /* ─── ANNOUNCEMENT TICKER ─── */
        .announcement-bar { background:#fff; border-bottom:1px solid var(--gov-border); padding:8px 0; overflow:hidden; }
        .announcement-bar .ann-inner { display:flex; align-items:center; gap:12px; }
        .announcement-bar .ann-label { background:#b91c1c; color:#fff; font-size:0.68rem; font-weight:800; text-transform:uppercase; padding:3px 10px; border-radius:4px; flex-shrink:0; letter-spacing:0.5px; }
        .announcement-bar .ann-scroll { flex:1; overflow:hidden; position:relative; height:1.4em; }
        .announcement-bar .ann-track { display:inline-flex; white-space:nowrap; will-change:transform; }
        .announcement-bar .ann-track > span { font-size:0.8rem; color:var(--gov-slate-700); padding-right:80px; font-weight:500; }

        /* ─── HERO SECTION ─── */
        .hero-section {
            background: linear-gradient(135deg, rgba(7,53,29,0.94) 0%, rgba(13,74,43,0.92) 50%, rgba(16,92,54,0.90) 100%),
                        url("{{ asset('images/orchard_pulwama.jpg') }}") center/cover no-repeat;
            position:relative;
            padding:48px 0 42px;
            color:#fff;
            border-bottom:4px solid var(--gov-gold);
        }
        .hero-inner { display:flex; align-items:stretch; gap:36px; flex-wrap:wrap; }
        .hero-left { flex:1.25; min-width:320px; display:flex; flex-direction:column; justify-content:center; }
        .hero-right { flex:0.95; min-width:300px; display:flex; flex-direction:column; justify-content:center; }

        .hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(254,243,199,0.15); border:1px solid rgba(254,243,199,0.3); color:#fef3c7; padding:5px 14px; border-radius:50px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:14px; align-self:flex-start; }
        .hero-title { font-size:2.35rem; font-weight:900; line-height:1.2; letter-spacing:-0.5px; margin-bottom:14px; text-shadow:0 2px 8px rgba(0,0,0,0.25); }
        .hero-subtitle { font-size:1.02rem; line-height:1.65; opacity:0.92; color:#f1f5f9; margin-bottom:24px; max-width:620px; }

        .hero-stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(110px, 1fr)); gap:12px; margin-bottom:24px; padding:16px; background:rgba(0,0,0,0.25); border-radius:10px; border:1px solid rgba(255,255,255,0.12); backdrop-filter:blur(4px); }
        .hero-stat-item { text-align:center; }
        .hero-stat-num { font-size:1.55rem; font-weight:900; color:#fde68a; line-height:1.1; margin-bottom:2px; }
        .hero-stat-lbl { font-size:0.7rem; color:#f1f5f9; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.3px; }

        .hero-actions { display:flex; gap:12px; flex-wrap:wrap; }
        .btn-gov-primary { background:#d97706; color:#fff; font-weight:700; font-size:0.86rem; padding:12px 24px; border-radius:6px; text-decoration:none; transition:all .2s; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(0,0,0,0.2); }
        .btn-gov-primary:hover { background:#b45309; color:#fff; transform:translateY(-1px); }
        .btn-gov-outline { background:rgba(255,255,255,0.1); border:1.5px solid rgba(255,255,255,0.4); color:#fff; font-weight:600; font-size:0.86rem; padding:12px 22px; border-radius:6px; text-decoration:none; transition:all .2s; display:inline-flex; align-items:center; gap:8px; }
        .btn-gov-outline:hover { background:#fff; color:var(--gov-green-900); }

        /* ─── FARMER QUICK DESK CARD ─── */
        .farmer-desk-card { background:#fff; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.4); overflow:hidden; color:var(--gov-slate-800); }
        .farmer-desk-header { background:linear-gradient(135deg, #0d4a2b, #157343); padding:16px 20px; color:#fff; display:flex; align-items:center; justify-content:space-between; }
        .farmer-desk-header h4 { font-size:1rem; font-weight:800; margin:0; display:flex; align-items:center; gap:8px; }
        .farmer-desk-header .desk-badge { background:#d97706; color:#fff; font-size:0.65rem; font-weight:800; text-transform:uppercase; padding:3px 8px; border-radius:4px; letter-spacing:0.4px; }
        .farmer-desk-body { padding:22px; }

        .track-input-group { margin-bottom:18px; }
        .track-input-group label { display:block; font-size:0.78rem; font-weight:700; color:var(--gov-slate-700); margin-bottom:6px; }
        .track-input-wrap { display:flex; gap:8px; }
        .track-input-wrap input { flex:1; padding:11px 14px; border:1.5px solid var(--gov-border); border-radius:6px; font-size:0.88rem; outline:none; transition:border-color .15s; }
        .track-input-wrap input:focus { border-color:var(--gov-green-600); box-shadow:0 0 0 3px rgba(30,135,79,0.15); }
        .track-input-wrap button { background:var(--gov-green-700); color:#fff; border:none; padding:0 20px; border-radius:6px; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all .2s; }
        .track-input-wrap button:hover { background:var(--gov-green-800); }

        .farmer-desk-divider { display:flex; align-items:center; gap:10px; margin:16px 0; font-size:0.75rem; color:var(--gov-slate-500); font-weight:600; text-transform:uppercase; }
        .farmer-desk-divider::before, .farmer-desk-divider::after { content:''; flex:1; height:1px; background:var(--gov-border); }

        .request-banner { background:var(--gov-green-50); border:1px dashed var(--gov-green-500); border-radius:8px; padding:14px; display:flex; align-items:center; gap:12px; }
        .request-banner-icon { width:42px; height:42px; border-radius:8px; background:var(--gov-green-100); color:var(--gov-green-700); display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
        .request-banner-text { flex:1; min-width:0; }
        .request-banner-text h6 { font-size:0.84rem; font-weight:800; color:var(--gov-green-900); margin-bottom:2px; }
        .request-banner-text p { font-size:0.73rem; color:var(--gov-slate-600); margin:0; line-height:1.35; }
        .request-banner-btn { background:var(--gov-green-700); color:#fff; font-size:0.76rem; font-weight:700; padding:8px 14px; border-radius:5px; text-decoration:none; white-space:nowrap; transition:all .15s; }
        .request-banner-btn:hover { background:var(--gov-green-800); color:#fff; }

        @media (max-width:768px) {
            .hero-section { padding:28px 0; }
            .hero-title { font-size:1.65rem; }
            .hero-subtitle { font-size:0.9rem; }
            .hero-inner { gap:24px; }
            .hero-stats { grid-template-columns:1fr 1fr; gap:8px; padding:12px; }
        }

        @media (max-width:480px) {
            .hero-title { font-size:1.5rem; }
            .hero-subtitle { font-size:0.86rem; line-height:1.55; }
            .hero-badge { font-size:0.68rem; padding:4px 10px; margin-bottom:10px; }
            .hero-actions { flex-direction:column; width:100%; gap:8px; }
            .btn-gov-primary, .btn-gov-outline { width:100%; justify-content:center; padding:11px 16px; font-size:0.82rem; }
            .farmer-desk-body { padding:16px; }
            .track-input-wrap { flex-direction:column; gap:8px; }
            .track-input-wrap button { width:100%; padding:11px; justify-content:center; }
            .request-banner { flex-direction:column; text-align:center; gap:10px; }
            .request-banner-btn { width:100%; text-align:center; padding:10px; }
            .section-header h2 { font-size:1.35rem; }
            .section-header p { font-size:0.82rem; }
            .parameters-card { padding:18px 14px; }
            .leadership-card { padding:18px 14px; text-align:center; justify-content:center; }
            .leader-contacts { justify-content:center; }
            .leader-contacts span { width:100%; }
            .service-item { padding:14px; }
            .workflow-card { padding:18px 16px; }
            .info-ribbon-item { justify-content:flex-start; }
            .footer-grid { gap:24px; }
        }

        /* ─── SECTION STYLING ─── */
        .section-header { text-align:center; max-width:700px; margin:0 auto 32px; }
        .section-tag { display:inline-block; font-size:0.72rem; font-weight:800; text-transform:uppercase; color:var(--gov-green-700); letter-spacing:0.8px; margin-bottom:6px; background:var(--gov-green-100); padding:3px 12px; border-radius:50px; }
        .section-header h2 { font-size:1.75rem; font-weight:900; color:var(--gov-green-950); letter-spacing:-0.4px; line-height:1.25; margin-bottom:8px; }
        .section-header p { font-size:0.9rem; color:var(--gov-slate-600); margin:0; }

        /* ─── 3-STEP WORKFLOW ─── */
        .workflow-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:22px; margin-bottom:46px; }
        .workflow-card { background:#fff; border-radius:10px; border:1px solid var(--gov-border); padding:24px 20px; position:relative; box-shadow:0 2px 8px rgba(0,0,0,0.03); transition:all .25s; }
        .workflow-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(7,53,29,0.08); border-color:var(--gov-green-500); }
        .workflow-num { position:absolute; top:18px; right:20px; font-size:1.6rem; font-weight:900; color:var(--gov-slate-200); line-height:1; }
        .workflow-icon { width:52px; height:52px; border-radius:10px; background:var(--gov-green-100); color:var(--gov-green-700); display:flex; align-items:center; justify-content:center; font-size:1.35rem; margin-bottom:16px; }
        .workflow-card h5 { font-size:1rem; font-weight:800; color:var(--gov-green-950); margin-bottom:8px; }
        .workflow-card p { font-size:0.82rem; color:var(--gov-slate-600); line-height:1.6; margin:0; }

        /* ─── CITIZEN SERVICES DIRECTORY ─── */
        .services-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:18px; margin-bottom:48px; }
        .service-item { background:#fff; border-radius:10px; border:1px solid var(--gov-border); padding:20px 18px; display:flex; gap:16px; align-items:flex-start; text-decoration:none; color:inherit; transition:all .2s; }
        .service-item:hover { transform:translateY(-2px); border-color:var(--gov-green-600); box-shadow:0 6px 18px rgba(0,0,0,0.06); color:inherit; }
        .service-item-icon { width:46px; height:46px; border-radius:8px; background:var(--gov-green-900); color:#fde68a; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
        .service-item-info h6 { font-size:0.92rem; font-weight:800; color:var(--gov-green-950); margin-bottom:4px; }
        .service-item-info p { font-size:0.78rem; color:var(--gov-slate-600); line-height:1.5; margin-bottom:6px; }
        .service-item-link { font-size:0.75rem; font-weight:700; color:var(--gov-green-700); display:inline-flex; align-items:center; gap:4px; }

        /* ─── 12 PARAMETERS DIRECTORY ─── */
        .parameters-card { background:#fff; border-radius:12px; border:1px solid var(--gov-border); padding:28px 24px; margin-bottom:48px; box-shadow:0 2px 8px rgba(0,0,0,0.03); }
        .parameters-card .p-header { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px; border-bottom:1px solid var(--gov-border); padding-bottom:14px; }
        .parameters-card .p-header h4 { font-size:1.1rem; font-weight:800; color:var(--gov-green-950); margin:0; display:flex; align-items:center; gap:8px; }
        .parameters-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:16px; }
        .param-group { background:var(--gov-slate-50); border:1px solid var(--gov-border); border-radius:8px; padding:14px 16px; }
        .param-group h6 { font-size:0.8rem; font-weight:800; color:var(--gov-green-800); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px; display:flex; align-items:center; justify-content:space-between; }
        .param-group ul { list-style:none; padding:0; margin:0; }
        .param-group ul li { font-size:0.78rem; color:var(--gov-slate-700); padding:4px 0; border-bottom:1px dashed var(--gov-border); display:flex; justify-content:space-between; }
        .param-group ul li:last-child { border-bottom:none; }
        .param-group ul li .code { font-weight:700; color:var(--gov-slate-900); }

        /* ─── SPLIT SECTION: UPDATES & FACTS ─── */
        .split-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:48px; }
        @media (max-width:850px) { .split-grid { grid-template-columns:1fr; } }

        .content-card { background:#fff; border-radius:10px; border:1px solid var(--gov-border); box-shadow:0 2px 8px rgba(0,0,0,0.03); overflow:hidden; }
        .content-card-head { padding:14px 20px; background:var(--gov-slate-50); border-bottom:1px solid var(--gov-border); display:flex; align-items:center; justify-content:space-between; }
        .content-card-head h5 { font-size:0.92rem; font-weight:800; color:var(--gov-green-950); margin:0; display:flex; align-items:center; gap:8px; }
        .content-card-body { padding:16px 20px; }

        .update-entry { display:flex; gap:12px; padding:10px 0; border-bottom:1px dashed var(--gov-border); font-size:0.8rem; }
        .update-entry:last-child { border-bottom:none; }
        .update-dot { width:7px; height:7px; border-radius:50%; background:var(--gov-green-600); margin-top:6px; flex-shrink:0; }
        .update-entry .text { flex:1; color:var(--gov-slate-700); line-height:1.45; }
        .update-entry .date { font-size:0.7rem; color:var(--gov-slate-500); white-space:nowrap; flex-shrink:0; font-weight:600; }

        .facts-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .fact-box { background:var(--gov-green-50); border:1px solid rgba(16,92,54,0.18); border-radius:8px; padding:16px; text-align:center; }
        .fact-box .f-num { font-size:1.45rem; font-weight:900; color:var(--gov-green-800); line-height:1.1; margin-bottom:4px; }
        .fact-box .f-lbl { font-size:0.72rem; color:var(--gov-slate-600); font-weight:600; text-transform:uppercase; letter-spacing:0.4px; }

        /* ─── LEADERSHIP CARD ─── */
        .leadership-card { background:#fff; border-radius:12px; border:1px solid var(--gov-border); padding:26px 24px; margin-bottom:48px; box-shadow:0 2px 8px rgba(0,0,0,0.03); display:flex; gap:26px; align-items:center; flex-wrap:wrap; }
        .leader-insignia { width:110px; height:130px; border-radius:8px; background:linear-gradient(135deg,var(--gov-green-900),var(--gov-green-700)); border:2px solid var(--gov-gold); display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff; flex-shrink:0; text-align:center; padding:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        .leader-insignia i { font-size:2.4rem; color:#fde68a; margin-bottom:6px; }
        .leader-insignia span { font-size:0.65rem; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; opacity:0.9; }
        .leader-info { flex:1; min-width:260px; }
        .leader-info .office-tag { font-size:0.72rem; font-weight:800; text-transform:uppercase; color:var(--gov-green-700); letter-spacing:0.6px; margin-bottom:3px; }
        .leader-info h4 { font-size:1.18rem; font-weight:900; color:var(--gov-green-950); margin-bottom:4px; }
        .leader-info .post-title { font-size:0.84rem; font-weight:700; color:var(--gov-amber); margin-bottom:8px; }
        .leader-info .message { font-size:0.83rem; color:var(--gov-slate-600); line-height:1.6; margin-bottom:12px; }
        .leader-contacts { display:flex; gap:18px; flex-wrap:wrap; font-size:0.78rem; color:var(--gov-slate-600); }
        .leader-contacts span i { color:var(--gov-green-600); margin-right:5px; }

        /* ─── INFO RIBBON ─── */
        .info-ribbon { background:var(--gov-green-900); padding:20px 0; color:#fff; }
        .info-ribbon-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:18px; }
        .info-ribbon-item { display:flex; align-items:center; gap:12px; justify-content:center; text-align:left; }
        .info-ribbon-item i { font-size:1.6rem; color:#fde68a; flex-shrink:0; }
        .info-ribbon-item .label { font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; line-height:1.3; }

        /* ─── OFFICIAL GOVERNMENT FOOTER ─── */
        .site-footer { background:var(--gov-green-950); padding:36px 0 0; color:rgba(255,255,255,0.65); font-size:0.78rem; border-top:3px solid var(--gov-gold); }
        .footer-grid { display:grid; grid-template-columns:1.5fr 1fr 1fr; gap:32px; margin-bottom:32px; }
        @media (max-width:850px) { .footer-grid { grid-template-columns:1fr; } }
        .footer-col h5 { color:#fff; font-size:0.88rem; font-weight:800; margin-bottom:12px; display:flex; align-items:center; gap:8px; }
        .footer-col p { line-height:1.65; margin-bottom:12px; }
        .footer-links { list-style:none; padding:0; margin:0; }
        .footer-links li { padding:4px 0; }
        .footer-links li a { color:rgba(255,255,255,0.7); text-decoration:none; transition:color .15s; }
        .footer-links li a:hover { color:#fde68a; }
        .footer-contact li { display:flex; gap:10px; padding:4px 0; align-items:flex-start; }
        .footer-contact li i { color:#fde68a; margin-top:3px; }

        .footer-bottom { border-top:1px solid rgba(255,255,255,0.08); padding:18px 0; text-align:center; font-size:0.72rem; }
        .footer-bottom .fb-logos { display:flex; justify-content:center; align-items:center; gap:20px; margin-bottom:10px; flex-wrap:wrap; }
        .footer-bottom .fb-logos span { display:flex; align-items:center; gap:6px; color:rgba(255,255,255,0.75); font-weight:600; font-size:0.73rem; }
        .footer-bottom .fb-logos span i { color:#fde68a; }
        .footer-bottom .copyright { color:rgba(255,255,255,0.55); margin-bottom:4px; }
        .footer-bottom .disclaimer { color:rgba(255,255,255,0.35); font-size:0.68rem; max-width:800px; margin:0 auto; line-height:1.5; }

        /* ─── BACK TO TOP BUTTON ─── */
        .back-to-top { position:fixed; bottom:24px; right:24px; z-index:999; width:44px; height:44px; border-radius:50%; background:var(--gov-green-700); color:#fff; border:none; font-size:1.1rem; cursor:pointer; box-shadow:0 4px 14px rgba(0,0,0,0.25); opacity:0; visibility:hidden; transform:translateY(12px); transition:all .3s; }
        .back-to-top.show { opacity:1; visibility:visible; transform:translateY(0); }
        .back-to-top:hover { background:var(--gov-green-800); transform:translateY(-2px); }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    {{-- UTILITY ACCESSIBILITY BAR --}}
    <div class="util-bar">
        <div class="container">
            <div class="util-inner">
                <div class="util-left">
                    <span class="util-date"><i class="far fa-clock mr-1"></i> <span id="liveClock">{{ now()->format('l, d F Y') }}</span></span>
                    <a href="{{ route('accessibility') }}" class="util-access" title="Accessibility options"><i class="fas fa-universal-access mr-1"></i>Screen Reader</a>
                    <a href="#main-content" class="util-access"><i class="fas fa-arrow-down mr-1"></i>Skip to Main</a>
                    <span><i class="fas fa-phone-alt mr-1"></i>Kisan Helpline: <a href="tel:18001801551" class="helpline">1800-180-1551 (Toll-Free)</a></span>
                </div>
                <div class="util-right">
                    <div class="txt-size">
                        <span style="color:rgba(255,255,255,0.5);font-size:0.7rem;margin-right:2px;">Text Size:</span>
                        <button title="Decrease font" onclick="changeFontSize(-1)">A<sup>-</sup></button>
                        <button title="Reset font" onclick="resetFontSize()">A</button>
                        <button title="Increase font" onclick="changeFontSize(1)">A<sup>+</sup></button>
                    </div>
                    <div class="lang-switch">
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ session('locale') === 'en' || !session('locale') ? 'active' : '' }}">English</a><span class="sep"> | </span>
                        <a href="{{ route('lang.switch', 'hi') }}" class="{{ session('locale') === 'hi' ? 'active' : '' }}">हिंदी</a><span class="sep"> | </span>
                        <a href="{{ route('lang.switch', 'ur') }}" class="{{ session('locale') === 'ur' ? 'active' : '' }}">اردو</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- OFFICIAL GOVERNMENT MASTHEAD --}}
    <header class="gov-header">
        <div class="container">
            <div class="header-inner">
                <div class="brand-main">
                    <div class="emblem-wrapper" title="National Emblem of India">
                        <img src="{{ asset('images/emblem_india.svg') }}" alt="Emblem of India">
                    </div>
                    <div class="brand-text">
                        <div class="sup-title">{{ setting('front.header_ministry', 'Government of Jammu & Kashmir (UT)') }}</div>
                        <div class="dept-title">{{ setting('front.header_dept_en', 'Department of Horticulture') }}</div>
                        <div class="sub-script">
                            <span>{{ setting('front.header_dept_ur', 'محکمہ باغبانی، جموں و کشمیر') }}</span> &bull; 
                            <span>{{ setting('front.header_dept_hi', 'बागवानी विभाग, जम्मू एवं कश्मीर') }}</span>
                        </div>
                    </div>
                </div>

                <div class="portal-badge">
                    <img src="{{ asset('images/flag_india.svg') }}" alt="Tricolor Flag of India" class="flag-img">
                    <div>
                        <h1>{{ setting('app_name', 'Soil Testing Laboratory') }}</h1>
                        <div class="badge-sub">District Pulwama &bull; Soil Health &amp; Fertility Management</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN STICKY NAVIGATION --}}
    <div class="nav-overlay" id="navOverlay"></div>
    <nav class="main-nav" aria-label="Main navigation" id="mainNav">
        <div class="container">
            <div class="nav-inner">
                <button class="hamburger" id="hamburger" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
                <ul class="nav-list" id="navList">
                    <li class="drawer-header">
                        <div class="drawer-brand">
                            <img src="{{ asset('images/emblem_india.svg') }}" alt="Emblem" style="width:24px;height:24px;background:#fff;border-radius:50%;padding:2px;">
                            <span>Soil Testing Lab</span>
                        </div>
                        <button class="drawer-close" id="drawerClose" aria-label="Close navigation"><i class="fas fa-times"></i></button>
                    </li>
                    <li class="active"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="#services"><i class="fas fa-th-large"></i> Citizen Services</a></li>
                    <li><a href="#workflow"><i class="fas fa-route"></i> How It Works</a></li>
                    <li><a href="#parameters"><i class="fas fa-vials"></i> Testing Parameters</a></li>
                    <li><a href="{{ route('public.track') }}"><i class="fas fa-file-medical-alt"></i> Track Soil Report</a></li>
                    <li><a href="{{ route('public.sample-request') }}"><i class="fas fa-clipboard-check"></i> Request Sample Collection</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fas fa-map-marker-alt"></i> Contact &amp; Labs</a></li>
                    <li class="drawer-login">
                        <a href="{{ route('login') }}" class="drawer-login-btn"><i class="fas fa-shield-alt"></i> Official Login</a>
                    </li>
                </ul>
                <div class="nav-right">
                    <a href="{{ route('login') }}" class="login-btn"><i class="fas fa-shield-alt"></i> Official Login</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- OFFICIAL ANNOUNCEMENT TICKER --}}
    @php
        $notices = json_decode(setting('front.notices', '[]'), true) ?: [
            'All soil testing services are provided 100% free of cost to orchardists and farmers under the Soil Health Card Scheme.',
            'Farmers are advised to collect and submit soil samples before applying fertilizers for upcoming seasonal crop cycles.',
            'Submit your soil sample collection request online or contact your nearest Zonal Horticulture Office (Tral, Pampore, Kakapora).'
        ];
    @endphp
    <div class="announcement-bar">
        <div class="container">
            <div class="ann-inner">
                <span class="ann-label"><i class="fas fa-bullhorn mr-1"></i> Notice</span>
                <div class="ann-scroll">
                    <div class="ann-track" id="annTrack">
                        @foreach($notices as $notice)
                            <span>&bull; {{ $notice }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- HERO SECTION & FARMER QUICK DESK --}}
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="hero-inner">
                
                {{-- LEFT: HERO PROPOSITION & LIVE DATA --}}
                <div class="hero-left">
                    <div class="hero-badge">
                        <i class="fas fa-seedling"></i> {!! html_entity_decode(setting('front.hero_tag', 'Soil Health Management Initiative • Pulwama District')) !!}
                    </div>
                    <h2 class="hero-title">{{ setting('front.hero_title', 'Scientific Soil Testing for Better Orchard & Farm Yield') }}</h2>
                    <p class="hero-subtitle">{{ setting('front.hero_subtitle', 'Comprehensive 12-parameter nutrient analysis and crop-specific fertilizer recommendations for Apple, Saffron, Walnut, and Field crops. Free testing for all farmers across Pulwama district.') }}</p>

                    {{-- LIVE REPOSITORIES TRANSPARENCY --}}
                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <div class="hero-stat-num">{{ number_format($total ?? 0) }}</div>
                            <div class="hero-stat-lbl">Samples Collected</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-num">{{ number_format($reported ?? 0) }}</div>
                            <div class="hero-stat-lbl">Soil Cards Issued</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-num">{{ number_format($farmers ?? 0) }}</div>
                            <div class="hero-stat-lbl">Registered Farmers</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-num">{{ number_format($pending ?? 0) }}</div>
                            <div class="hero-stat-lbl">Testing Pipeline</div>
                        </div>
                    </div>

                    <div class="hero-actions">
                        <a href="{{ route('public.sample-request') }}" class="btn-gov-primary"><i class="fas fa-clipboard-list"></i> Request Free Soil Test</a>
                        <a href="{{ route('public.track') }}" class="btn-gov-outline"><i class="fas fa-file-pdf"></i> Download Soil Health Card</a>
                    </div>
                </div>

                {{-- RIGHT: FARMER QUICK DESK CARD --}}
                <div class="hero-right">
                    <div class="farmer-desk-card">
                        <div class="farmer-desk-header">
                            <h4><i class="fas fa-search-location"></i> Farmer Quick Desk</h4>
                            <span class="desk-badge">Instant Access</span>
                        </div>
                        <div class="farmer-desk-body">
                            {{-- INSTANT REPORT SEARCH --}}
                            <form action="{{ route('public.track.lookup') }}" method="POST" class="track-input-group">
                                @csrf
                                <label for="phone"><i class="fas fa-mobile-alt mr-1 text-success"></i> Download Your Soil Health Card</label>
                                <div class="track-input-wrap">
                                    <input type="tel" name="phone" id="phone" placeholder="Enter 10-digit mobile number" required pattern="[0-9]{10}" maxlength="10">
                                    <button type="submit"><i class="fas fa-search mr-1"></i> Search</button>
                                </div>
                                <small style="display:block;margin-top:6px;font-size:0.72rem;color:var(--gov-slate-500);">
                                    <i class="fas fa-info-circle mr-1 text-info"></i> Instantly view and download digital Soil Health Card with crop fertilizer advisory.
                                </small>
                            </form>

                            <div class="farmer-desk-divider">or request collection</div>

                            {{-- REQUEST COLLECTION BANNER --}}
                            <div class="request-banner">
                                <div class="request-banner-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="request-banner-text">
                                    <h6>Need Orchard Soil Tested?</h6>
                                    <p>Designated zonal field official will visit your farm with GPS tagging.</p>
                                </div>
                                <a href="{{ route('public.sample-request') }}" class="request-banner-btn">Book Visit &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main id="main-content" style="padding:46px 0 20px;">
        <div class="container">

            {{-- ─── 3-STEP CITIZEN WORKFLOW ─── --}}
            <div id="workflow" style="margin-bottom:20px;">
                <div class="section-header">
                    <span class="section-tag">Standard Operating Procedure</span>
                    <h2>How Soil Testing Works</h2>
                    <p>Transparent, scientific, and 100% free process for all farmers and orchardists across Pulwama district.</p>
                </div>
                <div class="workflow-grid">
                    <div class="workflow-card">
                        <span class="workflow-num">01</span>
                        <div class="workflow-icon"><i class="fas fa-map-marked-alt"></i></div>
                        <h5>GPS-Tagged Sample Collection</h5>
                        <p>Field officials visit your orchard or farm parcel, take representative core samples (0–15 cm depth) from root zones, and register GPS coordinates on the official mobile app.</p>
                    </div>
                    <div class="workflow-card">
                        <span class="workflow-num">02</span>
                        <div class="workflow-icon"><i class="fas fa-flask"></i></div>
                        <h5>12-Parameter Laboratory Analysis</h5>
                        <p>Soil samples are processed at District Soil Testing Laboratory Pulwama. Chemical analysts assess soil pH, EC, Organic Carbon, Macro-nutrients (N, P, K), Secondary (S), and Micronutrients.</p>
                    </div>
                    <div class="workflow-card">
                        <span class="workflow-num">03</span>
                        <div class="workflow-icon"><i class="fas fa-file-medical-alt"></i></div>
                        <h5>Digital Soil Health Card &amp; Advisory</h5>
                        <p>Farmers receive an official QR-verifiable Soil Health Card containing fertility status ratings and scientific fertilizer application doses customized for their specific fruit and field crops.</p>
                    </div>
                </div>
            </div>

            {{-- ─── CITIZEN SERVICES DIRECTORY ─── --}}
            <div id="services" style="margin-bottom:24px;">
                <div class="section-header">
                    <span class="section-tag">Departmental Capabilities</span>
                    <h2>Citizen Services &amp; Laboratory Support</h2>
                    <p>Dedicated horticultural services delivered by the Department of Horticulture, Pulwama.</p>
                </div>
                <div class="services-grid">
                    <a href="{{ route('public.sample-request') }}" class="service-item">
                        <div class="service-item-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <div class="service-item-info">
                            <h6>Doorstep Sample Collection</h6>
                            <p>Request field official visit to your orchard. Collection conducted using GPS location geotagging.</p>
                            <span class="service-item-link">Request Collection <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="#parameters" class="service-item">
                        <div class="service-item-icon"><i class="fas fa-vials"></i></div>
                        <div class="service-item-info">
                            <h6>12-Parameter Lab Analysis</h6>
                            <p>High-precision chemical analysis of soil fertility including NPK, pH, electrical conductivity, and micronutrients.</p>
                            <span class="service-item-link">View Parameters <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="{{ route('public.track') }}" class="service-item">
                        <div class="service-item-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="service-item-info">
                            <h6>Digital Soil Health Card</h6>
                            <p>Instant PDF download of official card with barcode/QR verification and personalized crop guidelines.</p>
                            <span class="service-item-link">Download Card <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="#services" class="service-item">
                        <div class="service-item-icon"><i class="fas fa-apple-alt"></i></div>
                        <div class="service-item-info">
                            <h6>Crop-Wise Fertilizer Advisory</h6>
                            <p>Balanced nutrient recommendations tailored for high-density Apple, Saffron, Walnut, Almond, and Paddy.</p>
                            <span class="service-item-link">Nutrient Guidelines <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="{{ route('contact') }}" class="service-item">
                        <div class="service-item-icon"><i class="fas fa-building"></i></div>
                        <div class="service-item-info">
                            <h6>Zonal Offices Directory</h6>
                            <p>Access designated zonal officers and field laboratories in Tral Zone, Pampore Zone, and Kakapora Zone.</p>
                            <span class="service-item-link">Find Nearest Office <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="{{ route('public.track') }}" class="service-item">
                        <div class="service-item-icon"><i class="fas fa-qrcode"></i></div>
                        <div class="service-item-info">
                            <h6>QR-Code Verification</h6>
                            <p>Verify sample status and authentic laboratory report validity instantly using unique sample QR tracking.</p>
                            <span class="service-item-link">Track By Code <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ─── 12 PARAMETERS TESTED DIRECTORY ─── --}}
            <div id="parameters" class="parameters-card">
                <div class="p-header">
                    <h4><i class="fas fa-microscope text-success"></i> Official 12 Chemical Soil Parameters Tested</h4>
                    <span style="font-size:0.75rem;font-weight:700;color:var(--gov-slate-600);background:var(--gov-slate-100);padding:4px 12px;border-radius:4px;">Government of India Standard Protocol</span>
                </div>
                <div class="parameters-grid">
                    <div class="param-group">
                        <h6>Physical &amp; Chemical <span>(3)</span></h6>
                        <ul>
                            <li><span>Soil Reaction (Acidity/Alkalinity)</span> <span class="code">pH</span></li>
                            <li><span>Electrical Conductivity (Salinity)</span> <span class="code">EC</span></li>
                            <li><span>Organic Carbon (Soil Fertility)</span> <span class="code">OC</span></li>
                        </ul>
                    </div>
                    <div class="param-group">
                        <h6>Primary Macro-Nutrients <span>(3)</span></h6>
                        <ul>
                            <li><span>Available Nitrogen</span> <span class="code">N</span></li>
                            <li><span>Available Phosphorus</span> <span class="code">P</span></li>
                            <li><span>Available Potassium</span> <span class="code">K</span></li>
                        </ul>
                    </div>
                    <div class="param-group">
                        <h6>Secondary Nutrient <span>(1)</span></h6>
                        <ul>
                            <li><span>Available Sulphur</span> <span class="code">S</span></li>
                        </ul>
                    </div>
                    <div class="param-group">
                        <h6>Essential Micro-Nutrients <span>(5)</span></h6>
                        <ul>
                            <li><span>Zinc &bull; Boron</span> <span class="code">Zn, B</span></li>
                            <li><span>Iron &bull; Manganese</span> <span class="code">Fe, Mn</span></li>
                            <li><span>Copper</span> <span class="code">Cu</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ─── SPLIT SECTION: UPDATES & DISTRICT FACTS ─── --}}
            <div class="split-grid">
                
                {{-- LATEST ORDERS & NOTICES --}}
                <div class="content-card">
                    <div class="content-card-head">
                        <h5><i class="fas fa-newspaper text-success"></i> Latest Updates &amp; Advisories</h5>
                        <a href="{{ route('public.track') }}" style="font-size:0.75rem;font-weight:700;color:var(--gov-green-700);">View All</a>
                    </div>
                    <div class="content-card-body">
                        @php
                            $updates = json_decode(setting('front.updates', '[]'), true) ?: [
                                ['text' => 'Soil sample collection camps scheduled across Pulwama district blocks — orchardists are invited to participate.', 'date' => '15 Oct 2026'],
                                ['text' => 'Advisory issued on balanced micronutrient management for apple orchards and saffron fields.', 'date' => '02 Oct 2026'],
                                ['text' => 'High-precision testing equipment operational at District Soil Testing Laboratory, Pulwama.', 'date' => '25 Sep 2026'],
                                ['text' => 'Capacity-building and farmer training workshop on Integrated Nutrient Management held at Kakapora.', 'date' => '10 Sep 2026'],
                            ];
                        @endphp
                        @foreach($updates as $upd)
                            <div class="update-entry">
                                <span class="update-dot"></span>
                                <div class="text">{{ $upd['text'] ?? '' }}</div>
                                @if(!empty($upd['date']))
                                    <div class="date">{{ $upd['date'] }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- DISTRICT HORTICULTURE FACTS --}}
                <div class="content-card">
                    <div class="content-card-head">
                        <h5><i class="fas fa-chart-pie text-success"></i> Horticulture Profile — Pulwama District</h5>
                        <span style="font-size:0.72rem;font-weight:700;color:var(--gov-slate-500);">Official Statistics</span>
                    </div>
                    <div class="content-card-body">
                        <div class="facts-grid">
                            <div class="fact-box">
                                <div class="f-num">{{ setting('front.fact1_value', '14×') }}</div>
                                <div class="f-lbl">{{ setting('front.fact1_label', 'Growth Since 1972') }}</div>
                            </div>
                            <div class="fact-box">
                                <div class="f-num">{{ setting('front.fact2_value', '24.94 LMT') }}</div>
                                <div class="f-lbl">{{ setting('front.fact2_label', 'Annual Fruit Yield') }}</div>
                            </div>
                            <div class="fact-box">
                                <div class="f-num">{{ setting('front.fact3_value', '23 Lakh+') }}</div>
                                <div class="f-lbl">{{ setting('front.fact3_label', 'Persons Supported') }}</div>
                            </div>
                            <div class="fact-box">
                                <div class="f-num">{{ setting('front.fact4_value', '₹6,300 Cr') }}</div>
                                <div class="f-lbl">{{ setting('front.fact4_label', 'Annual Turnover') }}</div>
                            </div>
                        </div>
                        <div style="margin-top:14px;padding:10px 12px;background:var(--gov-slate-50);border-radius:6px;border:1px solid var(--gov-border);font-size:0.74rem;color:var(--gov-slate-600);line-height:1.45;">
                            <i class="fas fa-shield-alt text-success mr-1"></i> Pulwama is the premier horticulture hub of Jammu &amp; Kashmir, renowned for world-class Kashmiri Apple, authentic Saffron (GI Tagged), Walnut, and Almond production.
                        </div>
                    </div>
                </div>

            </div>

            {{-- ─── LEADERSHIP & DISTRICT ADMINISTRATION ─── --}}
            <div class="leadership-card">
                <div class="leader-insignia">
                    <i class="fas fa-landmark"></i>
                    <span>Official Seal</span>
                    <span style="font-size:0.58rem;opacity:0.8;">Horticulture J&amp;K</span>
                </div>
                <div class="leader-info">
                    <div class="office-tag">District Administration</div>
                    <h4>{{ setting('front.cho_name') ?: 'Chief Horticulture Officer' }}</h4>
                    <div class="post-title">{{ setting('front.cho_title', 'Chief Horticulture Officer') }}, {{ setting('front.cho_post', 'District Pulwama') }}</div>
                    <p class="message">{{ setting('front.cho_description', 'The Department of Horticulture, Pulwama is committed to promoting sustainable and high-yield farming through scientific soil analysis, balanced nutrient management, and modern horticultural practices. All orchardists and farmers across Tral, Pampore, Kakapora, and Pulwama are encouraged to avail free soil testing services to optimize fertilizer use and safeguard soil fertility.') }}</p>
                    <div class="leader-contacts">
                        <span><i class="fas fa-map-marker-alt"></i> {{ setting('front.cho_address', 'Mini Secretariat, Pulwama, J&K 192301') }}</span>
                        <span><i class="fas fa-envelope"></i> {{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}</span>
                        <span><i class="fas fa-phone-alt"></i> {{ setting('front.cho_phone', '+91-1933-241234') }}</span>
                        <span><i class="fas fa-clock"></i> {{ setting('front.working_hours', 'Monday – Friday: 9:00 AM – 5:00 PM') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- OFFICIAL INFORMATION RIBBON --}}
    <div class="info-ribbon">
        <div class="container">
            <div class="info-ribbon-grid">
                <div class="info-ribbon-item">
                    <i class="fas fa-award"></i>
                    <div class="label">100% Free Testing<br><span style="font-size:0.68rem;opacity:0.75;font-weight:500;">Soil Health Card Scheme</span></div>
                </div>
                <div class="info-ribbon-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <div class="label">GPS Geotagged Parcels<br><span style="font-size:0.68rem;opacity:0.75;font-weight:500;">Exact Root-Zone Sampling</span></div>
                </div>
                <div class="info-ribbon-item">
                    <i class="fas fa-vials"></i>
                    <div class="label">12 Parameter Analysis<br><span style="font-size:0.68rem;opacity:0.75;font-weight:500;">Macro, Secondary &amp; Micro</span></div>
                </div>
                <div class="info-ribbon-item">
                    <i class="fas fa-qrcode"></i>
                    <div class="label">Digital Soil Health Card<br><span style="font-size:0.68rem;opacity:0.75;font-weight:500;">QR-Verifiable PDF Report</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- OFFICIAL GOVERNMENT FOOTER --}}
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h5><i class="fas fa-university"></i> {{ setting('app_name', 'Soil Testing Laboratory, Pulwama') }}</h5>
                    <p>{{ setting('front.footer_about', 'Official portal of the Soil Testing Laboratory, District Pulwama under the Department of Horticulture, Government of Jammu & Kashmir (UT). Implemented under the Soil Health Card Scheme, Ministry of Agriculture & Farmers Welfare, Government of India.') }}</p>
                    <div style="font-size:0.73rem;color:rgba(255,255,255,0.7);">
                        <strong>Toll Free Kisan Call Centre:</strong> <span style="color:#fde68a;">1800-180-1551</span>
                    </div>
                </div>

                <div class="footer-col">
                    <h5><i class="fas fa-link"></i> Important Portals</h5>
                    <ul class="footer-links">
                        <li><a href="https://soilhealth.dac.gov.in/" target="_blank" rel="noopener"><i class="fas fa-external-link-alt mr-1"></i> National Soil Health Card Portal</a></li>
                        <li><a href="http://horticulture.jk.gov.in/" target="_blank" rel="noopener"><i class="fas fa-external-link-alt mr-1"></i> Horticulture Department J&amp;K</a></li>
                        <li><a href="https://jansugam.jk.gov.in/" target="_blank" rel="noopener"><i class="fas fa-external-link-alt mr-1"></i> Jan Sugam Portal J&amp;K</a></li>
                        <li><a href="{{ route('public.sample-request') }}"><i class="fas fa-chevron-right mr-1"></i> Request Soil Testing Online</a></li>
                        <li><a href="{{ route('public.track') }}"><i class="fas fa-chevron-right mr-1"></i> Download Soil Health Card</a></li>
                        <li><a href="{{ route('accessibility') }}"><i class="fas fa-universal-access mr-1"></i> Accessibility Statement</a></li>
                    </ul>
                </div>

                <div class="footer-col footer-contact">
                    <h5><i class="fas fa-address-book"></i> Laboratory &amp; Office</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> <span>{{ setting('front.cho_address', 'Mini Secretariat, Pulwama, J&K 192301') }}</span></li>
                        <li><i class="fas fa-envelope"></i> <span>{{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}</span></li>
                        <li><i class="fas fa-phone"></i> <span>{{ setting('front.cho_phone', '+91-1933-241234') }}</span></li>
                        <li><i class="fas fa-clock"></i> <span>Monday – Friday: 9:00 AM – 5:00 PM</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="fb-logos">
                    <span><i class="fas fa-check-circle"></i> Government of Jammu &amp; Kashmir (UT)</span>
                    <span><i class="fas fa-leaf"></i> Soil Health Card Scheme</span>
                    <span><i class="fas fa-award"></i> Department of Horticulture</span>
                    <span><i class="fas fa-lock"></i> Official Public Service Portal</span>
                </div>
                <div class="copyright">
                    {!! setting('front.footer_copyright', '&copy; ' . date('Y') . ' Soil Testing Laboratory, Pulwama &mdash; Government of Jammu & Kashmir (UT)') !!}
                    @if($v = setting('app_version', '1.0.4'))
                        &nbsp;|&nbsp; Version {{ $v }}
                    @endif
                </div>
                <div class="disclaimer">
                    {{ setting('front.footer_disclaimer', 'This official portal is published and maintained by the Department of Horticulture, District Pulwama, Government of Jammu & Kashmir (UT) for citizen service and public information.') }}
                </div>
                <div style="margin-top:6px;">
                    <span style="opacity:0.45;font-size:0.65rem;">Designed and Developed by <a href="https://theumer.store" target="_blank" rel="noopener" style="color:rgba(255,255,255,0.6);text-decoration:underline;">Umer Bashir</a></span>
                </div>
            </div>
        </div>
    </footer>

    {{-- BACK TO TOP BUTTON --}}
    <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

    <script>
        // ─── MOBILE DRAWER NAVIGATION ───
        (function() {
            var hamburger = document.getElementById('hamburger');
            var navList = document.getElementById('navList');
            var overlay = document.getElementById('navOverlay');
            if (!hamburger || !navList || !overlay) return;

            function closeNav() {
                navList.classList.remove('open');
                overlay.classList.remove('open');
            }
            function openNav() {
                navList.classList.add('open');
                overlay.classList.add('open');
            }

            hamburger.addEventListener('click', function() {
                if (navList.classList.contains('open')) closeNav();
                else openNav();
            });
            overlay.addEventListener('click', closeNav);
            var closeBtn = document.getElementById('drawerClose');
            if (closeBtn) closeBtn.addEventListener('click', closeNav);
            document.querySelectorAll('#navList a').forEach(function(a) {
                a.addEventListener('click', closeNav);
            });
        })();

        // ─── ANNOUNCEMENT MARQUEE ───
        (function() {
            var track = document.getElementById('annTrack');
            if (!track) return;
            var spans = track.children;
            if (spans.length < 2) return;
            var pos = 0, speed = 0.55;
            function step() {
                pos -= speed;
                var first = spans[0];
                var firstW = first.offsetWidth + 60;
                if (pos <= -firstW) {
                    pos += firstW;
                    track.appendChild(first);
                }
                track.style.transform = 'translateX(' + pos + 'px)';
                requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        })();

        // ─── FONT SIZE CONTROLS ───
        function changeFontSize(delta) {
            var html = document.documentElement;
            var current = parseInt(html.style.fontSize) || 100;
            var next = Math.min(Math.max(current + delta * 10, 80), 140);
            html.style.fontSize = next + '%';
            html.classList.add('font-adjusted');
            localStorage.setItem('fontSize', next);
        }
        function resetFontSize() {
            document.documentElement.style.fontSize = '';
            document.documentElement.classList.remove('font-adjusted');
            localStorage.removeItem('fontSize');
        }
        (function() {
            var size = localStorage.getItem('fontSize');
            if (size) {
                document.documentElement.style.fontSize = size + '%';
                document.documentElement.classList.add('font-adjusted');
            }
        })();

        // ─── LIVE DATE TICKER ───
        (function() {
            var el = document.getElementById('liveClock');
            if (!el) return;
            var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            function tick() {
                var d = new Date();
                el.textContent = days[d.getDay()] + ', ' + String(d.getDate()).padStart(2, '0') + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
            }
            tick();
            setInterval(tick, 60000);
        })();

        // ─── BACK TO TOP SCROLL BUTTON ───
        (function() {
            var btn = document.getElementById('backToTop');
            if (!btn) return;
            window.addEventListener('scroll', function() {
                btn.classList.toggle('show', window.scrollY > 350);
            }, { passive: true });
            btn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>
</body>
</html>
