<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    use Auditable;
    protected $fillable = [
        'farmer_id', 'state_id', 'district_id', 'zone_id', 'panchayat_id', 'village_id',
        'plot_name', 'khasra_no', 'latitude', 'longitude',
        'soil_type_id', 'soil_color_id', 'area_kanals', 'crop_id',
        'variety', 'age_of_plants'
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'area_kanals' => 'float',
            'age_of_plants' => 'float',
        ];
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
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

    public function soilType()
    {
        return $this->belongsTo(SoilType::class);
    }

    public function soilColor()
    {
        return $this->belongsTo(SoilColor::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
