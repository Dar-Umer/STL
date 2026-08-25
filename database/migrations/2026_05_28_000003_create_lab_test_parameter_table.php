<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_test_parameter', function (Blueprint $table) {
            $table->foreignId('lab_id')->constrained()->cascadeOnDelete();
            $table->foreignId('soil_test_parameter_id')->constrained()->cascadeOnDelete();
            $table->primary(['lab_id', 'soil_test_parameter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_test_parameter');
    }
};
