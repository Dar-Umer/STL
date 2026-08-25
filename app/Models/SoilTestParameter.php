<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SoilTestParameter extends Model
{
    use Auditable;
    protected $fillable = [
        'name', 'unit', 'min_range', 'max_range', 'method',
        'rating_low_max', 'rating_medium_max', 'rating_high_min',
        'rating_labels', 'rating_colors', 'order_column', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'rating_labels' => 'array',
            'rating_colors' => 'array',
        ];
    }

    public function getRatingLabel(string $tier): string
    {
        $defaults = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
        $labels = $this->rating_labels ?? [];
        return $labels[$tier] ?? $defaults[$tier] ?? ucfirst($tier);
    }

    public function getRatingColor(string $tier): string
    {
        $defaults = ['low' => '#ffc107', 'medium' => '#17a2b8', 'high' => '#28a745'];
        $colors = $this->rating_colors ?? [];
        return $colors[$tier] ?? $defaults[$tier];
    }

    public function getRatingBadgeStyle(string $tier): string
    {
        $bg = e($this->getRatingColor($tier));
        $light = in_array($bg, ['#ffc107', '#f8f9fa']) ? '#333' : '#fff';
        return "background:{$bg};color:{$light};padding:1px 8px;font-size:7.5pt;border-radius:10px;display:inline-block";
    }

    public function calculateRating($value): ?string
    {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return null;
        }
        $value = (float) $value;

        if ($this->rating_high_min !== null && $value >= (float) $this->rating_high_min) {
            return $this->getRatingLabel('high');
        }
        if ($this->rating_medium_max !== null && $value <= (float) $this->rating_medium_max) {
            if ($this->rating_low_max === null || $value > (float) $this->rating_low_max) {
                return $this->getRatingLabel('medium');
            }
        }
        if ($this->rating_low_max !== null && $value <= (float) $this->rating_low_max) {
            return $this->getRatingLabel('low');
        }

        return null;
    }

    public function getRangeAttribute(): ?string
    {
        if ($this->min_range !== null && $this->max_range !== null) {
            return number_format((float) $this->min_range, 2) . ' – ' . number_format((float) $this->max_range, 2);
        }
        if ($this->min_range !== null) {
            return '≥ ' . number_format((float) $this->min_range, 2);
        }
        if ($this->max_range !== null) {
            return '≤ ' . number_format((float) $this->max_range, 2);
        }
        return null;
    }

    public function results()
    {
        return $this->hasMany(SoilSampleResult::class, 'parameter_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab_test_parameter');
    }

    public function consumptionRules()
    {
        return $this->hasMany(ChemicalParameterConsumption::class, 'soil_test_parameter_id');
    }
}
