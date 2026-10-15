<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login &mdash; {{ setting('app_name', config('app.name')) }} @php $d = setting('app_description', ''); @endphp @if($d)&mdash; {{ $d }} @endif</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #064e3b 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            color: #1e293b;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 20%, rgba(16, 185, 129, 0.15), transparent 40%),
                        radial-gradient(circle at 80% 80%, rgba(5, 150, 105, 0.15), transparent 40%);
            pointer-events: none;
            z-index: 0;
        }
        .login-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
            animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 40px 36px 32px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .brand-section {
            text-align: center;
            margin-bottom: 24px;
        }
        .ministry-name {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 3px;
        }
        .dept-name {
            font-size: 13px;
            color: #334155;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 12px;
        }
        .brand-logo {
            margin-bottom: 12px;
        }
        .brand-logo img {
            max-height: 52px;
            width: auto;
            object-fit: contain;
        }
        .app-name {
            font-size: 20px;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 3px;
            letter-spacing: -0.02em;
        }
        .app-desc {
            font-size: 13px;
            color: #64748b;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 18px;
            position: relative;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }
        .input-group-custom {
            position: relative;
        }
        .input-group-custom .form-control {
            height: 48px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            padding: 10px 45px 10px 44px;
            font-size: 14px;
            background: #ffffff;
            color: #1e293b;
            transition: all 0.2s ease;
            box-shadow: none;
        }
        .input-group-custom .form-control:focus {
            border-color: #10b981;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        .input-group-custom .form-control.is-invalid {
            border-color: #ef4444;
            background: #ffffff;
        }
        .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            z-index: 5;
            transition: color 0.2s ease;
            pointer-events: none;
        }
        .input-group-custom .form-control:focus ~ .input-icon {
            color: #10b981;
        }
        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: #475569;
            user-select: none;
            width: fit-content;
        }
        .custom-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #10b981;
            cursor: pointer;
            margin: 0;
        }
        .btn-signin {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.2px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        .btn-signin:active {
            transform: translateY(0);
        }
        .btn-signin i {
            margin-right: 8px;
        }
        .back-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 18px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
            width: 100%;
        }
        .back-home:hover {
            color: #059669;
            text-decoration: none;
        }
        .back-home i {
            margin-right: 6px;
        }
        .error-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 18px;
            color: #b91c1c;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .demo-credentials {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            margin-top: 18px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
        .demo-credentials span {
            color: #0f172a;
            font-weight: 600;
        }
        @media (max-width: 576px) {
            .glass-card {
                padding: 30px 20px 24px;
            }
            .app-name {
                font-size: 18px;
            }
            body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="glass-card">

            <div class="brand-section">
                @php
                    $loginLogo = setting('logo_path', '');
                    $loginDesc = setting('app_description', 'Soil Testing & Health Management System');
                    $ministry = setting('front.header_ministry', 'Government of Jammu & Kashmir (UT)');
                    $dept = setting('front.header_dept_en', 'Horticulture Department');
                @endphp

                <div class="ministry-name">{{ $ministry }}</div>
                <div class="dept-name">{{ $dept }}</div>

                @if($loginLogo)
                    <div class="brand-logo">
                        <img src="{{ asset('storage/' . $loginLogo) }}" alt="Logo">
                    </div>
                @else
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                              style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 1.25rem;">
                            <i class="fas fa-flask"></i>
                        </span>
                    </div>
                @endif

                <div class="app-name">{{ setting('app_name', config('app.name')) }}</div>
                @if($loginDesc)
                    <div class="app-desc">{{ $loginDesc }}</div>
                @endif
            </div>

            <div class="divider"></div>

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{!! $error !!}</span>
                    </div>
                @endforeach
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group-custom">
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="admin@soillab.com"
                               value="{{ old('email') }}" required autofocus>
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group-custom">
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <span class="password-toggle" onclick="togglePassword()"
                              style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;z-index:5;font-size:15px;user-select:none;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center mb-4">
                    <label class="custom-checkbox mb-0">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember Me</span>
                    </label>
                </div>

                @if(setting('recaptcha_enabled', false) && app()->environment('production'))
                    <div class="recaptcha-wrapper mb-3 text-center">
                        <div class="g-recaptcha d-inline-block" data-sitekey="{{ setting('recaptcha_site_key', '') }}"></div>
                    </div>
                    @error('g-recaptcha-response')
                        <div class="error-alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                @endif

                <button type="submit" class="btn-signin">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Portal
                </button>
            </form>

            @if(app()->environment('local') || config('app.debug'))
                <div class="demo-credentials">
                    <i class="fas fa-key text-warning mr-1"></i> Admin demo: <span>admin@soillab.com</span> / <span>password</span>
                </div>
            @endif

            <a href="{{ route('home') }}" class="back-home">
                <i class="fas fa-arrow-left"></i> Back to Public Portal
            </a>

        </div>
    </div>

    @if(setting('recaptcha_enabled', false) && app()->environment('production'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            var input = document.getElementById('password');
            var icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
