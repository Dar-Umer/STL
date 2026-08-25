<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use Auditable;
    protected $fillable = ['code', 'prefix', 'sequence', 'is_used', 'used_by', 'used_at', 'soil_sample_id', 'lab_id', 'created_by'];

    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
            'used_at' => 'datetime',
        ];
    }

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function soilSample()
    {
        return $this->belongsTo(SoilSample::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function scopeByLab($query, $labId = null)
    {
        return $labId ? $query->where('lab_id', $labId) : $query;
    }
}
