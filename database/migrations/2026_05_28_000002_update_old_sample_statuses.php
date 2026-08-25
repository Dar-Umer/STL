<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('soil_samples')
            ->whereIn('status', ['in_transit', 'testing', 'analyzed', 'rejected'])
            ->update(['status' => DB::raw("CASE
                WHEN status = 'in_transit' THEN 'collected'
                WHEN status = 'testing' THEN 'received'
                WHEN status = 'analyzed' THEN 'reported'
                WHEN status = 'rejected' THEN 'collected'
            END")]);
    }

    public function down(): void
    {
    }
};
