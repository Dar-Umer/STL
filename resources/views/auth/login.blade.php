<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login &mdash; {{ setting('app_name', config('app.name')) }} @php $d = setting('app_description', ''); @endphp @if($d)&mdash; {{ $d }} @endif</title>
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
            background: linear-gradient(135deg, #0a5a2a 0%, #018840 50%, #0a5a2a 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }
        .login-wrapper {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
            animation: fadeSlideUp 0.8s ease-out;
        }
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px 35px 30px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .brand-section {
            text-align: center;
            margin-bottom: 28px;
        }
        .govt-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 14px;
        }
        .govt-badge img {
            height: 48px;
            width: auto;
            object-fit: contain;
        }
        .ministry-name {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 2px;
        }
        .dept-name {
            font-size: 13px;
            color: #495057;
            font-weight: 500;
            line-height: 1.3;
            margin-bottom: 10px;
        }
        .brand-logo {
            margin-bottom: 8px;
        }
        .brand-logo img {
            max-height: 70px;
            width: auto;
        }
        .app-name {
            font-size: 22px;
            font-weight: 700;
            color: #0a5a2a;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        .app-desc {
            font-size: 13px;
            color: #6c757d;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(10, 90, 42, 0.2), transparent);
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 18px;
            position: relative;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .input-group-custom {
            position: relative;
        }
        .input-group-custom .form-control {
            height: 48px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px 10px 48px;
            font-size: 14px;
            background: #f8fafc;
            transition: all 0.25s ease;
            box-shadow: none;
        }
        .input-group-custom .form-control:focus {
            border-color: #018840;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(1, 136, 64, 0.12);
        }
        .input-group-custom .form-control.is-invalid {
            border-color: #e3342f;
            background: #fff;
        }
        .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
            z-index: 5;
            transition: color 0.25s ease;
            pointer-events: none;
        }
        .input-group-custom .form-control:focus ~ .input-icon {
            color: #018840;
        }
        .input-group-custom .invalid-feedback {
            font-size: 12px;
            margin-top: 4px;
            padding-left: 4px;
        }
        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #495057;
            user-select: none;
            width: fit-content;
        }
        .custom-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #018840;
            cursor: pointer;
            margin: 0;
        }
        .btn-signin {
            width: 100%;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0a5a2a, #018840);
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(1, 136, 64, 0.35);
            position: relative;
            overflow: hidden;
        }
        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(1, 136, 64, 0.45);
            background: linear-gradient(135deg, #0d6b33, #01a04b);
        }
        .btn-signin:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(1, 136, 64, 0.3);
        }
        .btn-signin i {
            margin-right: 8px;
        }
        .recaptcha-wrapper {
            margin-bottom: 18px;
            display: flex;
            justify-content: center;
        }
        .back-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #718096;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .back-home:hover {
            color: #0a5a2a;
            text-decoration: none;
        }
        .back-home i {
            margin-right: 5px;
        }
        .error-alert {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
            color: #c53030;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .error-alert i {
            font-size: 16px;
        }
        @media (max-width: 576px) {
            .glass-card {
                padding: 30px 20px 25px;
            }
            .govt-badge img {
                height: 38px;
            }
            .app-name {
                font-size: 18px;
            }
            body {
                padding: 12px;
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
                    $loginDesc = setting('app_description', '');
                    $ministry = setting('front.header_ministry', 'Government of Jammu & Kashmir (UT)');
                    $dept = setting('front.header_dept_en', 'Horticulture Department');
                    $emblem = setting('front.header_emblem_path', '');
                    $emblemUrl = $emblem ? asset('storage/' . $emblem) : 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Emblem_of_India.svg/120px-Emblem_of_India.svg.png';
                    $flag = setting('front.header_flag_path', '');
                    $flagUrl = $flag ? asset('storage/' . $flag) : 'https://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/120px-Flag_of_India.svg.png';
                @endphp

                <div class="ministry-name">{{ $ministry }}</div>
                <div class="dept-name">{{ $dept }}</div>

                @if($loginLogo)
                    <div class="brand-logo">
                        <img src="{{ asset('storage/' . $loginLogo) }}" alt="Logo">
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
                               placeholder="Enter your email"
                               value="{{ old('email') }}" required autofocus>
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group-custom">
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter your password" required>
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <span class="password-toggle" onclick="togglePassword()"
                              style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#a0aec0;z-index:5;font-size:16px;user-select:none;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <label class="custom-checkbox mb-0">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember Me</span>
                    </label>
                </div>

                @if(setting('recaptcha_enabled', false) && app()->environment('production'))
                    <div class="recaptcha-wrapper">
                        <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key', '') }}"></div>
                    </div>
                    @error('g-recaptcha-response')
                        <div class="error-alert" style="margin-top:-12px">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                @endif

                <button type="submit" class="btn-signin">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <a href="{{ route('home') }}" class="back-home">
                <i class="fas fa-arrow-left"></i> Back to Home
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
