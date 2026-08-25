<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChemicalStockMovement extends Model
{
    protected $fillable = [
        'lab_chemical_id', 'type', 'quantity',
        'balance_before', 'balance_after',
        'reference_type', 'reference_id',
        'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function labChemical()
    {
        return $this->belongsTo(LabChemical::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
