<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoilType;
use App\Models\Crop;
use App\Models\SoilColor;

class ReferenceController extends Controller
{
    public function soilTypes()
    {
        return response()->json([
            'data' => SoilType::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function crops()
    {
        return response()->json([
            'data' => Crop::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function soilColors()
    {
        return response()->json([
            'data' => SoilColor::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
