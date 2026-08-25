<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soil_test_parameters', function (Blueprint $table) {
            $table->json('rating_colors')->nullable()->after('rating_labels');
        });
    }

    public function down(): void
    {
        Schema::table('soil_test_parameters', function (Blueprint $table) {
            $table->dropColumn('rating_colors');
        });
    }
};
