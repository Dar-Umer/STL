<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoilType extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function plots()
    {
        return $this->hasMany(Plot::class);
    }
}
