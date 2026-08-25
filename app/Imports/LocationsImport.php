<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LocationsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'state' => 'required|string',
            'district' => 'required|string',
            'zone' => 'nullable|string',
            'panchayat' => 'nullable|string',
            'village' => 'nullable|string',
        ];
    }

    public function model(array $row)
    {
        $stateName = trim($row['state']);
        $districtName = trim($row['district']);
        $zoneName = isset($row['zone']) ? trim($row['zone']) : '';
        $panchayatName = isset($row['panchayat']) ? trim($row['panchayat']) : '';
        $villageName = isset($row['village']) ? trim($row['village']) : '';

        if (empty($stateName) || empty($districtName)) {
            return null;
        }

        $state = Location::firstOrCreate(
            ['name' => $stateName, 'type' => 'state'],
            ['code' => strtoupper(substr($stateName, 0, 3))]
        );

        $district = Location::firstOrCreate(
            ['name' => $districtName, 'type' => 'district', 'parent_id' => $state->id],
            ['code' => strtoupper(substr($districtName, 0, 3))]
        );

        if (!empty($zoneName)) {
            $zone = Location::firstOrCreate(
                ['name' => $zoneName, 'type' => 'zone', 'parent_id' => $district->id],
                ['code' => strtoupper(substr($zoneName, 0, 3))]
            );
        }

        if (!empty($panchayatName) && !empty($zoneName)) {
            $panchayat = Location::firstOrCreate(
                ['name' => $panchayatName, 'type' => 'panchayat', 'parent_id' => $zone->id],
                ['code' => strtoupper(substr($panchayatName, 0, 3))]
            );
        }

        if (!empty($villageName)) {
            $parentId = !empty($panchayatName) && isset($panchayat)
                ? $panchayat->id
                : (!empty($zoneName) && isset($zone) ? $zone->id : $district->id);

            $villageType = !empty($panchayatName) ? 'village' : 'village';

            Location::firstOrCreate(
                ['name' => $villageName, 'type' => 'village', 'parent_id' => $parentId],
                ['code' => strtoupper(substr($villageName, 0, 3))]
            );
        }

        return null;
    }
}
