<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class LabChemical extends Model
{
    use Auditable;
    protected $fillable = [
        'lab_id', 'name', 'sku', 'unit',
        'current_stock', 'minimum_stock', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'current_stock' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
        ];
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(ChemicalStockMovement::class, 'lab_chemical_id');
    }

    public function consumptionRules()
    {
        return $this->hasMany(ChemicalParameterConsumption::class, 'lab_chemical_id');
    }

    public function stockStatus(): string
    {
        if (!$this->minimum_stock) return 'normal';
        if ($this->current_stock <= 0) return 'empty';
        if ($this->current_stock <= $this->minimum_stock * 0.5) return 'critical';
        if ($this->current_stock <= $this->minimum_stock) return 'low';
        return 'normal';
    }
}
