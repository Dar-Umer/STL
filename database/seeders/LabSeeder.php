<?php

namespace Database\Seeders;

use App\Models\Lab;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    public function run(): void
    {
        Lab::create([
            'name' => 'Central Soil Testing Lab',
            'code' => 'CSTL',
            'address' => 'Main Campus, District Headquarters',
            'phone' => '0194-1234567',
            'email' => 'cstl@soillab.com',
            'contact_person' => 'Lab Director',
            'is_active' => true,
        ]);
    }
}
