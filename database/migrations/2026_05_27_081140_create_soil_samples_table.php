<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soil_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained();
            $table->foreignId('plot_id')->constrained();
            $table->string('sample_code')->unique();
            $table->string('qr_code_path')->nullable();
            $table->foreignId('collected_by')->constrained('users');
            $table->datetime('collected_at');
            $table->string('status')->default('collected');
            $table->datetime('received_at')->nullable();
            $table->foreignId('analyzed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('analyzed_at')->nullable();
            $table->text('recommendations')->nullable();
            $table->foreignId('lab_id')->constrained('labs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soil_samples');
    }
};
