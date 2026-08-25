<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
        'lab_id',
        'zone_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function farmer()
    {
        return $this->hasOne(Farmer::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function zone()
    {
        return $this->belongsTo(Location::class, 'zone_id');
    }

    public function collectedSamples()
    {
        return $this->hasMany(SoilSample::class, 'collected_by');
    }

    public function analyzedSamples()
    {
        return $this->hasMany(SoilSample::class, 'analyzed_by');
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }
}
