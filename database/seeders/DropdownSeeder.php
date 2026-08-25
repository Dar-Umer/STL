<?php

namespace Database\Seeders;

use App\Models\SoilColor;
use App\Models\SoilType;
use App\Models\Crop;
use Illuminate\Database\Seeder;

class DropdownSeeder extends Seeder
{
    public function run(): void
    {
        // Soil Types
        $soilTypes = ['Alluvial', 'Black', 'Red', 'Laterite', 'Arid', 'Saline', 'Peaty', 'Forest'];
        foreach ($soilTypes as $name) {
            SoilType::create(['name' => $name]);
        }

        // Soil Colors
        $soilColors = ['Brown', 'Black', 'Red', 'Yellow', 'Grey', 'White', 'Mixed'];
        foreach ($soilColors as $name) {
            SoilColor::create(['name' => $name]);
        }

        // Crops
        $crops = ['Wheat', 'Rice', 'Maize', 'Sugarcane', 'Cotton', 'Pulses', 'Mustard', 'Vegetables', 'Fruits', 'Other', 'Apple'];
        foreach ($crops as $name) {
            Crop::firstOrCreate(['name' => $name]);
        }
    }
}
