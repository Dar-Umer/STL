<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use Auditable;
    protected $fillable = [
        'zone_id', 'user_id', 'type', 'title', 'body', 'data', 'is_read',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_read' => 'boolean',
        ];
    }

    public function zone()
    {
        return $this->belongsTo(Location::class, 'zone_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
