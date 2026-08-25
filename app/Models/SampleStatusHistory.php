<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleStatusHistory extends Model
{
    protected $fillable = ['soil_sample_id', 'from_status', 'to_status', 'changed_by', 'notes'];

    public function soilSample()
    {
        return $this->belongsTo(SoilSample::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
