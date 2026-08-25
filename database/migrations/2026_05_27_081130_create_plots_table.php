<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('panchayat_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('plot_name');
            $table->string('khasra_no')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('soil_type_id')->nullable()->constrained('soil_types')->nullOnDelete();
            $table->foreignId('soil_color_id')->nullable()->constrained('soil_colors')->nullOnDelete();
            $table->decimal('area_kanals', 8, 2)->nullable();
            $table->foreignId('crop_id')->nullable()->constrained('crops')->nullOnDelete();
            $table->string('variety')->nullable();
            $table->string('age_of_plants')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
