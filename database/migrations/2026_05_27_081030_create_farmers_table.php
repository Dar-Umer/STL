<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('parentage')->nullable();
            $table->string('phone');
            $table->string('aadhar_no')->nullable()->unique();
            $table->foreignId('lab_id')->constrained('labs');
            $table->foreignId('zone_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('panchayat_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
