<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use Auditable;
    protected $fillable = [
        'zone_id',
        'target_count',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_count' => 'integer',
    ];

    public function zone()
    {
        return $this->belongsTo(Location::class, 'zone_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function achievedCount(): int
    {
        return SoilSample::where('source', 'field')->whereHas('farmer', function ($q) {
            $q->where('zone_id', $this->zone_id);
        })
        ->whereIn('status', ['received', 'testing', 'reported'])
        ->whereBetween('received_at', [
            $this->start_date->copy()->startOfDay(),
            $this->end_date->copy()->endOfDay(),
        ])
        ->count();
    }

    public function collectedCount(): int
    {
        return SoilSample::where('source', 'field')->whereHas('farmer', function ($q) {
            $q->where('zone_id', $this->zone_id);
        })
        ->whereBetween('collected_at', [
            $this->start_date->copy()->startOfDay(),
            $this->end_date->copy()->endOfDay(),
        ])
        ->count();
    }

    public function progressPercent(): int
    {
        if ($this->target_count <= 0) {
            return 0;
        }
        return min(100, (int) round(($this->achievedCount() / $this->target_count) * 100));
    }

    public function isExceeded(): bool
    {
        return $this->collectedCount() >= $this->target_count;
    }

    public function remainingCount(): int
    {
        return max(0, $this->target_count - $this->achievedCount());
    }

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now()->startOfDay())
            ->where('end_date', '>=', now()->startOfDay());
    }

    public function scopeForZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }
}
