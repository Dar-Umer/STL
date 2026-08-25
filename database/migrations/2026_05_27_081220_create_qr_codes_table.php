<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('prefix');
            $table->integer('sequence');
            $table->boolean('is_used')->default(false);
            $table->foreignId('used_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('used_at')->nullable();
            $table->foreignId('soil_sample_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->unique(['prefix', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
