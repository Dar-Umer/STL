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
