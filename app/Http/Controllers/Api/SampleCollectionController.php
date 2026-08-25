<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Lab;
use App\Models\QrCode;
use App\Models\SampleRequest;
use App\Models\SampleStatusHistory;
use App\Models\SoilSample;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SampleCollectionController extends Controller
{
    public function availableQrCodes(Request $request)
    {
        $user = $request->user();
        $labId = $request->query('lab_id', $user->lab_id);

        if (!$labId) {
            return response()->json(['message' => 'No lab assigned to your account.'], 403);
        }

        $codes = QrCode::where('lab_id', $labId)
            ->where('is_used', false)
            ->orderBy('prefix')
            ->orderBy('sequence')
            ->get(['id', 'code', 'prefix', 'sequence']);

        return response()->json(['data' => $codes]);
    }

    public function validateQr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'lab_id' => 'required|exists:labs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $qr = QrCode::where('code', $request->code)->first();

        if (!$qr) {
            return response()->json(['valid' => false, 'message' => 'QR code not found in system.']);
        }
        if ($qr->is_used) {
            return response()->json(['valid' => false, 'message' => 'QR code already used.']);
        }
        if ($qr->lab_id != $request->lab_id) {
            return response()->json(['valid' => false, 'message' => 'QR code belongs to a different lab.']);
        }

        return response()->json([
            'valid' => true,
            'message' => 'QR code is valid.',
            'qr_code' => ['id' => $qr->id, 'code' => $qr->code, 'prefix' => $qr->prefix, 'sequence' => $qr->sequence],
        ]);
    }

    public function collect(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'request_id' => 'nullable|exists:sample_requests,request_id',
            'farmer_id' => 'required|exists:farmers,id',
            'plot_id' => 'required|exists:plots,id',
            'qr_code' => 'required|string',
            'lab_id' => 'required|exists:labs,id',
            'collected_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (!empty($data['request_id'])) {
            $sampleRequest = SampleRequest::where('request_id', $data['request_id'])->first();
            if (!$sampleRequest || !in_array($sampleRequest->status, ['pending', 'assigned'])) {
                return response()->json(['message' => 'Request is not available for collection.'], 422);
            }
        }

        $qr = QrCode::where('code', $data['qr_code'])->first();
        if (!$qr || $qr->is_used) {
            return response()->json(['message' => 'Invalid or already used QR code.'], 422);
        }
        if ($qr->lab_id != $data['lab_id']) {
            return response()->json(['message' => 'QR code belongs to a different lab.'], 422);
        }

        $farmer = Farmer::find($data['farmer_id']);
        if (!$farmer || !$farmer->zone_id) {
            return response()->json(['message' => 'Farmer has no zone assigned.'], 422);
        }

        $activeTarget = Target::active()->forZone($farmer->zone_id)->first();
        if (!$activeTarget) {
            return response()->json(['message' => 'No active target set for this zone.'], 422);
        }
        if ($activeTarget->isExceeded()) {
            return response()->json(['message' => 'Collection limit reached for this zone.'], 422);
        }

        try {
            $sample = DB::transaction(function () use ($data, $farmer, $qr, $user) {
                $lab = Lab::where('id', $data['lab_id'])->lockForUpdate()->firstOrFail();
                $zoneCode = $farmer->zone?->code ?? 'XX';
                $count = SoilSample::where('lab_id', $data['lab_id'])
                    ->whereHas('farmer', fn($q) => $q->where('zone_id', $farmer->zone_id))
                    ->count() + 1;
                $code = $lab->code . '-' . $zoneCode . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

                $sample = SoilSample::create([
                    'farmer_id' => $data['farmer_id'],
                    'plot_id' => $data['plot_id'],
                    'sample_code' => $code,
                    'source' => 'field',
                    'collected_by' => $user->id,
                    'collected_at' => $data['collected_at'],
                    'status' => 'collected',
                    'lab_id' => $data['lab_id'],
                    'qr_code_path' => $data['qr_code'],
                ]);

                SampleStatusHistory::create([
                    'soil_sample_id' => $sample->id,
                    'from_status' => null,
                    'to_status' => 'collected',
                    'changed_by' => $user->id,
                    'notes' => 'Collected via Flutter app',
                ]);

                $qr->update([
                    'is_used' => true,
                    'used_by' => $user->id,
                    'used_at' => now(),
                    'soil_sample_id' => $sample->id,
                ]);

                return $sample;
            });

            if (isset($sampleRequest)) {
                $sampleRequest->update([
                    'soil_sample_id' => $sample->id,
                    'status' => 'collected',
                    'field_official_id' => $sampleRequest->field_official_id ?? $user->id,
                ]);
            }

            return response()->json([
                'message' => 'Sample collected successfully.',
                'data' => [
                    'id' => $sample->id,
                    'sample_code' => $sample->sample_code,
                    'status' => 'collected',
                    'lab_id' => $sample->lab_id,
                    'collected_at' => $sample->collected_at->toDateTimeString(),
                ],
                'request_id' => isset($sampleRequest) ? $sampleRequest->request_id : null,
            ], 201);

        } catch (\Exception $e) {
            logger()->error('Sample collection failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Sample collection failed due to a server error.'], 500);
        }
    }
}
