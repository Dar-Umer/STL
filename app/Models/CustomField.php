<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use Auditable;
    protected $fillable = ['name', 'type', 'options', 'required', 'order_column', 'is_active'];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'is_active' => 'boolean',
            'options' => 'array',
        ];
    }

    public function values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
