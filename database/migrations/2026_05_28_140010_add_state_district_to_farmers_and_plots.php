<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('lab_id')->constrained('locations')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('state_id')->constrained('locations')->nullOnDelete();
        });

        Schema::table('plots', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('farmer_id')->constrained('locations')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('state_id')->constrained('locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['state_id', 'district_id']);
        });

        Schema::table('plots', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['state_id', 'district_id']);
        });
    }
};
