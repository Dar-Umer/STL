<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginInput = $request->input('phone') 
            ?? $request->input('login') 
            ?? $request->input('email');

        if (!$loginInput) {
            throw ValidationException::withMessages([
                'login' => 'Mobile number or email is required.',
            ]);
        }

        $request->validate([
            'password' => 'required',
        ]);

        $key = 'api-login|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'login' => 'Too many attempts. Try again in ' . ceil($seconds / 60) . ' min.',
            ]);
        }

        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = null;

        if ($fieldType === 'phone') {
            $rawPhone = trim((string)$loginInput);
            $digits = preg_replace('/[^0-9]/', '', $rawPhone);
            $last10 = strlen($digits) >= 10 ? substr($digits, -10) : $digits;

            $user = \App\Models\User::where('phone', $rawPhone)
                ->orWhere('phone', $digits)
                ->orWhere('phone', '+91' . $last10)
                ->orWhere('phone', '+91 ' . $last10)
                ->orWhere('phone', '91' . $last10)
                ->orWhere('phone', '0' . $last10)
                ->orWhere('phone', $last10)
                ->orWhere('phone', 'like', "%{$last10}")
                ->first();

            if (!$user && strlen($last10) === 10) {
                $user = \App\Models\User::whereNotNull('phone')
                    ->get()
                    ->first(function ($u) use ($last10) {
                        $dbDigits = preg_replace('/[^0-9]/', '', (string)$u->phone);
                        return substr($dbDigits, -10) === $last10;
                    });
            }

            if (!$user || !Hash::check($request->password, $user->password)) {
                RateLimiter::hit($key, 60);
                throw ValidationException::withMessages([
                    'login' => 'Invalid credentials.',
                ]);
            }

            Auth::login($user);
        } else {
            $email = trim((string)$loginInput);
            if (!Auth::attempt(['email' => $email, 'password' => $request->password])) {
                RateLimiter::hit($key, 60);
                throw ValidationException::withMessages([
                    'login' => 'Invalid credentials.',
                ]);
            }
            $user = Auth::user();
        }

        if (!$user->is_active) {
            Auth::logout();
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'login' => 'Your account has been deactivated.',
            ]);
        }

        RateLimiter::clear($key);
        $token = $user->createToken('flutter-app')->plainTextToken;

        $lab = Lab::find($user->lab_id);
        $zone = Location::find($user->zone_id);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'is_active' => (bool) $user->is_active,
                'lab_id' => $user->lab_id,
                'zone_id' => $user->zone_id,
                'roles' => $user->getRoleNames(),
                'lab' => $lab ? ['id' => $lab->id, 'name' => $lab->name, 'code' => $lab->code] : null,
                'zone' => $zone ? ['id' => $zone->id, 'name' => $zone->name] : null,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $lab = Lab::find($user->lab_id);
        $zone = Location::find($user->zone_id);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'is_active' => (bool) $user->is_active,
                'lab_id' => $user->lab_id,
                'zone_id' => $user->zone_id,
                'roles' => $user->getRoleNames(),
                'lab' => $lab ? ['id' => $lab->id, 'name' => $lab->name, 'code' => $lab->code] : null,
                'zone' => $zone ? ['id' => $zone->id, 'name' => $zone->name] : null,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        $lab = Lab::find($user->lab_id);
        $zone = Location::find($user->zone_id);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'is_active' => (bool) $user->is_active,
                'lab_id' => $user->lab_id,
                'zone_id' => $user->zone_id,
                'roles' => $user->getRoleNames(),
                'lab' => $lab ? ['id' => $lab->id, 'name' => $lab->name, 'code' => $lab->code] : null,
                'zone' => $zone ? ['id' => $zone->id, 'name' => $zone->name] : null,
            ],
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update(['password' => $request->password]);

        return response()->json(['message' => 'Password updated.']);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();

        $path = $request->file('avatar')->store('avatars', 'public');

        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => $path]);

        $lab = Lab::find($user->lab_id);
        $zone = Location::find($user->zone_id);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'is_active' => (bool) $user->is_active,
                'lab_id' => $user->lab_id,
                'zone_id' => $user->zone_id,
                'roles' => $user->getRoleNames(),
                'lab' => $lab ? ['id' => $lab->id, 'name' => $lab->name, 'code' => $lab->code] : null,
                'zone' => $zone ? ['id' => $zone->id, 'name' => $zone->name] : null,
            ],
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $loginInput = $request->input('login') 
            ?? $request->input('phone') 
            ?? $request->input('email');

        if (!$loginInput) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide your registered mobile number or email address.',
            ], 422);
        }

        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = null;

        if ($fieldType === 'phone') {
            $rawPhone = trim((string)$loginInput);
            $digits = preg_replace('/[^0-9]/', '', $rawPhone);
            $last10 = strlen($digits) >= 10 ? substr($digits, -10) : $digits;

            $user = \App\Models\User::where('phone', $rawPhone)
                ->orWhere('phone', $digits)
                ->orWhere('phone', '+91' . $last10)
                ->orWhere('phone', '91' . $last10)
                ->orWhere('phone', '0' . $last10)
                ->orWhere('phone', $last10)
                ->orWhere('phone', 'like', "%{$last10}")
                ->first();

            if (!$user && strlen($last10) === 10) {
                $user = \App\Models\User::whereNotNull('phone')
                    ->get()
                    ->first(function ($u) use ($last10) {
                        $p = preg_replace('/[^0-9]/', '', (string)$u->phone);
                        return strlen($p) >= 10 && substr($p, -10) === $last10;
                    });
            }
        } else {
            $user = \App\Models\User::where('email', strtolower(trim($loginInput)))->first();
        }

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No registered account found with that mobile number or email address.',
            ], 404);
        }

        if (empty($user->email)) {
            return response()->json([
                'status' => false,
                'message' => 'Your account does not have a registered email address. Please contact your lab administrator to update your email.',
            ], 422);
        }

        // Generate a 6-digit cryptographic OTP
        $otp = sprintf('%06d', random_int(100000, 999999));

        // Store OTP in Cache for 15 minutes
        $cacheKey = "password_otp:{$user->id}";
        \Illuminate\Support\Facades\Cache::put($cacheKey, [
            'otp' => $otp,
            'user_id' => $user->id,
            'email' => $user->email,
            'created_at' => now()->timestamp,
        ], now()->addMinutes(15));

        // Mask the email address: e.g. f***d@soillab.com
        $parts = explode('@', $user->email);
        $namePart = $parts[0];
        $domainPart = $parts[1] ?? '';
        if (strlen($namePart) <= 2) {
            $maskedName = substr($namePart, 0, 1) . '***';
        } else {
            $maskedName = substr($namePart, 0, 1) . str_repeat('*', max(3, strlen($namePart) - 2)) . substr($namePart, -1);
        }
        $maskedEmail = "{$maskedName}@{$domainPart}";

        // Configure dynamic SMTP from settings
        configure_dynamic_smtp();

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(
                new \App\Mail\PasswordResetOtpMail($user->name, $otp)
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Failed to dispatch OTP via SMTP: {$e->getMessage()}. Logging fallback.");
            \Illuminate\Support\Facades\Log::info("PASSWORD RESET OTP for User #{$user->id} ({$user->email}): {$otp}");
        }

        return response()->json([
            'status' => true,
            'message' => "A 6-digit verification code (OTP) has been sent to {$maskedEmail}.",
            'email_masked' => $maskedEmail,
            'email' => $user->email,
            'user_id' => $user->id,
            'debug_otp' => app()->environment('local', 'testing') ? $otp : null,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $loginInput = $request->input('login') 
            ?? $request->input('email') 
            ?? $request->input('phone');

        $otpInput = trim($request->input('otp'));

        $user = null;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', strtolower(trim($loginInput)))->first();
        } else {
            $digits = preg_replace('/[^0-9]/', '', (string)$loginInput);
            $last10 = strlen($digits) >= 10 ? substr($digits, -10) : $digits;
            $user = \App\Models\User::where('phone', 'like', "%{$last10}")->first();
        }

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found.',
            ], 404);
        }

        $cached = \Illuminate\Support\Facades\Cache::get("password_otp:{$user->id}");

        if (!$cached || !isset($cached['otp']) || (string)$cached['otp'] !== $otpInput) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP. Please check the code or request a new one.',
            ], 422);
        }

        // Generate temporary reset token valid for 15 minutes
        $resetToken = \Illuminate\Support\Str::random(40);
        \Illuminate\Support\Facades\Cache::put("password_reset_token:{$resetToken}", $user->id, now()->addMinutes(15));

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully.',
            'reset_token' => $resetToken,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = null;
        $resetToken = $request->input('reset_token');
        $otp = $request->input('otp');
        $loginInput = $request->input('login') ?? $request->input('email') ?? $request->input('phone');

        if ($resetToken) {
            $userId = \Illuminate\Support\Facades\Cache::get("password_reset_token:{$resetToken}");
            if ($userId) {
                $user = \App\Models\User::find($userId);
            }
        }

        if (!$user && $otp && $loginInput) {
            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                $user = \App\Models\User::where('email', strtolower(trim($loginInput)))->first();
            } else {
                $digits = preg_replace('/[^0-9]/', '', (string)$loginInput);
                $last10 = strlen($digits) >= 10 ? substr($digits, -10) : $digits;
                $user = \App\Models\User::where('phone', 'like', "%{$last10}")->first();
            }

            if ($user) {
                $cached = \Illuminate\Support\Facades\Cache::get("password_otp:{$user->id}");
                if (!$cached || (string)($cached['otp'] ?? '') !== trim($otp)) {
                    $user = null;
                }
            }
        }

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired session. Please request a new OTP.',
            ], 422);
        }

        // Update password
        $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        $user->save();

        // Invalidate tokens and OTP
        if ($resetToken) {
            \Illuminate\Support\Facades\Cache::forget("password_reset_token:{$resetToken}");
        }
        \Illuminate\Support\Facades\Cache::forget("password_otp:{$user->id}");

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully! You can now log in with your new password.',
        ]);
    }
}
