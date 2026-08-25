<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Under Maintenance &mdash; {{ setting('app_name', config('app.name')) }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#0a5a2a,#018840,#0a5a2a);
            font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; padding:20px;
        }
        .card {
            background:rgba(255,255,255,.93); backdrop-filter:blur(20px); border-radius:20px;
            padding:50px 40px; max-width:460px; width:100%; text-align:center;
            box-shadow:0 25px 60px rgba(0,0,0,.3); animation:fadeSlideUp .6s ease-out;
        }
        @keyframes fadeSlideUp {
            0%{opacity:0;transform:translateY(30px)}
            100%{opacity:1;transform:translateY(0)}
        }
        .spinner {
            width:64px; height:64px; margin:0 auto 20px; position:relative;
        }
        .spinner .ring {
            position:absolute; inset:0; border-radius:50%;
            border:4px solid transparent; border-top-color:#018840;
            animation:spin 1s linear infinite;
        }
        .spinner .ring:nth-child(2) {
            inset:6px; border-top-color:#f59e0b; animation-duration:1.4s;
        }
        .spinner .icon {
            position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
            font-size:1.6rem; color:#0a5a2a;
        }
        @keyframes spin { to { transform:rotate(360deg); } }
        h1 { font-size:1.6rem; font-weight:700; color:#1a202c; margin-bottom:8px; }
        p { font-size:.9rem; color:#718096; line-height:1.6; margin-bottom:6px; }
        .badge {
            display:inline-block; margin-top:8px; padding:6px 18px;
            background:rgba(1,136,64,.1); color:#018840; border-radius:50px;
            font-size:.78rem; font-weight:600;
        }
        .btn {
            display:inline-block; margin-top:24px; padding:12px 32px;
            background:linear-gradient(135deg,#0a5a2a,#018840);
            color:#fff; border-radius:12px; text-decoration:none; font-weight:600; font-size:.9rem;
            transition:all .3s; box-shadow:0 6px 20px rgba(1,136,64,.35);
        }
        .btn:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(1,136,64,.45); }
        @media(max-width:576px) { .card { padding:30px 20px; } h1 { font-size:1.3rem; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="spinner">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="icon"><i class="fas fa-wrench"></i></div>
        </div>
        <h1>Under Maintenance</h1>
        <p>The system is currently undergoing scheduled maintenance.</p>
        <p style="font-size:.82rem;color:#a0aec0;">We should be back shortly. Please check back later.</p>
        <div class="badge"><i class="far fa-clock mr-1"></i> {{ setting('app_name', 'Soil Lab') }}</div>
        <br>
        <a href="mailto:{{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}" class="btn"><i class="fas fa-envelope mr-1"></i> Contact Support</a>
    </div>
</body>
</html>
