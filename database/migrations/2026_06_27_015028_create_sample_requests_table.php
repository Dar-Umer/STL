<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();
            $table->string('farmer_name');
            $table->string('farmer_phone');
            $table->text('farmer_address')->nullable();
            $table->foreignId('zone_id')->constrained('locations');
            $table->string('status')->default('pending');
            $table->foreignId('field_official_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('soil_sample_id')->nullable()->constrained('soil_samples')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_requests');
    }
};
