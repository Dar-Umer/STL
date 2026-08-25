<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function validate(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        $qr = QrCode::where('code', $request->code)->first();

        if (!$qr) {
            return response()->json(['valid' => false, 'error' => 'QR code not found in system.']);
        }

        if ($qr->is_used) {
            return response()->json(['valid' => false, 'error' => 'QR code already used.']);
        }

        if ($qr->lab_id !== $request->user()->lab_id) {
            return response()->json(['valid' => false, 'error' => 'QR code belongs to a different lab.']);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'id' => $qr->id,
                'code' => $qr->code,
                'prefix' => $qr->prefix,
                'sequence' => $qr->sequence,
            ],
        ]);
    }
}
