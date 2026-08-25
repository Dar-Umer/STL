<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chemical_parameter_consumption', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_chemical_id')->constrained()->cascadeOnDelete();
            $table->foreignId('soil_test_parameter_id')->constrained()->cascadeOnDelete();
            $table->decimal('consumption_per_test', 12, 2);
            $table->timestamps();
            $table->unique(['lab_chemical_id', 'soil_test_parameter_id'], 'chem_param_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chemical_parameter_consumption');
    }
};
