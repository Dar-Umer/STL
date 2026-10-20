<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationBatch extends Model
{
    protected $fillable = [
        'batch_code',
        'lab_id',
        'received_by',
        'sample_count',
        'lab_number_from',
        'lab_number_to',
        'notes',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function soilSamples()
    {
        return $this->hasMany(SoilSample::class, 'evaluation_batch_id');
    }

    public function samples()
    {
        return $this->soilSamples();
    }

    public function getTestedCountAttribute(): int
    {
        return $this->soilSamples()->where('status', 'reported')->count();
    }

    public function getPendingCountAttribute(): int
    {
        return $this->soilSamples()->where('status', '!=', 'reported')->count();
    }

    public function getProgressPercentAttribute(): int
    {
        if ($this->sample_count === 0) {
            return 0;
        }
        return (int) round(($this->tested_count / $this->sample_count) * 100);
    }
}
