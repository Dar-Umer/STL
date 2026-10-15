<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Location::withoutEvents(function () {
            $state = Location::firstOrCreate(
                ['type' => 'state', 'name' => 'Jammu & Kashmir'],
                ['code' => 'JK']
            );

            $district = Location::firstOrCreate(
                ['type' => 'district', 'name' => 'Pulwama'],
                ['parent_id' => $state->id, 'code' => 'PW']
            );

            Location::where('type', 'zone')->whereNull('parent_id')->update(['parent_id' => $district->id]);
        });
    }

    public function down(): void
    {
        Location::withoutEvents(function () {
            Location::where('type', 'zone')->whereHas('parent', function ($q) {
                $q->where('type', 'district');
            })->update(['parent_id' => null]);

            Location::where('type', 'district')->where('name', 'Pulwama')->delete();
            Location::where('type', 'state')->where('name', 'Jammu & Kashmir')->delete();
        });
    }
};
