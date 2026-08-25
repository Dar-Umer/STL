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
        Schema::table('sample_requests', function (Blueprint $table) {
            $table->string('submitter_ip', 45)->nullable()->after('soil_sample_id');
            $table->text('submitter_ua')->nullable()->after('submitter_ip');
        });
    }

    public function down(): void
    {
        Schema::table('sample_requests', function (Blueprint $table) {
            $table->dropColumn(['submitter_ip', 'submitter_ua']);
        });
    }
};
