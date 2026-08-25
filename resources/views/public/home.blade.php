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
    <title>{{ $metaTitle ?: setting('app_name', 'Soil Lab') . (setting('app_description', '') ? ' &mdash; ' . setting('app_description', '') : '') }}</title>
    @if($metaDesc)<meta name="description" content="{{ $metaDesc }}">@endif
    @if($metaKw)<meta name="keywords" content="{{ $metaKw }}">@endif
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $metaTitle ?: setting('app_name', 'Soil Lab') }}">
    @if($metaDesc)<meta property="og:description" content="{{ $metaDesc }}">@endif
    @if($ogUrl)<meta property="og:image" content="{{ $ogUrl }}">
    <meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">@endif
    <meta name="twitter:card" content="summary_large_image">
    @if($ogUrl)<meta name="twitter:image" content="{{ $ogUrl }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        :root {
            --green-900: #072b17;
            --green-800: #0a3d22;
            --green-700: #0a4b2a;
            --green-600: #0a5a2a;
            --green-500: #018840;
            --green-400: #28a563;
            --green-300: #5bc08a;
            --green-200: #c8e6c9;
            --green-100: #e8f5e9;
            --amber: #f3b229;
            --amber-dark: #d4941a;
            --gray-50: #f8f9fb;
            --gray-100: #eef1f5;
            --gray-200: #dce1e8;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --red: #c62828;
            --font-base: 100%;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; font-size: var(--font-base); }
        body { font-family:'Inter',sans-serif; background:var(--gray-50); color:var(--gray-800); overflow-x:hidden; }
        body.font-adjusted { --font-base: inherit; }

        .skip-link { position:absolute; top:-100px; left:0; background:var(--green-500); color:#fff; padding:8px 16px; z-index:9999; font-weight:600; text-decoration:none; }
        .skip-link:focus { top:0; }

        /* ─── UTILITY BAR ─── */
        .util-bar { background:var(--green-900); padding:6px 0; font-size:.7rem; }
        .util-bar .util-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px; }
        .util-bar .util-left { display:flex; align-items:center; gap:14px; color:rgba(255,255,255,.55); }
        .util-bar .util-left a { color:rgba(255,255,255,.55); text-decoration:none; transition:color .15s; }
        .util-bar .util-left a:hover { color:var(--amber); }
        .util-bar .util-right { display:flex; align-items:center; gap:10px; }
        .util-bar .txt-size { display:flex; align-items:center; gap:4px; }
        .util-bar .txt-size button { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); color:rgba(255,255,255,.6); padding:1px 8px; border-radius:4px; cursor:pointer; font-size:.68rem; transition:.15s; }
        .util-bar .txt-size button:hover { background:rgba(255,255,255,.18); color:#fff; }
        .util-bar .lang-switch a { color:rgba(255,255,255,.5); text-decoration:none; padding:0 4px; font-size:.68rem; transition:color .15s; }
        .util-bar .lang-switch a:hover, .util-bar .lang-switch a.active { color:var(--amber); }
        .util-bar .lang-switch .sep { color:rgba(255,255,255,.2); }

        /* ─── GOVT HEADER ─── */
        .gov-header { background:linear-gradient(135deg,var(--green-700) 0%,var(--green-500) 100%); padding:16px 0 14px; position:relative; overflow:hidden; }
        .gov-header::after { content:''; position:absolute; top:-50%; right:-20%; width:400px; height:400px; background:rgba(255,255,255,.03); border-radius:50%; pointer-events:none; }
        .gov-header .header-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; position:relative; z-index:1; }
        .gov-header .brand-group { display:flex; align-items:center; gap:14px; }
        .gov-header .emblem { width:54px; height:54px; background:rgba(255,255,255,.1); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; backdrop-filter:blur(4px); }
        .gov-header .emblem img { width:42px; height:42px; }
        .gov-header .flag-img { width:44px; border-radius:2px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .gov-header .portal-title { text-align:right; }
        .gov-header .portal-title h1 { color:#fff; font-size:1.2rem; font-weight:800; margin:0; line-height:1.2; letter-spacing:-.3px; }
        .gov-header .portal-title .tagline { color:var(--amber); font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
        .gov-header .logo-img { max-height:46px; }
        .gov-header .govt-meta { color:rgba(255,255,255,.9); }
        .gov-header .govt-meta .ministry { font-size:.65rem; font-weight:500; text-transform:uppercase; letter-spacing:.4px; opacity:.7; }
        .gov-header .govt-meta .dept-en { font-size:.82rem; font-weight:700; }
        .gov-header .govt-meta .dept-urdu { font-size:.88rem; font-weight:600; opacity:.65; direction:rtl; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; }
        .gov-header .govt-meta .dept-hindi { font-size:.68rem; font-weight:500; opacity:.5; }

        @media (min-width:768px) {
            .gov-header { padding:12px 0 10px; }
            .gov-header .header-inner { gap:10px; }
            .gov-header .brand-group { gap:10px; }
            .gov-header .emblem { width:48px; height:48px; }
            .gov-header .emblem img { width:36px; height:36px; }
            .gov-header .flag-img { width:36px; }
            .gov-header .portal-title h1 { font-size:1.2rem; }
        }
        @media (max-width:480px) {
            .gov-header { padding:8px 0; }
            .gov-header .header-inner { gap:6px; flex-direction:column; align-items:flex-start; }
            .gov-header .brand-group { gap:8px; width:100%; justify-content:flex-start; }
            .gov-header .emblem { width:36px; height:36px; }
            .gov-header .emblem img { width:28px; height:28px; }
            .gov-header .flag-img { display:none; }
            .gov-header .govt-meta .ministry { font-size:.55rem; }
            .gov-header .govt-meta .dept-en { font-size:.68rem; }
            .gov-header .govt-meta .dept-urdu { font-size:.72rem; }
            .gov-header .govt-meta .dept-hindi { font-size:.6rem; }
            .gov-header .portal-title { display:none; }
        }

        /* ─── STICKY NAV ─── */
        .main-nav { background:linear-gradient(180deg,var(--green-800) 0%,#06331b 100%); position:sticky; top:0; z-index:1000; transition:box-shadow .3s; }
        .main-nav::after { content:''; position:absolute; bottom:0; left:0; right:0; height:1px; background:rgba(255,255,255,.06); }
        .main-nav.scrolled { box-shadow:0 4px 20px rgba(0,0,0,.15); }
        .main-nav .nav-inner { display:flex; align-items:center; justify-content:space-between; }
        .main-nav .nav-list { display:flex; list-style:none; margin:0; padding:0; }
        .main-nav .nav-list > li { position:relative; }
        .main-nav .nav-list > li > a { display:flex; align-items:center; gap:6px; padding:14px 18px; color:rgba(255,255,255,.85); font-size:.78rem; font-weight:500; text-decoration:none; white-space:nowrap; position:relative; transition:color .2s; }
        .main-nav .nav-list > li > a::after { content:''; position:absolute; bottom:0; left:50%; right:50%; height:3px; background:var(--amber); border-radius:3px 3px 0 0; transition:left .2s,right .2s; }
        .main-nav .nav-list > li > a:hover { color:#fff; }
        .main-nav .nav-list > li > a:hover::after { left:18px; right:18px; }
        .main-nav .nav-list > li.active > a { color:#fff; }
        .main-nav .nav-list > li.active > a::after { left:18px; right:18px; }
        .main-nav .nav-list > li > a .arrow { font-size:.5rem; opacity:.5; transition:transform .2s; }
        .main-nav .nav-list > li:hover > a .arrow { transform:rotate(180deg); }
        .main-nav .dropdown-menu-custom { display:none; position:absolute; top:100%; left:0; background:#fff; min-width:220px; border-radius:10px; box-shadow:0 12px 40px rgba(0,0,0,.15); z-index:200; padding:8px 0; opacity:0; transform:translateY(8px); transition:all .2s; }
        .main-nav .dropdown-menu-custom::before { content:''; position:absolute; top:-6px; left:24px; width:12px; height:12px; background:#fff; transform:rotate(45deg); border-radius:2px; }
        .main-nav .dropdown-trigger:hover .dropdown-menu-custom,
        .main-nav .dropdown-menu-custom.show { display:block; opacity:1; transform:translateY(0); }
        .main-nav .dropdown-menu-custom a { display:flex; align-items:center; gap:10px; padding:9px 18px; color:var(--gray-700); font-size:.77rem; text-decoration:none; transition:all .12s; border-left:3px solid transparent; }
        .main-nav .dropdown-menu-custom a:hover { background:var(--green-100); color:var(--green-500); border-left-color:var(--green-500); }
        .main-nav .nav-right { display:flex; align-items:center; gap:10px; }
        .main-nav .login-btn { background:var(--amber); color:var(--green-800) !important; padding:8px 22px; border-radius:20px; font-weight:700; font-size:.76rem; text-decoration:none; transition:all .2s; white-space:nowrap; }
        .main-nav .login-btn:hover { background:#fff; transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.15); }
        .main-nav .hamburger { display:none; background:none; border:none; color:#fff; font-size:1.3rem; padding:10px 14px; cursor:pointer; transition:transform .2s; }
        .main-nav .hamburger.open { transform:rotate(90deg); }

        /* Mobile nav drawer */
        .nav-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:999; cursor:pointer; }
        .nav-overlay.open { display:block; }
        @media (max-width:768px) {
            .main-nav .nav-list { display:none; flex-direction:column; width:280px; background:linear-gradient(180deg,var(--green-800) 0%,#052b16 100%); position:fixed; top:0; left:-280px; height:100vh; z-index:1000; padding:0 0 20px; overflow-y:auto; transition:left .3s ease; box-shadow:4px 0 30px rgba(0,0,0,.25); }
            .main-nav .nav-list.open { left:0; display:flex; }
            .main-nav .nav-list .drawer-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid rgba(255,255,255,.06); margin-bottom:8px; }
            .main-nav .nav-list .drawer-header .drawer-brand { color:#fff; font-size:.85rem; font-weight:700; }
            .main-nav .nav-list .drawer-header .drawer-close { background:none; border:none; color:rgba(255,255,255,.6); font-size:1.2rem; cursor:pointer; padding:4px; transition:color .15s; }
            .main-nav .nav-list .drawer-header .drawer-close:hover { color:#fff; }
            .main-nav .nav-list > li > a { padding:14px 20px; border-bottom:1px solid rgba(255,255,255,.04); min-height:48px; }
            .main-nav .nav-list > li > a::after { display:none; }
            .main-nav .nav-list > li.active > a { background:rgba(255,255,255,.06); border-left:3px solid var(--amber); }
            .main-nav .nav-list > li > a .arrow { margin-left:auto; }
            .main-nav .dropdown-menu-custom { position:static; background:rgba(0,0,0,.12); box-shadow:none; border-radius:0; opacity:1; transform:none; display:none; padding:0; margin:0; }
            .main-nav .dropdown-menu-custom::before { display:none; }
            .main-nav .dropdown-trigger:hover .dropdown-menu-custom { display:none; }
            .main-nav .dropdown-menu-custom.show { display:block; }
            .main-nav .dropdown-menu-custom a { color:rgba(255,255,255,.7); padding:10px 32px; border-left-color:transparent; font-size:.75rem; }
            .main-nav .dropdown-menu-custom a:hover { background:rgba(255,255,255,.08); color:#fff; border-left-color:var(--amber); }
            .main-nav .hamburger { display:block; }
            .main-nav .nav-right { margin-left:auto; }
        }

        /* ─── ANNOUNCEMENT BAR ─── */
        .announcement-bar { background:#fff; border-bottom:1px solid var(--gray-200); padding:10px 0; overflow:hidden; }
        .announcement-bar .ann-inner { display:flex; align-items:center; gap:12px; }
        .announcement-bar .ann-label { background:var(--red); color:#fff; font-size:.63rem; font-weight:700; text-transform:uppercase; padding:3px 12px; border-radius:4px; flex-shrink:0; letter-spacing:.5px; z-index:1; }
        .announcement-bar .ann-scroll { flex:1; overflow:hidden; position:relative; height:1.3em; }
        .announcement-bar .ann-track { display:inline-flex; white-space:nowrap; will-change:transform; }
        .announcement-bar .ann-track > span { font-size:.76rem; color:var(--gray-600); padding-right:80px; }
        .announcement-bar .ann-text a { color:var(--green-500); font-weight:500; }

        /* ─── HERO ─── */
        .hero-section { background:linear-gradient(135deg,var(--green-800),var(--green-500),var(--green-700)); position:relative; overflow:hidden; min-height:40vh; display:flex; align-items:center; }
        .hero-section::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); opacity:.15; }
        .hero-section .hero-shapes { position:absolute; inset:0; pointer-events:none; overflow:hidden; z-index:1; }
        .hero-section .hero-shapes span { position:absolute; background:rgba(255,255,255,.04); border-radius:50%; animation:floatShape 20s infinite; }
        .hero-section .hero-shapes span:nth-child(1) { width:180px; height:180px; top:10%; left:5%; animation-delay:0s; }
        .hero-section .hero-shapes span:nth-child(2) { width:120px; height:120px; bottom:15%; right:10%; animation-delay:-5s; }
        .hero-section .hero-shapes span:nth-child(3) { width:80px; height:80px; top:40%; right:30%; animation-delay:-10s; }
        @keyframes floatShape { 0%,100% { transform:translateY(0) scale(1); } 50% { transform:translateY(-30px) scale(1.05); } }
        .hero-section .hero-inner { position:relative; z-index:2; display:flex; align-items:center; gap:40px; padding:32px 0; flex-wrap:wrap; }
        .hero-section .hero-text { flex:1; min-width:280px; color:#fff; }
        .hero-section .hero-text .hero-icon { font-size:2rem; margin-bottom:10px; opacity:.7; display:inline-block; animation:floatIcon 3s ease-in-out infinite; }
        @keyframes floatIcon { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-6px); } }
        .hero-section .hero-text h2 { font-size:2.2rem; font-weight:900; margin-bottom:10px; letter-spacing:-.5px; line-height:1.2; }
        .hero-section .hero-text p { font-size:.95rem; opacity:.8; max-width:560px; line-height:1.7; margin-bottom:20px; text-align:justify; }
        .hero-section .hero-text .hero-btns { display:flex; flex-wrap:wrap; gap:12px; }
        .hero-section .hero-text .hero-btn { display:inline-flex; align-items:center; gap:8px; background:var(--amber); color:var(--green-800); font-weight:700; padding:12px 28px; border-radius:50px; text-decoration:none; font-size:.85rem; transition:all .25s; }
        .hero-section .hero-text .hero-btn:hover { background:#fff; transform:translateY(-2px); box-shadow:0 8px 25px rgba(0,0,0,.15); }
        .hero-section .hero-text .hero-btn-outline { display:inline-flex; align-items:center; gap:8px; border:2px solid rgba(255,255,255,.3); color:#fff; font-weight:600; padding:10px 24px; border-radius:50px; text-decoration:none; font-size:.82rem; transition:all .2s; }
        .hero-section .hero-text .hero-btn-outline:hover { border-color:#fff; background:rgba(255,255,255,.1); }
        .hero-section .hero-image { flex-shrink:0; width:480px; height:280px; border-radius:16px; overflow:hidden; box-shadow:0 12px 40px rgba(0,0,0,.25); position:relative; }
        .hero-section .hero-image .hero-slider { display:flex; width:100%; height:100%; transition:transform .6s ease; }
        .hero-section .hero-image .hero-slider .slide { min-width:100%; height:100%; flex-shrink:0; }
        .hero-section .hero-image .hero-slider .slide img { width:100%; height:100%; object-fit:cover; }
        .hero-section .hero-image .hero-dots { position:absolute; bottom:8px; left:50%; transform:translateX(-50%); display:flex; gap:6px; z-index:2; }
        .hero-section .hero-image .hero-dots span { width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,.4); cursor:pointer; transition:all .3s; }
        .hero-section .hero-image .hero-dots span.active { background:var(--amber); width:20px; border-radius:4px; }
        .hero-section .hero-wave { position:absolute; bottom:-2px; left:0; width:100%; height:60px; z-index:2; }
        @media (max-width:768px) {
            .hero-section { min-height:auto; }
            .hero-section .hero-inner { padding:32px 0; gap:20px; flex-direction:column-reverse; }
            .hero-section .hero-text h2 { font-size:1.5rem; }
            .hero-section .hero-text p { font-size:.85rem; }
            .hero-section .hero-text .hero-btns { flex-direction:column; }
            .hero-section .hero-text .hero-btn, .hero-section .hero-text .hero-btn-outline { width:100%; justify-content:center; }
            .hero-section .hero-image { width:100%; height:200px; }
            .hero-section .hero-wave { height:30px; }
        }

        /* ─── SECTION TITLES ─── */
        .section-title { font-size:1.15rem; font-weight:800; color:var(--green-800); margin-bottom:16px; display:flex; align-items:center; gap:10px; }
        .section-title .title-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:.75rem; flex-shrink:0; }
        .section-title .title-line { flex:1; height:2px; background:linear-gradient(90deg,var(--green-200),transparent); margin-left:8px; }

        /* ─── FADE-IN SECTIONS ─── */
        .fade-section { opacity:0; transform:translateY(24px); transition:opacity .6s ease, transform .6s ease; }
        .fade-section.visible { opacity:1; transform:translateY(0); }

        /* ─── LEADERSHIP ─── */
        .leadership-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); padding:24px; margin-bottom:28px; display:flex; align-items:center; gap:24px; flex-wrap:wrap; transition:box-shadow .3s; }
        .leadership-card:hover { box-shadow:0 6px 24px rgba(1,136,64,.1); }
        .leadership-card .leader-photo { width:150px; height:180px; border-radius:12px; object-fit:cover; border:3px solid var(--green-500); flex-shrink:0; }
        .leadership-card .leader-photo-fallback { display:none; width:150px; height:180px; border-radius:12px; background:var(--green-100); align-items:center; justify-content:center; border:3px solid var(--green-500); flex-shrink:0; }
        .leadership-card .leader-info { flex:1; min-width:220px; }
        .leadership-card .leader-info h5 { font-weight:800; color:var(--green-800); margin-bottom:2px; font-size:1.05rem; }
        .leadership-card .leader-info .leader-post { color:var(--green-500); font-weight:600; font-size:.82rem; margin-bottom:6px; }
        .leadership-card .leader-info .leader-desc { color:var(--gray-600); font-size:.8rem; line-height:1.65; margin-bottom:8px; text-align:justify; }
        .leadership-card .leader-info .leader-contacts { display:flex; gap:16px; flex-wrap:wrap; font-size:.74rem; color:var(--gray-500); }
        .leadership-card .leader-info .leader-contacts span i { width:16px; color:var(--green-500); margin-right:4px; }
        @media (max-width:480px) {
            .leadership-card { flex-direction:column; text-align:center; padding:18px; }
            .leadership-card .leader-info .leader-contacts { justify-content:center; flex-direction:column; gap:6px; }
        }

        /* ─── SPLIT LAYOUT ─── */
        .split-layout { display:grid; grid-template-columns:1fr; gap:24px; margin-bottom:28px; }
        @media (min-width:768px) { .split-layout { grid-template-columns:1fr 1fr; } }

        /* ─── UPDATES CARD ─── */
        .updates-card { background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; }
        .updates-card .c-head { padding:12px 18px; background:var(--gray-50); border-bottom:1px solid var(--gray-200); font-size:.82rem; font-weight:700; color:var(--green-800); display:flex; align-items:center; gap:8px; }
        .updates-card .c-body { padding:12px 18px; }
        .updates-card .update-scroll-wrap { max-height:280px; overflow:hidden; position:relative; }
        .updates-card .update-item { display:flex; align-items:flex-start; gap:10px; padding:8px 0; border-bottom:1px dashed var(--gray-100); font-size:.78rem; color:var(--gray-600); }
        .updates-card .update-item:last-child { border-bottom:none; }
        .updates-card .update-item .upd-badge { flex-shrink:0; width:8px; height:8px; border-radius:50%; background:var(--green-400); margin-top:6px; }
        .updates-card .update-item .upd-text { flex:1; min-width:0; }
        .updates-card .update-item .upd-text.clamped { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .updates-card .update-item .upd-date { color:var(--gray-400); font-size:.66rem; white-space:nowrap; flex-shrink:0; }
        .updates-card .update-item .read-more-link { color:var(--green-500); font-weight:600; text-decoration:none; margin-left:4px; white-space:nowrap; cursor:pointer; font-size:.7rem; }
        .updates-card .update-item .read-more-link:hover { text-decoration:underline; }

        /* ─── SERVICES ─── */
        .service-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:12px; }
        .service-card { background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.04); padding:20px 14px; text-align:center; transition:all .25s; cursor:pointer; text-decoration:none; display:block; color:inherit; border:1px solid transparent; }
        .service-card:hover { transform:translateY(-4px); box-shadow:0 8px 28px rgba(1,136,64,.12); border-color:var(--green-200); }
        .service-card .sc-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px; font-size:1.2rem; color:#fff; transition:transform .25s; }
        .service-card:hover .sc-icon { transform:scale(1.1); }
        .service-card h6 { font-size:.8rem; font-weight:700; margin-bottom:4px; }
        .service-card p { font-size:.7rem; color:var(--gray-500); margin:0; line-height:1.4; }
        @media (max-width:480px) {
            .service-grid { grid-template-columns:repeat(2,1fr); gap:8px; }
            .service-card { padding:14px 8px; }
            .service-card .sc-icon { width:40px; height:40px; font-size:1rem; }
        }

        /* ─── THREE COLUMN ─── */
        .three-col { display:grid; grid-template-columns:1fr; gap:18px; margin-bottom:28px; }
        .three-col .col-card { background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; transition:box-shadow .3s; }
        .three-col .col-card:hover { box-shadow:0 6px 24px rgba(1,136,64,.08); }
        .three-col .col-card .c-head { padding:14px 18px; border-left:4px solid var(--green-500); font-size:.82rem; font-weight:700; color:var(--green-800); display:flex; align-items:center; gap:8px; }
        .three-col .col-card .c-body { padding:14px 18px; font-size:.78rem; line-height:1.65; color:var(--gray-600); }
        @media (min-width:768px) { .three-col { grid-template-columns:1fr 1fr; } }
        @media (min-width:992px) { .three-col { grid-template-columns:1fr 1fr 1fr; } }

        /* ─── FACTS ─── */
        .facts-section { background:linear-gradient(135deg,var(--green-800),var(--green-600)); border-radius:16px; padding:28px 24px; margin-bottom:28px; color:#fff; }
        .facts-section .facts-head { font-size:.95rem; font-weight:800; margin-bottom:16px; display:flex; align-items:center; gap:8px; color:var(--amber); }
        .facts-section .facts-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:14px; }
        .facts-section .fact-item { text-align:center; padding:14px 10px; background:rgba(255,255,255,.07); border-radius:12px; backdrop-filter:blur(4px); transition:transform .2s; }
        .facts-section .fact-item:hover { transform:translateY(-2px); }
        .facts-section .fact-item .fact-num { font-size:1.4rem; font-weight:800; color:var(--amber); }
        .facts-section .fact-item .fact-lbl { font-size:.7rem; opacity:.7; margin-top:3px; }
        @media (max-width:480px) {
            .facts-section { padding:20px 16px; }
            .facts-section .facts-grid { grid-template-columns:repeat(2,1fr); gap:10px; }
        }

        /* ─── REQUEST CTA ─── */
        .request-cta { text-align:center; padding:36px 24px; background:linear-gradient(135deg,var(--green-100),#fff); border:2px solid var(--green-300); border-radius:16px; margin-bottom:28px; transition:box-shadow .3s; }
        .request-cta:hover { box-shadow:0 8px 30px rgba(1,136,64,.08); }
        .request-cta .cta-icon { font-size:2.6rem; color:var(--green-500); margin-bottom:10px; }
        .request-cta h3 { font-size:1.1rem; font-weight:800; color:var(--green-800); margin-bottom:4px; }
        .request-cta p { color:var(--gray-600); font-size:.82rem; max-width:420px; margin:0 auto 16px; }
        .request-cta .cta-btn { display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,var(--green-500),var(--green-600)); color:#fff; padding:12px 32px; border-radius:50px; font-size:.88rem; font-weight:700; text-decoration:none; transition:all .25s; }
        .request-cta .cta-btn:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(1,136,64,.3); }

        /* ─── TRACK CTA ─── */
        .track-cta { text-align:center; padding:40px 24px; background:linear-gradient(135deg,#f8f9fb,#fff); border:2px dashed var(--green-400); border-radius:16px; margin-bottom:28px; }
        .track-cta .cta-icon { font-size:3rem; color:var(--green-500); margin-bottom:12px; }
        .track-cta h3 { font-size:1.15rem; font-weight:800; color:var(--green-800); }
        .track-cta p { color:var(--gray-500); font-size:.82rem; max-width:480px; margin:4px auto 18px; }
        .track-cta .track-btn { display:inline-flex; align-items:center; gap:8px; background:var(--green-500); color:#fff; padding:12px 36px; border-radius:50px; font-size:.88rem; font-weight:700; text-decoration:none; transition:all .25s; border:none; }
        .track-cta .track-btn:hover { background:var(--green-600); transform:translateY(-2px); box-shadow:0 6px 20px rgba(1,136,64,.25); }

        /* ─── INFO RIBBON ─── */
        .info-ribbon { background:var(--green-800); padding:22px 0; }
        .info-ribbon .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; }
        .info-ribbon .ir-item { text-align:center; color:rgba(255,255,255,.8); padding:8px; }
        .info-ribbon .ir-item i { font-size:1.5rem; color:var(--amber); margin-bottom:6px; display:block; }
        .info-ribbon .ir-item .ir-label { font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.3px; }

        /* ─── FOOTER ─── */
        .site-footer { background:var(--green-900); padding:32px 0 0; color:rgba(255,255,255,.55); font-size:.75rem; }
        .site-footer .fw { display:grid; grid-template-columns:1fr; gap:24px; margin-bottom:24px; }
        .site-footer .fw .fw-head { color:rgba(255,255,255,.8); font-weight:700; font-size:.82rem; margin-bottom:10px; }
        .site-footer .fw ul { list-style:none; padding:0; margin:0; }
        .site-footer .fw ul li { padding:4px 0; }
        .site-footer .fw ul li i { width:18px; margin-right:4px; }
        .site-footer .fw a { color:rgba(255,255,255,.55); text-decoration:none; transition:color .15s; }
        .site-footer .fw a:hover { color:var(--amber); }
        .site-footer .fw .imp-links { display:flex; flex-wrap:wrap; gap:6px 14px; }
        .site-footer .fw .imp-links a { color:rgba(255,255,255,.4); font-size:.7rem; }
        .site-footer .fw .imp-links a:hover { color:var(--amber); }
        .site-footer .footer-bottom { border-top:1px solid rgba(255,255,255,.06); padding:16px 0; text-align:center; font-size:.67rem; }
        .site-footer .footer-bottom .fb-logos { display:flex; justify-content:center; align-items:center; gap:18px; margin-bottom:8px; flex-wrap:wrap; }
        .site-footer .footer-bottom .fb-logos span { display:flex; align-items:center; gap:6px; color:rgba(255,255,255,.3); font-size:.6rem; }
        .site-footer .footer-bottom .fb-disclaimer { font-size:.6rem; opacity:.35; max-width:700px; margin:8px auto 0; line-height:1.5; }
        @media (min-width:768px) { .site-footer .fw { grid-template-columns:repeat(3,1fr); } }

        /* ─── BACK TO TOP ─── */
        .back-to-top { position:fixed; bottom:24px; right:24px; z-index:999; width:44px; height:44px; border-radius:50%; background:var(--green-500); color:#fff; border:none; font-size:1.1rem; cursor:pointer; box-shadow:0 4px 14px rgba(1,136,64,.3); opacity:0; visibility:hidden; transform:translateY(12px); transition:all .3s; }
        .back-to-top.show { opacity:1; visibility:visible; transform:translateY(0); }
        .back-to-top:hover { background:var(--green-600); transform:translateY(-3px); box-shadow:0 6px 20px rgba(1,136,64,.4); }

        /* ─── RESPONSIVE ADJUSTMENTS ─── */
        @media (max-width:480px) {
            .util-bar .util-inner { flex-direction:column; align-items:flex-start; gap:4px; }
            .util-bar .util-right { width:100%; justify-content:space-between; }
            #main-content { padding:20px 0 !important; }
        }
        @media (max-width:768px) {
            .gov-header .brand-group { justify-content:center; }
            .gov-header .portal-title { text-align:center; }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    {{-- UTILITY BAR --}}
    <div class="util-bar">
        <div class="container">
            <div class="util-inner">
                <div class="util-left">
                    <span><i class="far fa-clock"></i> <span id="liveClock">{{ now()->format('l, d F Y') }}</span></span>
                    <a href="{{ route('accessibility') }}" title="Accessibility options"><i class="fas fa-universal-access mr-1"></i>Screen Reader</a>
                    <a href="#main-content" onclick="document.getElementById('main-content').focus();"><i class="fas fa-arrow-down mr-1"></i>Skip to Main</a>
                </div>
                <div class="util-right">
                    <div class="txt-size">
                        <span style="color:rgba(255,255,255,.4);font-size:.65rem;">A</span>
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

    {{-- GOVT HEADER --}}
    <header class="gov-header">
        <div class="container">
            <div class="header-inner">
                @php
                    $emblem = setting('front.header_emblem_path', '');
                    $emblemSrc = $emblem ? asset('storage/' . $emblem) : 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Emblem_of_India.svg/120px-Emblem_of_India.svg.png';
                @endphp
                <div class="brand-group">
                    <div class="emblem">
                        <img src="{{ $emblemSrc }}" alt="Emblem">
                    </div>
                    <div class="govt-meta">
                        <div class="ministry">{{ setting('front.header_ministry', 'Government of Jammu & Kashmir (UT)') }}</div>
                        <div class="dept-en">{{ setting('front.header_dept_en', 'Horticulture Department') }}</div>
                        <div class="dept-urdu">{{ setting('front.header_dept_ur', 'محکمہ باغبانی') }}</div>
                        <div class="dept-hindi">{{ setting('front.header_dept_hi', 'बागवानी विभाग, जम्मू एवं कश्मीर') }}</div>
                    </div>
                </div>
                @php
                    $flag = setting('front.header_flag_path', '');
                    $flagSrc = $flag ? asset('storage/' . $flag) : 'https://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/120px-Flag_of_India.svg.png';
                @endphp
                <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                    <img src="{{ $flagSrc }}" alt="India Flag" class="flag-img">
                    <div class="portal-title">
                        @php $logo = setting('logo_path', ''); @endphp
                        <div style="display:flex;align-items:center;gap:10px;justify-content:flex-end;">
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="logo-img">
                            @endif
                            <div>
                                <h1>{{ setting('app_name', 'Soil Lab') }}</h1>
                                <div class="tagline">{{ setting('app_description', 'Soil Testing & Health Management System') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @php
        $navMenuRaw = setting('front.nav_menu', '');
        $navMenu = $navMenuRaw ? json_decode($navMenuRaw, true) : [
            ['label' => 'Home', 'url' => route('home'), 'active' => true],
            ['label' => 'Services', 'url' => '#services'],
            ['label' => 'Track Report', 'url' => route('public.track')],
            ['label' => 'Gallery', 'children' => [
                ['label' => 'Photo Gallery', 'url' => '#'],
                ['label' => 'Press Notes', 'url' => '#'],
                ['label' => 'Events', 'url' => '#'],
            ]],
            ['label' => 'Orders', 'children' => [
                ['label' => 'Government Orders', 'url' => '#'],
                ['label' => 'Notifications', 'url' => '#'],
                ['label' => 'Circulars', 'url' => '#'],
            ]],
            ['label' => 'Acts & Rules', 'url' => '#'],
            ['label' => 'RTI', 'url' => '#'],
            ['label' => 'Contact', 'url' => '#'],
        ];
    @endphp

    {{-- NAV --}}
    <div class="nav-overlay" id="navOverlay"></div>
    <nav class="main-nav" aria-label="Main navigation" id="mainNav">
        <div class="container">
            <div class="nav-inner">
                <button class="hamburger" id="hamburger" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
                <ul class="nav-list" id="navList">
                    <li class="drawer-header d-md-none">
                        <span class="drawer-brand"><i class="fas fa-flask mr-2" style="color:var(--amber);"></i>{{ setting('app_name', 'Soil Lab') }}</span>
                        <button class="drawer-close" id="drawerClose" aria-label="Close navigation"><i class="fas fa-times"></i></button>
                    </li>
                    @foreach($navMenu as $item)
                        @if(!empty($item['children']))
                        <li class="dropdown-trigger">
                            <a>{{ $item['label'] ?? '' }} <i class="fas fa-chevron-down arrow"></i></a>
                            <div class="dropdown-menu-custom">
                                @foreach($item['children'] as $child)
                                <a href="{{ $child['url'] ?? '#' }}">{{ $child['label'] ?? '' }}</a>
                                @endforeach
                            </div>
                        </li>
                        @else
                        <li @if(!empty($item['active'])) class="active" @endif>
                            <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] ?? '' }}</a>
                        </li>
                        @endif
                    @endforeach
                </ul>
                <div class="nav-right">
                    <a href="{{ route('login') }}" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ANNOUNCEMENT BAR --}}
    @php $notices = json_decode(setting('front.notices', '[]'), true) ?: []; @endphp
    <div class="announcement-bar">
        <div class="container">
            <div class="ann-inner">
                <span class="ann-label"><i class="fas fa-bullhorn mr-1"></i> {{ setting('front.notice_label', 'Notice') }}</span>
                <div class="ann-scroll">
                    <div class="ann-track" id="annTrack"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- HERO --}}
    <section class="hero-section" id="hero">
        <div class="hero-shapes">
            <span></span><span></span><span></span>
        </div>
        <div class="container">
            <div class="hero-inner">
                <div class="hero-text">
                    <h2>{{ setting('front.hero_title', 'Soil Health & Testing Services') }}</h2>
                    <p>{{ setting('front.hero_subtitle', 'Scientific soil analysis for balanced nutrient management. Get your personalised Soil Health Card with crop-wise recommendations — free for all farmers.') }}</p>
                    <div class="hero-btns">
                        <a href="{{ setting('front.hero_btn_url', route('public.track')) }}" class="hero-btn"><i class="fas fa-file-pdf"></i> {{ setting('front.hero_btn_text', 'Download Your Report') }}</a>
                        <a href="{{ route('public.sample-request') }}" class="hero-btn-outline"><i class="fas fa-clipboard-list"></i> Request Collection</a>
                    </div>
                </div>
                @php
                    $heroPhoto = setting('front.hero_photo_path', '');
                    $heroPhoto2 = setting('front.hero_photo_path2', '');
                    $heroPhoto3 = setting('front.hero_photo_path3', '');
                    $heroImages = array_filter([$heroPhoto, $heroPhoto2, $heroPhoto3]);
                    $defaultImg = 'https://images.unsplash.com/photo-1589923188900-85dae523342b?w=600&h=400&fit=crop';
                    if (empty($heroImages)) { $heroImages = [$defaultImg]; }
                @endphp
                <div class="hero-image" id="heroCarousel">
                    <div class="hero-slider" id="heroSlider">
                        @foreach($heroImages as $img)
                        <div class="slide">
                            @if(str_starts_with($img, 'http'))
                            <img src="{{ $img }}" alt="Hero" onerror="this.style.display='none'" loading="lazy">
                            @else
                            <img src="{{ asset('storage/' . $img) }}" alt="Hero" onerror="this.style.display='none'" loading="lazy">
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @if(count($heroImages) > 1)
                    <div class="hero-dots" id="heroDots">
                        @foreach($heroImages as $idx => $img)
                        <span class="{{ $idx === 0 ? 'active' : '' }}"></span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @php unset($heroPhoto, $heroPhoto2, $heroPhoto3, $heroImages, $defaultImg); @endphp
            </div>
        </div>
        <svg class="hero-wave" viewBox="0 0 1440 60" preserveAspectRatio="none"><path d="M0,40 C360,60 720,0 1440,40 L1440,60 L0,60 Z" fill="#f0f2f5"/></svg>
    </section>

    {{-- MAIN CONTENT --}}
    <main id="main-content" style="padding:28px 0;">
        <div class="container">

            {{-- REQUEST SAMPLE CTA --}}
            <div class="request-cta fade-section">
                <div class="cta-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3>Need a Soil Test?</h3>
                <p>Farmer? Request a free sample collection from your zone. A field official will visit your farm.</p>
                <a href="{{ route('public.sample-request') }}" class="cta-btn"><i class="fas fa-clipboard-list mr-1"></i> Request Sample Collection</a>
            </div>

            {{-- LEADERSHIP --}}
            <div class="leadership-card fade-section">
                @php $choPhoto = setting('front.cho_photo_path', ''); @endphp
                <img src="{{ $choPhoto ? asset('storage/' . $choPhoto) : asset('images/cho_pulwama.png') }}" alt="{{ setting('front.cho_title', 'Chief Horticulture Officer') }}, Pulwama"
                     class="leader-photo"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" loading="lazy">
                <div class="leader-photo-fallback"><i class="fas fa-user-tie" style="font-size:2.5rem;color:#2e7d32;"></i></div>
                <div class="leader-info">
                    @if($name = setting('front.cho_name', ''))
                        <h5>{{ $name }}</h5>
                    @endif
                    <div class="leader-post">{{ setting('front.cho_title', 'Chief Horticulture Officer') }}, {{ setting('front.cho_post', 'Pulwama District') }}</div>
                    <p class="leader-desc">{{ setting('front.cho_description', 'The Horticulture Department, Pulwama is committed to promoting sustainable farming through scientific soil analysis, quality plant material, and modern horticultural practices. Farmers are encouraged to avail free soil testing services for better crop yield.') }}</p>
                    <div class="leader-contacts">
                        <span><i class="fas fa-phone-alt"></i> {{ setting('front.cho_phone', '+91-XXXX-XXXXXX') }}</span>
                        <span><i class="fas fa-envelope"></i> {{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}</span>
                        <span><i class="fas fa-map-marker-alt"></i> {{ setting('front.cho_address', 'Mini Secretariat, Pulwama, J&K 192301') }}</span>
                    </div>
                </div>
            </div>

            {{-- SPLIT: UPDATES + SERVICES --}}
            <div class="split-layout">
                <div class="fade-section">
                    @php $updates = json_decode(setting('front.updates', '[]'), true) ?: []; @endphp
                    @if(count($updates))
                    <div class="updates-card">
                        <div class="c-head"><i class="fas fa-clock" style="color:var(--green-500);"></i> Latest Updates &amp; Orders</div>
                        <div class="c-body">
                            <div class="update-scroll-wrap" id="updatesWrap">
                                <div id="updatesInner">
                                    @foreach($updates as $upd)
                                    @php
                                        $text = $upd['text'] ?? '';
                                        $date = $upd['date'] ?? '';
                                    @endphp
                                    @if($text)
                                    <div class="update-item">
                                        <span class="upd-badge"></span>
                                        <div class="upd-text clamped" data-full="{{ str_replace(["\r","\n"], ' ', $text) }}">{{ $text }}</div>
                                        <a class="read-more-link" onclick="toggleUpdate(this)" style="display:none">Read More</a>
                                        @if($date)<span class="upd-date">{{ $date }}</span>@endif
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="fade-section">
                    <h4 class="section-title" id="services">
                        <span class="title-icon" style="background:linear-gradient(135deg,var(--green-500),var(--green-400))"><i class="fas fa-th-large"></i></span>
                        Our Services
                        <span class="title-line"></span>
                    </h4>
                    <div class="service-grid">
                        <a href="{{ route('public.track') }}" class="service-card">
                            <div class="sc-icon" style="background:linear-gradient(135deg,var(--green-500),var(--green-400))"><i class="fas fa-hand-holding-heart"></i></div>
                            <h6>{{ setting('front.feature1_title', 'Sample Collection') }}</h6>
                            <p>{{ setting('front.feature1_text', 'Field officials collect soil samples with GPS-tagged locations.') }}</p>
                        </a>
                        <a href="#" class="service-card">
                            <div class="sc-icon" style="background:linear-gradient(135deg,var(--green-700),var(--green-500))"><i class="fas fa-flask"></i></div>
                            <h6>{{ setting('front.feature2_title', 'Lab Testing') }}</h6>
                            <p>{{ setting('front.feature2_text', '12-parameter analysis including NPK, pH, and micronutrients.') }}</p>
                        </a>
                        <a href="{{ route('public.track') }}" class="service-card">
                            <div class="sc-icon" style="background:linear-gradient(135deg,var(--amber),var(--amber-dark))"><i class="fas fa-file-medical-alt"></i></div>
                            <h6>{{ setting('front.feature3_title', 'Soil Health Card') }}</h6>
                            <p>{{ setting('front.feature3_text', 'Detailed report with ratings and crop-specific recommendations.') }}</p>
                        </a>
                        <a href="#" class="service-card">
                            <div class="sc-icon" style="background:linear-gradient(135deg,var(--red),#e53935)"><i class="fas fa-seedling"></i></div>
                            <h6>{{ setting('front.feature4_title', 'Fertilizer Advice') }}</h6>
                            <p>{{ setting('front.feature4_text', 'Science-based fertilizer recommendations for your crops.') }}</p>
                        </a>
                        <a href="{{ setting('front.feature5_url', '#') }}" class="service-card">
                            <div class="sc-icon" style="background:linear-gradient(135deg,#1565c0,#1e88e5)"><i class="fas {{ setting('front.feature5_icon', 'fa-map-marked-alt') }}"></i></div>
                            <h6>{{ setting('front.feature5_title', 'Locate Lab') }}</h6>
                            <p>{{ setting('front.feature5_text', 'Find your nearest soil testing laboratory.') }}</p>
                        </a>
                        <a href="{{ setting('front.feature6_url', '#') }}" class="service-card">
                            <div class="sc-icon" style="background:linear-gradient(135deg,#6a1b9a,#ab47bc)"><i class="fas {{ setting('front.feature6_icon', 'fa-chart-bar') }}"></i></div>
                            <h6>{{ setting('front.feature6_title', 'Nutrient Guide') }}</h6>
                            <p>{{ setting('front.feature6_text', 'Balanced nutrient management recommendations.') }}</p>
                        </a>
                    </div>
                </div>
            </div>

            {{-- THREE COLUMN --}}
            <div class="three-col fade-section">
                <div class="col-card">
                    <div class="c-head"><i class="fas fa-eye" style="color:var(--green-500);"></i> Vision</div>
                    <div class="c-body">
                        <p>{{ setting('front.vision_text', 'To strengthen an enabling environment to enhance soil health and agricultural productivity in Pulwama district through scientific testing, balanced nutrient management, and sustainable farming practices.') }}</p>
                    </div>
                </div>
                <div class="col-card">
                    <div class="c-head"><i class="fas fa-rocket" style="color:var(--green-500);"></i> Mission</div>
                    <div class="c-body">
                        <p>{{ setting('front.mission_text', 'Provide free, accessible soil testing to every farmer in Pulwama. Promote balanced fertiliser use, organic farming, and modern horticultural techniques to double farmer income.') }}</p>
                    </div>
                </div>
                <div class="col-card">
                    <div class="c-head"><i class="fas fa-bullseye" style="color:var(--green-500);"></i> Objectives</div>
                    <div class="c-body">
                        <p>{{ setting('front.objectives_text', 'Cover 100% of villages in Pulwama under soil testing. Reduce turnaround time. Issue Soil Health Cards with GPS-tagged recommendations. Train farmers on integrated nutrient management.') }}</p>
                    </div>
                </div>
            </div>

            {{-- FACTS --}}
            <div class="facts-section fade-section">
                <div class="facts-head"><i class="fas fa-info-circle"></i> Horticulture Facts — Pulwama District</div>
                <div class="facts-grid">
                    <div class="fact-item"><span class="fact-num" data-count="{{ setting('front.fact1_value', '14×') }}">0</span><div class="fact-lbl">{{ setting('front.fact1_label', 'Growth Since 1972') }}</div></div>
                    <div class="fact-item"><span class="fact-num" data-count="{{ setting('front.fact2_value', '24.94 LMT') }}">0</span><div class="fact-lbl">{{ setting('front.fact2_label', 'Annual Fruit Production') }}</div></div>
                    <div class="fact-item"><span class="fact-num" data-count="{{ setting('front.fact3_value', '23 Lakh+') }}">0</span><div class="fact-lbl">{{ setting('front.fact3_label', 'People Employed') }}</div></div>
                    <div class="fact-item"><span class="fact-num" data-count="{{ setting('front.fact4_value', '₹6,300 Cr') }}">0</span><div class="fact-lbl">{{ setting('front.fact4_label', 'Annual Turnover') }}</div></div>
                </div>
            </div>

            {{-- TRACK CTA --}}
            <div class="track-cta fade-section">
                <div class="cta-icon"><i class="fas {{ setting('front.cta_icon', 'fa-search') }}"></i></div>
                <h3>{{ setting('front.cta_title', 'Track Your Soil Report') }}</h3>
                <p>{{ setting('front.cta_subtitle', 'Enter your registered mobile number to check the status of your soil test and download your report.') }}</p>
                <a href="{{ setting('front.cta_btn_url', route('public.track')) }}" class="track-btn"><i class="fas fa-arrow-right mr-1"></i> {{ setting('front.cta_btn_text', 'Go to Report Tracking') }}</a>
            </div>

        </div>
    </main>

    {{-- INFO RIBBON --}}
    <div class="info-ribbon">
        <div class="container">
            <div class="info-grid">
                @for($ri = 1; $ri <= 4; $ri++)
                @php
                    $rIcon = setting('front.ribbon'.$ri.'_icon', '');
                    $rLabel = setting('front.ribbon'.$ri.'_label', '');
                @endphp
                @if($rLabel)
                <div class="ir-item"><i class="fas {{ $rIcon }}"></i><span class="ir-label">{{ $rLabel }}</span></div>
                @endif
                @endfor
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="site-footer">
        <div class="container">
            <div class="fw">
                <div>
                    <div class="fw-head"><i class="fas fa-university mr-1"></i> {{ setting('app_name', 'Soil Lab') }}</div>
                    <p style="line-height:1.7;">{{ setting('front.footer_about', 'Official portal of the Soil Testing Laboratory, Pulwama under the Department of Agriculture & Farmers Welfare / Horticulture Department, Government of Jammu & Kashmir (UT). Soil Health Card — A Government of India initiative.') }}</p>
                </div>
                <div>
                    <div class="fw-head">Important Links</div>
                    <div class="imp-links">
                        @php $footerLinks = json_decode(setting('front.footer_links', '[]'), true) ?: []; @endphp
                        @foreach($footerLinks as $link)
                        @if(!empty($link['label']))
                        <a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] }}</a>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="fw-head">Contact</div>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> {{ setting('front.cho_address', 'Mini Secretariat, Pulwama, J&K 192301') }}</li>
                        <li><i class="fas fa-envelope"></i> {{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}</li>
                        <li><i class="fas fa-phone"></i> {{ setting('front.cho_phone', '+91-XXXX-XXXXXX') }}</li>
                        <li><i class="fas fa-clock"></i> {{ setting('front.working_hours', 'Mon–Fri: 9 AM – 5 PM') }}</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="fb-logos">
                    <span><i class="fas fa-certificate" style="color:var(--amber);"></i> {{ setting('front.header_ministry', 'Government of Jammu & Kashmir (UT)') }}</span>
                    <span><i class="fas fa-leaf" style="color:var(--green-400);"></i> Soil Health Card Scheme</span>
                    <span><i class="fas fa-dharmachakra" style="color:var(--amber);"></i> Ministry of Agriculture</span>
                </div>
                {!! setting('front.footer_copyright', '&copy; ' . date('Y') . ' ' . setting('app_name', 'Soil Lab') . ' &mdash; Government of Jammu & Kashmir (UT)') !!}
                @if($v = setting('app_version', ''))
                    &nbsp;|&nbsp; Version {{ $v }}
                @endif
                <div class="fb-disclaimer">
                    {{ setting('front.footer_disclaimer', 'This site is for information purposes only. Content is owned and maintained by the Horticulture Department, Pulwama District, J&K (UT).') }}
                </div>
                <div style="margin-top:6px;">
                    <span style="opacity:.4;font-size:.6rem;">{!! setting('front.footer_credit_text', 'Designed and Developed by <a href="https://theumer.store" target="_blank" rel="noopener" style="color:rgba(255,255,255,.5);text-decoration:underline;">Umer Bashir</a>') !!}</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- BACK TO TOP --}}
    <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

    <script>
        // ─── NAV SCROLL SHADOW ───
        (function() {
            var nav = document.getElementById('mainNav');
            window.addEventListener('scroll', function() {
                nav.classList.toggle('scrolled', window.scrollY > 10);
            }, { passive: true });
        })();

        // ─── MOBILE NAV DRAWER ───
        (function() {
            var hamburger = document.getElementById('hamburger');
            var navList = document.getElementById('navList');
            var overlay = document.getElementById('navOverlay');
            var closeBtn = document.getElementById('drawerClose');
            function closeNav() { navList.classList.remove('open'); overlay.classList.remove('open'); hamburger.classList.remove('open'); }
            function openNav() { navList.classList.add('open'); overlay.classList.add('open'); hamburger.classList.add('open'); }
            hamburger.addEventListener('click', function() {
                if (navList.classList.contains('open')) { closeNav(); } else { openNav(); }
            });
            overlay.addEventListener('click', closeNav);
            if (closeBtn) { closeBtn.addEventListener('click', closeNav); }
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeNav(); });
            navList.querySelectorAll('.dropdown-trigger > a').forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    var menu = this.nextElementSibling;
                    if (window.innerWidth <= 768) {
                        menu.classList.toggle('show');
                    } else {
                        var isOpen = menu.classList.contains('show');
                        document.querySelectorAll('.dropdown-menu-custom').forEach(function(m) { m.classList.remove('show'); });
                        if (!isOpen) menu.classList.add('show');
                    }
                });
            });
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-trigger')) {
                    document.querySelectorAll('.dropdown-menu-custom').forEach(function(m) { m.classList.remove('show'); });
                }
            });
            document.querySelectorAll('#navList a[href^="#"]').forEach(function(a) {
                a.addEventListener('click', closeNav);
            });
        })();

        // ─── NOTICE MARQUEE ───
        var notices = @json($notices);
        if (notices.length) {
            (function() {
                var track = document.getElementById('annTrack');
                var html = '';
                for (var j = 0; j < notices.length; j++) {
                    html += '<span>' + notices[j] + '</span>';
                }
                track.innerHTML = html;
                var spans = track.children;
                if (spans.length < 2) return;
                var pos = 0, speed = 0.6;
                function step() {
                    pos -= speed;
                    var first = spans[0];
                    var firstW = first.offsetWidth + 80;
                    if (pos <= -firstW) {
                        pos += firstW;
                        track.appendChild(first);
                    }
                    track.style.transform = 'translateX(' + pos + 'px)';
                    requestAnimationFrame(step);
                }
                step();
            })();
        }

        // ─── UPDATES AUTO-SCROLL ───
        (function() {
            var wrap = document.getElementById('updatesWrap');
            var inner = document.getElementById('updatesInner');
            if (!wrap || !inner) return;
            var items = inner.querySelectorAll('.update-item');
            if (items.length <= 5) return;
            var pos = 0, speed = 0.3, paused = false;
            function pause() { paused = true; }
            function resume() { paused = false; }
            wrap.addEventListener('mouseenter', pause);
            wrap.addEventListener('mouseleave', resume);
            wrap.addEventListener('touchstart', pause, { passive: true });
            wrap.addEventListener('touchend', resume, { passive: true });
            function scrollUpdates() {
                if (!paused) {
                    pos -= speed;
                    var h = inner.offsetHeight - wrap.offsetHeight;
                    if (pos < -h) { pos = 0; }
                    inner.style.transform = 'translateY(' + pos + 'px)';
                }
                requestAnimationFrame(scrollUpdates);
            }
            scrollUpdates();
        })();

        // ─── READ MORE TOGGLE ───
        function toggleUpdate(link) {
            var item = link.closest('.update-item');
            var textEl = item.querySelector('.upd-text');
            if (textEl.classList.contains('clamped')) {
                textEl.classList.remove('clamped');
                textEl.textContent = textEl.getAttribute('data-full');
                link.textContent = 'Show Less';
            } else {
                textEl.classList.add('clamped');
                link.textContent = 'Read More';
            }
        }
        (function() {
            document.querySelectorAll('.upd-text').forEach(function(el) {
                if (el.scrollHeight > el.clientHeight) {
                    var link = el.nextElementSibling;
                    if (link && link.classList.contains('read-more-link')) {
                        link.style.display = 'inline';
                    }
                }
            });
        })();

        // ─── FONT SIZE ───
        (function() {
            var size = localStorage.getItem('fontSize');
            if (size) {
                document.documentElement.style.fontSize = size + '%';
                document.documentElement.classList.add('font-adjusted');
            }
        })();
        function changeFontSize(delta) {
            var html = document.documentElement;
            var current = parseInt(html.style.fontSize) || 100;
            var next = Math.min(Math.max(current + delta * 10, 70), 150);
            html.style.fontSize = next + '%';
            html.classList.add('font-adjusted');
            localStorage.setItem('fontSize', next);
        }
        function resetFontSize() {
            document.documentElement.style.fontSize = '';
            document.documentElement.classList.remove('font-adjusted');
            localStorage.removeItem('fontSize');
        }

        // ─── LIVE CLOCK ───
        (function() {
            var el = document.getElementById('liveClock');
            if (!el) return;
            var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            function tick() {
                var d = new Date();
                el.textContent = days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
            }
            tick();
            setInterval(tick, 60000);
        })();

        // ─── FADE-IN SECTIONS ───
        (function() {
            var sections = document.querySelectorAll('.fade-section');
            if (!('IntersectionObserver' in window)) {
                sections.forEach(function(s) { s.classList.add('visible'); });
                return;
            }
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });
            sections.forEach(function(s) { observer.observe(s); });
        })();

        // ─── BACK TO TOP ───
        (function() {
            var btn = document.getElementById('backToTop');
            window.addEventListener('scroll', function() {
                btn.classList.toggle('show', window.scrollY > 400);
            }, { passive: true });
            btn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();

        // ─── ANIMATED COUNTERS ───
        (function() {
            var facts = document.querySelectorAll('.fact-num');
            if (!facts.length || !('IntersectionObserver' in window)) return;
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        var target = el.getAttribute('data-count');
                        el.textContent = target;
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            facts.forEach(function(f) { observer.observe(f); });
        })();

        // ─── HERO CAROUSEL ───
        (function() {
            var slider = document.getElementById('heroSlider');
            var dotsContainer = document.getElementById('heroDots');
            if (!slider) return;
            var slides = slider.querySelectorAll('.slide');
            if (slides.length < 2) return;
            var dots = dotsContainer ? dotsContainer.querySelectorAll('span') : [];
            var current = 0, total = slides.length, interval;
            function goTo(idx) {
                if (idx === current) return;
                slider.style.transform = 'translateX(-' + (idx * 100) + '%)';
                if (dots.length) {
                    dots[current].classList.remove('active');
                    dots[idx].classList.add('active');
                }
                current = idx;
            }
            function next() { goTo((current + 1) % total); }
            function start() { interval = setInterval(next, 4000); }
            function stop() { clearInterval(interval); }
            var carousel = document.getElementById('heroCarousel');
            carousel.addEventListener('mouseenter', stop);
            carousel.addEventListener('mouseleave', start);
            carousel.addEventListener('touchstart', stop, { passive: true });
            carousel.addEventListener('touchend', start, { passive: true });
            if (dots.length) {
                dots.forEach(function(dot, idx) {
                    dot.addEventListener('click', function() { stop(); goTo(idx); start(); });
                });
            }
            start();
        })();
    </script>
</body>
</html>
