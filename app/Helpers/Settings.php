<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$key}";

        return Cache::rememberForever($cacheKey, function () use ($key, $default) {
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    return $default;
                }
                $setting = Setting::where('key', $key)->first();
                if (!$setting) {
                    return $default;
                }
                $val = $setting->value;
                if ($val === '1') {
                    return true;
                }
                if ($val === '0') {
                    return false;
                }
                return $val;
            } catch (\Throwable $e) {
                return $default;
            }
        });
    }
}

if (!function_exists('set_setting')) {
    function set_setting(string $key, mixed $value): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                Setting::updateOrCreate(['key' => $key], ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]);
            }
        } catch (\Throwable $e) {
            // Silently fallback if table is not migrated yet
        }
        Cache::forever("settings.{$key}", is_bool($value) ? $value : (string) $value);
    }
}

if (!function_exists('forget_setting')) {
    function forget_setting(string $key): void
    {
        Cache::forget("settings.{$key}");
    }
}

if (!function_exists('configure_dynamic_smtp')) {
    function configure_dynamic_smtp(): void
    {
        $host = setting('mail_host');

        if ($host) {
            $encryption = setting('mail_encryption', 'tls');
            if ($encryption === 'none' || empty($encryption)) {
                $encryption = null;
            }

            $mailer = setting('mail_mailer', 'smtp');

            config([
                'mail.default' => $mailer ?: 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => (int) setting('mail_port', 587),
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.username' => setting('mail_username'),
                'mail.mailers.smtp.password' => setting('mail_password'),
                'mail.from.address' => setting('mail_from_address', config('mail.from.address') ?: 'noreply@soillab.gov.in'),
                'mail.from.name' => setting('mail_from_name', setting('app_name', config('app.name')) ?: 'Soil Testing Laboratory'),
            ]);
        }
    }
}
