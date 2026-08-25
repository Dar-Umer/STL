<?php

use App\Models\QrCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->foreignId('lab_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        $firstLabId = DB::table('labs')->orderBy('id')->value('id');
        if ($firstLabId) {
            QrCode::whereNull('lab_id')->update(['lab_id' => $firstLabId]);
        }
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropForeign(['lab_id']);
            $table->dropColumn('lab_id');
        });
    }
};
