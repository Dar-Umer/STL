<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function logLogin($user): void
    {
        AuditLog::create([
            'user_id' => $user->getKey(),
            'auditable_type' => get_class($user),
            'auditable_id' => $user->getKey(),
            'event' => 'login',
            'old_values' => null,
            'new_values' => ['name' => $user->name, 'email' => $user->email],
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public static function logLogout($user): void
    {
        AuditLog::create([
            'user_id' => $user->getKey(),
            'auditable_type' => get_class($user),
            'auditable_id' => $user->getKey(),
            'event' => 'logout',
            'old_values' => null,
            'new_values' => ['name' => $user->name],
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public static function logSettingsChange(array $oldValues, array $newValues): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => Setting::class,
            'auditable_id' => null,
            'event' => 'settings_changed',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
