<?php

namespace App\Http\Controllers;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->dashboardRoute());
        }
        return view('auth.login', [
            'attempts' => $this->remainingAttempts(request()),
        ]);
    }

    protected function dashboardRoute(): string
    {
        $user = Auth::user();
        if ($user->hasRole('Admin')) return route('admin.dashboard');
        if ($user->hasRole(['Lab Manager', 'Lab Technician'])) return route('lab.dashboard');
        if ($user->hasRole('Field Official')) return route('field.collect-sample');
        if ($user->hasRole('Farmer')) return route('samples.index');
        return '/';
    }

    protected function throttleKey(Request $request): string
    {
        $login = Str::lower($request->input('login', $request->input('email', '')));
        return $login . '|' . $request->ip();
    }

    protected function remainingAttempts(Request $request): int
    {
        $limiter = app(RateLimiter::class);
        $key = $this->throttleKey($request);
        return max(0, 5 - $limiter->attempts($key));
    }

    public function login(Request $request)
    {
        $loginInput = trim((string) $request->input('login', $request->input('email', '')));
        $request->merge(['login' => $loginInput]);

        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $limiter = app(RateLimiter::class);
        $key = $this->throttleKey($request);

        if (setting('recaptcha_enabled', false) && app()->environment('production')) {
            $request->validate(['g-recaptcha-response' => 'required']);
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => setting('recaptcha_secret_key', ''),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);
            if (!($response->json()['success'] ?? false)) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->onlyInput('login');
            }
        }

        if ($limiter->tooManyAttempts($key, 5)) {
            $seconds = $limiter->availableIn($key);
            return back()->withErrors([
                'login' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minute(s).',
            ])->onlyInput('login');
        }

        $password = $request->input('password');
        $remember = $request->boolean('remember');
        $user = null;

        if (str_contains($loginInput, '@')) {
            if (Auth::attempt(['email' => $loginInput, 'password' => $password], $remember)) {
                $user = Auth::user();
            }
        } else {
            $digits = preg_replace('/[^0-9]/', '', $loginInput);
            $last10 = strlen($digits) >= 10 ? substr($digits, -10) : $digits;

            $candidates = array_unique(array_filter([
                $loginInput,
                $digits,
                $last10,
                '+91' . $last10,
                '+91 ' . $last10,
                '91' . $last10,
                '0' . $last10,
            ]));

            $candidateUsers = \App\Models\User::where(function ($q) use ($candidates, $last10) {
                $q->whereIn('phone', $candidates);
                if (strlen($last10) === 10) {
                    $q->orWhere('phone', 'LIKE', '%' . $last10);
                }
            })->get();

            foreach ($candidateUsers as $u) {
                if (\Illuminate\Support\Facades\Hash::check($password, $u->password)) {
                    $user = $u;
                    Auth::login($u, $remember);
                    break;
                }
            }
        }

        if ($user) {
            $limiter->clear($key);
            $request->session()->regenerate();

            AuditLogger::logLogin($user);
            if ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->hasRole(['Lab Manager', 'Lab Technician'])) {
                return redirect()->route('lab.dashboard');
            }
            if ($user->hasRole('Field Official')) {
                return redirect()->route('field.collect-sample');
            }
            if ($user->hasRole('Farmer')) {
                return redirect()->route('samples.index');
            }

            return redirect('/');
        }

        $limiter->hit($key, 60);
        $remaining = $this->remainingAttempts($request);
        $msg = 'The provided credentials do not match our records.';
        if ($remaining > 0) {
            $msg .= ' <strong>' . $remaining . ' attempt(s) remaining.</strong>';
        }

        return back()->withErrors([
            'login' => $msg,
            'email' => $msg,
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditLogger::logLogout($user);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
