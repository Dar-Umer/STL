<?php

namespace Database\Seeders;

use App\Models\SoilTestParameter;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    public function run(): void
    {
        $parameters = [
            ['name' => 'pH', 'unit' => '-', 'min_range' => 0, 'max_range' => 14, 'method' => 'pH Meter', 'rating_low_max' => 5.5, 'rating_medium_max' => 7.5, 'rating_high_min' => 7.6, 'order_column' => 1],
            ['name' => 'EC', 'unit' => 'dS/m', 'min_range' => 0, 'max_range' => 10, 'method' => 'Conductivity Meter', 'rating_low_max' => 0.5, 'rating_medium_max' => 1.5, 'rating_high_min' => 1.6, 'order_column' => 2],
            ['name' => 'OC', 'unit' => '%', 'min_range' => 0, 'max_range' => 5, 'method' => 'Walkley-Black', 'rating_low_max' => 0.4, 'rating_medium_max' => 0.75, 'rating_high_min' => 0.76, 'order_column' => 3],
            ['name' => 'N', 'unit' => 'kg/ha', 'min_range' => 0, 'max_range' => 500, 'method' => 'Alkaline Permanganate', 'rating_low_max' => 280, 'rating_medium_max' => 420, 'rating_high_min' => 421, 'order_column' => 4],
            ['name' => 'P', 'unit' => 'kg/ha', 'min_range' => 0, 'max_range' => 100, 'method' => 'Olsen\'s', 'rating_low_max' => 15, 'rating_medium_max' => 30, 'rating_high_min' => 31, 'order_column' => 5],
            ['name' => 'K', 'unit' => 'kg/ha', 'min_range' => 0, 'max_range' => 500, 'method' => 'Flame Photometer', 'rating_low_max' => 140, 'rating_medium_max' => 280, 'rating_high_min' => 281, 'order_column' => 6],
            ['name' => 'S', 'unit' => 'mg/kg', 'min_range' => 0, 'max_range' => 100, 'method' => 'Turbidimetric', 'rating_low_max' => 10, 'rating_medium_max' => 20, 'rating_high_min' => 21, 'order_column' => 7],
            ['name' => 'Zn', 'unit' => 'mg/kg', 'min_range' => 0, 'max_range' => 10, 'method' => 'DTPA Extraction', 'rating_low_max' => 0.6, 'rating_medium_max' => 1.2, 'rating_high_min' => 1.3, 'order_column' => 8],
            ['name' => 'Fe', 'unit' => 'mg/kg', 'min_range' => 0, 'max_range' => 50, 'method' => 'DTPA Extraction', 'rating_low_max' => 4.5, 'rating_medium_max' => 9.0, 'rating_high_min' => 9.1, 'order_column' => 9],
            ['name' => 'Cu', 'unit' => 'mg/kg', 'min_range' => 0, 'max_range' => 10, 'method' => 'DTPA Extraction', 'rating_low_max' => 0.4, 'rating_medium_max' => 0.8, 'rating_high_min' => 0.9, 'order_column' => 10],
            ['name' => 'Mn', 'unit' => 'mg/kg', 'min_range' => 0, 'max_range' => 50, 'method' => 'DTPA Extraction', 'rating_low_max' => 3.5, 'rating_medium_max' => 7.0, 'rating_high_min' => 7.1, 'order_column' => 11],
            ['name' => 'Bo', 'unit' => 'mg/kg', 'min_range' => 0, 'max_range' => 10, 'method' => 'Hot Water Extraction', 'rating_low_max' => 0.5, 'rating_medium_max' => 1.0, 'rating_high_min' => 1.1, 'order_column' => 12],
        ];

        foreach ($parameters as $param) {
            SoilTestParameter::create($param);
        }
    }
}
