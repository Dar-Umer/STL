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
}
