<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Auditable;
    protected $fillable = ['name', 'type', 'parent_id', 'code'];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function fieldOfficials()
    {
        return $this->hasMany(User::class, 'zone_id');
    }

    public function targets()
    {
        return $this->hasMany(Target::class, 'zone_id');
    }
}
