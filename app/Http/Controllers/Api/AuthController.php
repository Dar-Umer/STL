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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'api-login|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => 'Too many attempts. Try again in ' . ceil($seconds / 60) . ' min.',
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated.',
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
