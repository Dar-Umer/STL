<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlotController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmers,id',
            'plot_name' => 'required|string|max:255',
            'khasra_no' => 'required|string|max:255',
            'area_kanals' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'soil_type_id' => 'required|exists:soil_types,id',
            'crop_id' => 'required|exists:crops,id',
            'age_of_plants' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['plot_name'] = ucwords(strtolower(trim($data['plot_name'])));

        // Inherit location from farmer
        $farmer = \App\Models\Farmer::find($data['farmer_id']);
        if ($farmer) {
            $data['state_id'] = $farmer->state_id;
            $data['district_id'] = $farmer->district_id;
            $data['zone_id'] = $farmer->zone_id;
            $data['panchayat_id'] = $farmer->panchayat_id;
            $data['village_id'] = $farmer->village_id;
        }

        $plot = Plot::create($data);
        $plot->load(['soilType', 'crop']);

        return response()->json([
            'message' => 'Plot created successfully.',
            'data' => [
                'id' => $plot->id,
                'farmer_id' => $plot->farmer_id,
                'plot_name' => $plot->plot_name,
                'khasra_no' => $plot->khasra_no,
                'area_kanals' => $plot->area_kanals !== null ? (float) $plot->area_kanals : null,
                'latitude' => $plot->latitude !== null ? (float) $plot->latitude : null,
                'longitude' => $plot->longitude !== null ? (float) $plot->longitude : null,
                'soil_type' => $plot->soilType?->name,
                'crop' => $plot->crop?->name,
                'age_of_plants' => $plot->age_of_plants !== null ? (float) $plot->age_of_plants : null,
            ],
        ], 201);
    }
}
