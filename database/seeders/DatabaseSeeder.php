<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LabSeeder::class,
            LocationSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            DropdownSeeder::class,
            ParameterSeeder::class,
            QrCodeSeeder::class,
            RecommendationRuleSeeder::class,
        ]);
    }
}
