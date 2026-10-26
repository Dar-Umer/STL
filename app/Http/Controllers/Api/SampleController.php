<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSampleRequest;
use App\Http\Resources\Api\SampleResource;
use App\Models\Farmer;
use App\Models\Lab;
use App\Models\QrCode;
use App\Models\SampleStatusHistory;
use App\Models\SoilSample;
use App\Models\Target;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SampleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $samples = SoilSample::with(['farmer', 'plot.soilType', 'plot.crop', 'collector', 'lab', 'dispatchBatch'])
            ->where('collected_by', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $samples->map(fn($s) => [
                'id' => $s->id,
                'sample_code' => $s->sample_code,
                'lab_number' => $s->lab_number,
                'batch_no' => $s->dispatchBatch?->batch_no,
                'dispatch_batch_id' => $s->dispatch_batch_id,
                'status' => $s->status->value,
                'status_label' => $s->status->label(),
                'qr_code' => $s->qr_code_path,
                'collected_at' => $s->collected_at?->toDateTimeString(),
                'received_at' => $s->received_at?->toDateTimeString(),
                'analyzed_at' => $s->analyzed_at?->toDateTimeString(),
                'farmer' => $s->farmer ? [
                    'id' => $s->farmer->id,
                    'name' => $s->farmer->name,
                    'phone' => $s->farmer->phone,
                ] : null,
                'plot' => $s->plot ? [
                    'id' => $s->plot->id,
                    'farmer_id' => $s->plot->farmer_id,
                    'plot_name' => $s->plot->plot_name,
                    'khasra_no' => $s->plot->khasra_no,
                    'latitude' => $s->plot->latitude !== null ? (float) $s->plot->latitude : null,
                    'longitude' => $s->plot->longitude !== null ? (float) $s->plot->longitude : null,
                    'area_kanals' => $s->plot->area_kanals !== null ? (float) $s->plot->area_kanals : null,
                    'age_of_plants' => $s->plot->age_of_plants !== null ? (float) $s->plot->age_of_plants : null,
                    'soil_type' => $s->plot->soilType?->name,
                    'crop' => $s->plot->crop?->name,
                ] : null,
                'collector' => $s->collector ? [
                    'id' => $s->collector->id,
                    'name' => $s->collector->name,
                ] : null,
                'lab' => $s->lab ? [
                    'id' => $s->lab->id,
                    'name' => $s->lab->name,
                ] : null,
                'created_at' => $s->created_at?->toDateTimeString(),
            ]),
        ]);
    }

    public function show(Request $request, SoilSample $sample): JsonResponse
    {
        if ($request->user()->lab_id && $sample->lab_id !== $request->user()->lab_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => new SampleResource($sample->load([
                'farmer.state', 'farmer.district', 'farmer.zone',
                'plot.soilType', 'plot.crop', 'plot.soilColor',
                'collector', 'lab', 'statusHistory', 'dispatchBatch',
            ])),
        ]);
    }

    public function store(StoreSampleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $labId = $request->user()->lab_id;

        $qr = QrCode::where('code', $data['qr_code'])->first();
        if (!$qr) {
            return response()->json(['message' => 'QR code not found in system.'], 422);
        }
        if ($qr->is_used) {
            return response()->json(['message' => 'QR code already used.'], 422);
        }
        if ($qr->lab_id !== $labId) {
            return response()->json(['message' => 'QR code belongs to a different lab.'], 422);
        }

        $farmer = Farmer::findOrFail($data['farmer_id']);
        if ($farmer->lab_id !== $labId) {
            return response()->json(['message' => 'Farmer belongs to a different lab.'], 403);
        }

        if (!$farmer->zone_id) {
            return response()->json(['message' => 'Farmer has no zone assigned. Cannot determine target.'], 422);
        }

        $activeTarget = Target::active()->forZone($farmer->zone_id)->first();
        if (!$activeTarget) {
            return response()->json(['message' => 'No active target set for this zone period. Contact administrator.'], 422);
        }
        if ($activeTarget->isExceeded()) {
            return response()->json([
                'message' => 'Collection limit reached for this zone. Target of ' . $activeTarget->target_count
                    . ' samples has been met (' . $activeTarget->collectedCount() . ' collected).',
            ], 422);
        }

        $sampleCode = DB::transaction(function () use ($data, $farmer, $qr, $labId, $request) {
            $lab = Lab::where('id', $labId)->lockForUpdate()->firstOrFail();
            $zoneCode = $farmer->zone?->code ?? 'XX';

            $count = SoilSample::where('lab_id', $labId)
                ->whereHas('farmer', fn($q) => $q->where('zone_id', $farmer->zone_id))
                ->count() + 1;

            $code = $lab->code . '-' . $zoneCode . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $sample = SoilSample::create([
                'farmer_id' => $data['farmer_id'],
                'plot_id' => $data['plot_id'],
                'sample_code' => $code,
                'source' => 'field',
                'collected_by' => $request->user()->id,
                'collected_at' => $data['collected_at'],
                'status' => 'collected',
                'lab_id' => $labId,
                'qr_code_path' => $data['qr_code'],
            ]);

            $qr->update([
                'is_used' => true,
                'used_by' => $request->user()->id,
                'used_at' => now(),
                'soil_sample_id' => $sample->id,
            ]);

            SampleStatusHistory::create([
                'soil_sample_id' => $sample->id,
                'from_status' => null,
                'to_status' => 'collected',
                'changed_by' => $request->user()->id,
                'notes' => 'Sample collected via mobile app',
            ]);

            return $sample;
        });

        return response()->json([
            'data' => new SampleResource($sampleCode->load([
                'farmer', 'plot.soilType', 'plot.crop', 'collector', 'lab',
            ])),
            'message' => "Sample {$sampleCode->sample_code} collected successfully.",
        ], 201);
    }
}
