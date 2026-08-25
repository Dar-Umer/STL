<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChemicalParameterConsumption extends Model
{
    protected $table = 'chemical_parameter_consumption';

    protected $fillable = [
        'lab_chemical_id', 'soil_test_parameter_id', 'consumption_per_test',
    ];

    protected function casts(): array
    {
        return [
            'consumption_per_test' => 'decimal:2',
        ];
    }

    public function labChemical()
    {
        return $this->belongsTo(LabChemical::class);
    }

    public function parameter()
    {
        return $this->belongsTo(SoilTestParameter::class, 'soil_test_parameter_id');
    }
}
