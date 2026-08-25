<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $fillable = ['custom_field_id', 'valueable_id', 'valueable_type', 'value'];

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function valueable()
    {
        return $this->morphTo();
    }
}
