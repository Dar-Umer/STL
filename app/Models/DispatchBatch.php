<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispatchBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_no',
        'dispatch_no',
        'date',
        'zone_id',
        'lab_id',
        'created_by',
        'sample_count',
        'status',
        'received_by',
        'received_at',
        'evaluation_batch_id',
        'header_title',
        'subheader',
        'addressed_to',
        'subject',
        'body_text',
        'hdo_title',
        'officer_name',
        'officer_designation',
        'officer_phone',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'sample_count' => 'integer',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'zone_id');
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function evaluationBatch(): BelongsTo
    {
        return $this->belongsTo(EvaluationBatch::class, 'evaluation_batch_id');
    }

    public function soilSamples(): HasMany
    {
        return $this->hasMany(SoilSample::class, 'dispatch_batch_id');
    }

    /**
     * Generate sequential batch number like BATTZ01, BATTZ02, ...
     * Uses Zone Code (e.g. 'TZ' for Tral Zone) matching sample code pattern.
     */
    public static function generateNextBatchNo(?int $zoneId): string
    {
        $zoneCode = '0';
        if ($zoneId) {
            $zone = Location::find($zoneId);
            $zoneCode = !empty($zone?->code) ? strtoupper($zone->code) : (string) $zoneId;
        }

        $prefix = 'BAT' . $zoneCode;

        $latest = static::where('batch_no', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNum = 1;
        if ($latest && preg_match('/^' . preg_quote($prefix, '/') . '(\d+)$/', $latest->batch_no, $matches)) {
            $nextNum = ((int) $matches[1]) + 1;
        }

        $code = $prefix . str_pad((string) $nextNum, 2, '0', STR_PAD_LEFT);
        while (static::where('batch_no', $code)->exists()) {
            $nextNum++;
            $code = $prefix . str_pad((string) $nextNum, 2, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}
