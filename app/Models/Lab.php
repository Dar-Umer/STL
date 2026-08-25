<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use Auditable;
    protected $fillable = ['name', 'code', 'address', 'phone', 'email', 'contact_person', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function farmers()
    {
        return $this->hasMany(Farmer::class);
    }

    public function soilSamples()
    {
        return $this->hasMany(SoilSample::class);
    }

    public function parameters()
    {
        return $this->belongsToMany(SoilTestParameter::class, 'lab_test_parameter');
    }
}
