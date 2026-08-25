<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use Auditable;
    protected $fillable = [
        'name', 'parentage', 'phone', 'aadhar_no',
        'lab_id', 'state_id', 'district_id', 'zone_id', 'panchayat_id', 'village_id', 'created_by'
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function state()
    {
        return $this->belongsTo(Location::class, 'state_id');
    }

    public function district()
    {
        return $this->belongsTo(Location::class, 'district_id');
    }

    public function zone()
    {
        return $this->belongsTo(Location::class, 'zone_id');
    }

    public function panchayat()
    {
        return $this->belongsTo(Location::class, 'panchayat_id');
    }

    public function village()
    {
        return $this->belongsTo(Location::class, 'village_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function plots()
    {
        return $this->hasMany(Plot::class);
    }

    public function soilSamples()
    {
        return $this->hasMany(SoilSample::class);
    }

    public function scopeByLab($query, $labId = null)
    {
        if ($labId) {
            return $query->where('lab_id', $labId);
        }
        return $query;
    }
}
