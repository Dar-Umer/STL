<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soil_sample_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soil_sample_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parameter_id')->constrained('soil_test_parameters')->cascadeOnDelete();
            $table->decimal('value', 10, 2)->nullable();
            $table->string('rating')->nullable();
            $table->timestamps();

            $table->unique(['soil_sample_id', 'parameter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soil_sample_results');
    }
};
