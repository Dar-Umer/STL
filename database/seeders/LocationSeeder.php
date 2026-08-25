<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // State
        $state = Location::create(['name' => 'Jammu & Kashmir', 'type' => 'state', 'code' => 'JK']);

        // District
        $pulwama = Location::create(['name' => 'Pulwama', 'type' => 'district', 'parent_id' => $state->id, 'code' => 'PW']);

        // Zones under Pulwama
        $tral = Location::create(['name' => 'Tral Zone', 'type' => 'zone', 'parent_id' => $pulwama->id, 'code' => 'TZ']);
        $pampore = Location::create(['name' => 'Pampore Zone', 'type' => 'zone', 'parent_id' => $pulwama->id, 'code' => 'PZ']);
        $kakapora = Location::create(['name' => 'Kakapora Zone', 'type' => 'zone', 'parent_id' => $pulwama->id, 'code' => 'KZ']);

        // === Tral Zone ===
        $panchayat = Location::create(['name' => 'Tral Central', 'type' => 'panchayat', 'parent_id' => $tral->id, 'code' => 'TC']);
        Location::create(['name' => 'Tral', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'TR']);
        Location::create(['name' => 'Nihama', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'NH']);

        $panchayat = Location::create(['name' => 'Tral North', 'type' => 'panchayat', 'parent_id' => $tral->id, 'code' => 'TN']);
        Location::create(['name' => 'Batagund', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'BG']);
        Location::create(['name' => 'Dadsara', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'DD']);

        // === Pampore Zone ===
        $panchayat = Location::create(['name' => 'Pampore East', 'type' => 'panchayat', 'parent_id' => $pampore->id, 'code' => 'PE']);
        Location::create(['name' => 'Pampore', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'PM']);
        Location::create(['name' => 'Drusoo', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'DR']);

        $panchayat = Location::create(['name' => 'Pampore West', 'type' => 'panchayat', 'parent_id' => $pampore->id, 'code' => 'PW']);
        Location::create(['name' => 'Kadlabal', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'KB']);

        // === Kakapora Zone ===
        $panchayat = Location::create(['name' => 'Kakapora', 'type' => 'panchayat', 'parent_id' => $kakapora->id, 'code' => 'KK']);
        Location::create(['name' => 'Kakapora', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'KP']);
        Location::create(['name' => 'Mitrigam', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'MG']);

        $panchayat = Location::create(['name' => 'Pulwama Central', 'type' => 'panchayat', 'parent_id' => $kakapora->id, 'code' => 'PC']);
        Location::create(['name' => 'Pulwama', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'PW']);
        Location::create(['name' => 'Lassipora', 'type' => 'village', 'parent_id' => $panchayat->id, 'code' => 'LS']);
    }
}
