<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->foreignId('zone_id')->nullable()->after('lab_id')->constrained('locations')->nullOnDelete();
        });

        // Backfill existing QR codes zone_id from location codes
        $zones = DB::table('locations')->where('type', 'zone')->whereNotNull('code')->get();
        foreach ($zones as $zone) {
            DB::table('qr_codes')
                ->where('prefix', $zone->code)
                ->update(['zone_id' => $zone->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
        });
    }
};
