<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Plot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $zoneId = $request->query('zone_id');

        // Automatically scope to the authenticated user's zone if assigned
        if (!$zoneId && $user && $user->zone_id) {
            $zoneId = $user->zone_id;
        }

        $query = Farmer::with(['state', 'district', 'zone', 'panchayat', 'village', 'lab'])
            ->withCount('plots');

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('parentage', 'like', "%{$search}%")
                  ->orWhere('aadhar_no', 'like', "%{$search}%")
                  ->orWhereHas('village', fn($vq) => $vq->where('name', 'like', "%{$search}%"));
            });
        }

        $farmers = $query->orderBy('name', 'asc')
            ->limit(150)
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'phone' => $f->phone,
                'parentage' => $f->parentage,
                'aadhar_no' => $f->aadhar_no,
                'state' => $f->state ? ['id' => $f->state->id, 'name' => $f->state->name] : null,
                'district' => $f->district ? ['id' => $f->district->id, 'name' => $f->district->name] : null,
                'zone' => $f->zone ? ['id' => $f->zone->id, 'name' => $f->zone->name] : null,
                'panchayat' => $f->panchayat ? ['id' => $f->panchayat->id, 'name' => $f->panchayat->name] : null,
                'village' => $f->village ? ['id' => $f->village->id, 'name' => $f->village->name] : null,
                'plot_count' => (int) $f->plots_count,
                'created_at' => $f->created_at->toDateTimeString(),
            ]);

        return response()->json([
            'data' => $farmers,
            'zone_id' => $zoneId ? (int)$zoneId : null,
            'total' => $farmers->count(),
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q');

        if (!$q || strlen($q) < 2) {
            return response()->json(['data' => []]);
        }

        $user = $request->user();
        $zoneId = $request->query('zone_id', $user?->zone_id);

        $query = Farmer::with(['state', 'district', 'zone', 'panchayat', 'village', 'lab'])
            ->withCount('plots');

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        $farmers = $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('aadhar_no', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'phone' => $f->phone,
                'parentage' => $f->parentage,
                'aadhar_no' => $f->aadhar_no,
                'state' => $f->state ? ['id' => $f->state->id, 'name' => $f->state->name] : null,
                'district' => $f->district ? ['id' => $f->district->id, 'name' => $f->district->name] : null,
                'zone' => $f->zone ? ['id' => $f->zone->id, 'name' => $f->zone->name] : null,
                'panchayat' => $f->panchayat ? ['id' => $f->panchayat->id, 'name' => $f->panchayat->name] : null,
                'village' => $f->village ? ['id' => $f->village->id, 'name' => $f->village->name] : null,
                'plot_count' => (int) $f->plots_count,
                'created_at' => $f->created_at->toDateTimeString(),
            ]);

        return response()->json(['data' => $farmers]);
    }

    public function show($id)
    {
        $farmer = Farmer::with(['state', 'district', 'zone', 'panchayat', 'village', 'lab'])->withCount('plots')->findOrFail($id);

        $f = $farmer;
        return response()->json([
            'data' => [
                'id' => $f->id,
                'name' => $f->name,
                'phone' => $f->phone,
                'parentage' => $f->parentage,
                'aadhar_no' => $f->aadhar_no,
                'state' => $f->state ? ['id' => $f->state->id, 'name' => $f->state->name] : null,
                'district' => $f->district ? ['id' => $f->district->id, 'name' => $f->district->name] : null,
                'zone' => $f->zone ? ['id' => $f->zone->id, 'name' => $f->zone->name] : null,
                'panchayat' => $f->panchayat ? ['id' => $f->panchayat->id, 'name' => $f->panchayat->name] : null,
                'village' => $f->village ? ['id' => $f->village->id, 'name' => $f->village->name] : null,
                'plot_count' => (int) $f->plots_count,
                'created_at' => $f->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function plots($id)
    {
        $farmer = Farmer::findOrFail($id);
        $plots = Plot::with(['soilType', 'crop', 'soilColor'])
            ->where('farmer_id', $farmer->id)
            ->orderBy('plot_name')
            ->get();

        return response()->json([
            'data' => $plots->map(fn($p) => [
                'id' => $p->id,
                'farmer_id' => $p->farmer_id,
                'plot_name' => $p->plot_name,
                'khasra_no' => $p->khasra_no,
                'area_kanals' => $p->area_kanals !== null ? (float) $p->area_kanals : null,
                'soil_type' => $p->soilType?->name,
                'crop' => $p->crop?->name,
                'age_of_plants' => $p->age_of_plants !== null ? (float) $p->age_of_plants : null,
                'latitude' => $p->latitude !== null ? (float) $p->latitude : null,
                'longitude' => $p->longitude !== null ? (float) $p->longitude : null,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'digits:10', 'regex:/^[6789]/', 'unique:farmers,phone'],
            'parentage' => 'required|string|max:255',
            'aadhar_no' => ['required', 'string', 'digits:12', 'regex:/^[0-9]{12}$/', 'unique:farmers,aadhar_no'],
            'state_id' => 'required|exists:locations,id',
            'district_id' => 'required|exists:locations,id',
            'zone_id' => 'nullable|exists:locations,id',
            'panchayat_id' => 'nullable|exists:locations,id',
            'village_id' => 'nullable|exists:locations,id',
            'lab_id' => 'required|exists:labs,id',
        ], [
            'name.required' => 'Farmer full name is required.',
            'phone.required' => 'Mobile number is required.',
            'phone.digits' => 'Mobile number must be exactly 10 digits.',
            'phone.regex' => 'Mobile number must start with 9, 8, 7 or 6.',
            'phone.unique' => 'This mobile number is already registered to another farmer.',
            'parentage.required' => 'Parentage (Father/Husband name) is required.',
            'aadhar_no.required' => 'Aadhaar number is required.',
            'aadhar_no.digits' => 'Aadhaar number must be exactly 12 digits.',
            'aadhar_no.regex' => 'Aadhaar number must contain only numeric digits.',
            'aadhar_no.unique' => 'This Aadhaar number is already registered to another farmer.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['created_by'] = $request->user()->id;

        $farmer = Farmer::create($data);
        $farmer->load(['state', 'district', 'zone', 'panchayat', 'village', 'lab']);

        return response()->json([
            'message' => 'Farmer registered successfully.',
            'data' => [
                'id' => $farmer->id,
                'name' => $farmer->name,
                'phone' => $farmer->phone,
                'parentage' => $farmer->parentage,
                'aadhar_no' => $farmer->aadhar_no,
                'state' => $farmer->state ? ['id' => $farmer->state->id, 'name' => $farmer->state->name] : null,
                'district' => $farmer->district ? ['id' => $farmer->district->id, 'name' => $farmer->district->name] : null,
                'zone' => $farmer->zone ? ['id' => $farmer->zone->id, 'name' => $farmer->zone->name] : null,
                'panchayat' => $farmer->panchayat ? ['id' => $farmer->panchayat->id, 'name' => $farmer->panchayat->name] : null,
                'village' => $farmer->village ? ['id' => $farmer->village->id, 'name' => $farmer->village->name] : null,
                'plot_count' => 0,
                'created_at' => $farmer->created_at->toDateTimeString(),
            ],
        ], 201);
    }
}
