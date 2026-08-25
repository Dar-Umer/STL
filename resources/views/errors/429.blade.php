<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Too Many Requests &mdash; {{ setting('app_name', config('app.name')) }}</title>
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
            padding:50px 40px; max-width:440px; width:100%; text-align:center;
            box-shadow:0 25px 60px rgba(0,0,0,.3); animation:fadeSlideUp .6s ease-out;
        }
        @keyframes fadeSlideUp {
            0%{opacity:0;transform:translateY(30px)}
            100%{opacity:1;transform:translateY(0)}
        }
        .icon { font-size:3rem; color:#c62828; margin-bottom:16px; }
        h1 { font-size:1.6rem; font-weight:700; color:#1a202c; margin-bottom:8px; }
        p { font-size:.9rem; color:#718096; line-height:1.6; margin-bottom:24px; }
        .btn {
            display:inline-block; padding:12px 32px; background:linear-gradient(135deg,#0a5a2a,#018840);
            color:#fff; border-radius:12px; text-decoration:none; font-weight:600; font-size:.9rem;
            transition:all .3s; box-shadow:0 6px 20px rgba(1,136,64,.35);
        }
        .btn:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(1,136,64,.45); }
        @media(max-width:576px) { .card { padding:30px 20px; } h1 { font-size:1.3rem; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon"><i class="fas fa-shield-alt"></i></div>
        <h1>Too Many Requests</h1>
        <p>You have made too many requests in a short period. Please wait a moment and try again.</p>
        <a href="{{ url()->previous() ?? route('home') }}" class="btn"><i class="fas fa-arrow-left mr-1"></i> Go Back</a>
    </div>
</body>
</html>
