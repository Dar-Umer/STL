<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoilSampleResult extends Model
{
    protected $fillable = ['soil_sample_id', 'parameter_id', 'value', 'rating'];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
        ];
    }

    public function soilSample()
    {
        return $this->belongsTo(SoilSample::class);
    }

    public function parameter()
    {
        return $this->belongsTo(SoilTestParameter::class, 'parameter_id');
    }
}
