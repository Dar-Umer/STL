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
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    protected function remainingAttempts(Request $request): int
    {
        $limiter = app(RateLimiter::class);
        $key = $this->throttleKey($request);
        return max(0, 5 - $limiter->attempts($key));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
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
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->onlyInput('email');
            }
        }

        if ($limiter->tooManyAttempts($key, 5)) {
            $seconds = $limiter->availableIn($key);
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minute(s).',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $limiter->clear($key);
            $request->session()->regenerate();

            $user = Auth::user();
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
            'email' => $msg,
        ])->onlyInput('email');
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
