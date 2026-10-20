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
        Schema::create('evaluation_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code', 50)->unique()->index();
            $table->foreignId('lab_id')->nullable()->constrained('labs')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('sample_count')->default(0);
            $table->string('lab_number_from', 50)->nullable();
            $table->string('lab_number_to', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('soil_samples', function (Blueprint $table) {
            $table->string('lab_number', 50)->nullable()->index()->after('sample_code');
            $table->foreignId('evaluation_batch_id')->nullable()->after('lab_number')->constrained('evaluation_batches')->nullOnDelete();
            $table->text('evaluation_remarks')->nullable()->after('recommendations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soil_samples', function (Blueprint $table) {
            $table->dropForeign(['evaluation_batch_id']);
            $table->dropColumn(['lab_number', 'evaluation_batch_id', 'evaluation_remarks']);
        });

        Schema::dropIfExists('evaluation_batches');
    }
};
