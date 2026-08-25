<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'notes',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('auditable_type', $type);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function getAuditableLabelAttribute(): string
    {
        $short = class_basename($this->auditable_type);
        $id = $this->auditable_id ?? '-';

        if ($this->auditable_type === Setting::class) {
            $key = $this->new_values['key'] ?? $this->old_values['key'] ?? $id;
            return "Setting: {$key}";
        }

        if ($this->event === 'login' || $this->event === 'logout') {
            return $short;
        }

        return "{$short} #{$id}";
    }
}
