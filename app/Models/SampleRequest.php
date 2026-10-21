<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SampleRequest extends Model
{
    use Auditable;
    protected $fillable = [
        'request_id', 'farmer_name', 'farmer_phone', 'aadhar_no', 'farmer_address',
        'zone_id', 'status', 'field_official_id', 'soil_sample_id',
        'submitter_ip', 'submitter_ua',
    ];

    public function zone()
    {
        return $this->belongsTo(Location::class, 'zone_id');
    }

    public function fieldOfficial()
    {
        return $this->belongsTo(User::class, 'field_official_id');
    }

    public function soilSample()
    {
        return $this->belongsTo(SoilSample::class, 'soil_sample_id');
    }

    public static function generateRequestId(): string
    {
        $last = static::orderBy('id', 'desc')->value('id');
        $next = ($last ?? 0) + 1;
        return 'REQ-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
