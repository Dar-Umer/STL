<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soil_test_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->nullable();
            $table->decimal('min_range', 10, 2)->nullable();
            $table->decimal('max_range', 10, 2)->nullable();
            $table->string('method')->nullable();
            $table->decimal('rating_low_max', 10, 2)->nullable();
            $table->decimal('rating_medium_max', 10, 2)->nullable();
            $table->decimal('rating_high_min', 10, 2)->nullable();
            $table->integer('order_column')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soil_test_parameters');
    }
};
