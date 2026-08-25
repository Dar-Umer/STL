<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            if (static::shouldSkipLog('created', $model)) return;

            AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'event' => 'created',
                'old_values' => null,
                'new_values' => $model->getAuditedAttributes(),
                'url' => Request::fullUrl(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        });

        static::updated(function ($model) {
            if (static::shouldSkipLog('updated', $model)) return;

            $old = [];
            $new = [];
            $changed = $model->getChanges();

            foreach ($changed as $key => $value) {
                if (in_array($key, ['updated_at'])) continue;
                $old[$key] = $model->getOriginal($key);
                $new[$key] = $value;
            }

            if (empty($new)) return;

            AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'event' => 'updated',
                'old_values' => $old,
                'new_values' => $new,
                'url' => Request::fullUrl(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        });

        static::deleted(function ($model) {
            if (static::shouldSkipLog('deleted', $model)) return;

            AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'event' => 'deleted',
                'old_values' => $model->getAuditedAttributes(),
                'new_values' => null,
                'url' => Request::fullUrl(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        });
    }

    protected function getAuditedAttributes(): array
    {
        $attributes = $this->toArray();
        $hidden = $this->getAuditedHidden();

        foreach ($hidden as $key) {
            unset($attributes[$key]);
        }

        return $attributes;
    }

    protected function getAuditedHidden(): array
    {
        return method_exists($this, 'getHidden')
            ? $this->getHidden()
            : ($this->hidden ?? []);
    }

    protected static function shouldSkipLog(string $event, $model): bool
    {
        return false;
    }
}
