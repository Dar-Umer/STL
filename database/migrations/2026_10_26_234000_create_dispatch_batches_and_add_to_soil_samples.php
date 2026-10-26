<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dispatch_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no', 50)->unique()->index();
            $table->string('dispatch_no', 100)->nullable();
            $table->string('date', 50)->nullable();
            $table->foreignId('zone_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('lab_id')->nullable()->constrained('labs')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('sample_count')->default(0);
            $table->string('status', 30)->default('dispatched')->index(); // 'dispatched', 'received', 'cancelled'
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->foreignId('evaluation_batch_id')->nullable()->constrained('evaluation_batches')->nullOnDelete();

            // Covering letter text snapshot
            $table->string('header_title', 255)->nullable();
            $table->string('subheader', 255)->nullable();
            $table->text('addressed_to')->nullable();
            $table->text('subject')->nullable();
            $table->text('body_text')->nullable();
            $table->string('hdo_title', 255)->nullable();
            $table->string('officer_name', 255)->nullable();
            $table->string('officer_designation', 100)->nullable();
            $table->string('officer_phone', 50)->nullable();

            $table->timestamps();
        });

        Schema::table('soil_samples', function (Blueprint $table) {
            $table->foreignId('dispatch_batch_id')->nullable()->after('evaluation_batch_id')->constrained('dispatch_batches')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soil_samples', function (Blueprint $table) {
            $table->dropForeign(['dispatch_batch_id']);
            $table->dropColumn('dispatch_batch_id');
        });

        Schema::dropIfExists('dispatch_batches');
    }
};
