<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $state = Location::create(['name' => 'Jammu & Kashmir', 'type' => 'state', 'code' => 'JK']);
        $district = Location::create(['name' => 'Pulwama', 'type' => 'district', 'parent_id' => $state->id, 'code' => 'PW']);

        Location::where('type', 'zone')->whereNull('parent_id')->update(['parent_id' => $district->id]);
    }

    public function down(): void
    {
        Location::where('type', 'zone')->whereHas('parent', function ($q) {
            $q->where('type', 'district');
        })->update(['parent_id' => null]);

        Location::where('type', 'district')->delete();
        Location::where('type', 'state')->delete();
    }
};
