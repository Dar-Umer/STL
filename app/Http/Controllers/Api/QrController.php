<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode as SimpleQr;

class QrController extends Controller
{
    /**
     * Get summary & recent QR code generation batches for the user's zone.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Field official / zone scoping
        $zoneId = ($user->hasRole('Admin') || $user->hasRole('Super Admin'))
            ? ($request->query('zone_id') ?: $user->zone_id)
            : $user->zone_id;

        if (!$zoneId) {
            return response()->json([
                'status' => 'error',
                'message' => 'No zone assigned to your account. Only zone officials can access zone QR codes.',
            ], 422);
        }

        $zone = Location::findOrFail($zoneId);
        $prefix = strtoupper($zone->code ?: (string) $zone->id);

        $baseQuery = QrCode::where(function ($q) use ($zoneId, $prefix) {
            $q->where('zone_id', $zoneId)->orWhere('prefix', $prefix);
        });

        $totalGenerated = (clone $baseQuery)->count();
        $availableUnused = (clone $baseQuery)->where('is_used', false)->count();
        $usedCount = (clone $baseQuery)->where('is_used', true)->count();

        // Recent generation batches grouped by created_by and minute or latest records
        $recentCodes = (clone $baseQuery)
            ->with(['creator'])
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();

        // Group into generation runs (consecutive sequences or chunks)
        $batches = [];
        if ($recentCodes->isNotEmpty()) {
            // Group by date/time (within 5 minutes) and creator
            $grouped = $recentCodes->groupBy(function ($item) {
                return $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'legacy';
            });

            foreach ($grouped as $timeKey => $items) {
                $minSeq = $items->min('sequence');
                $maxSeq = $items->max('sequence');
                $itemsPrefix = $items->first()->prefix;
                $creator = $items->first()->creator?->name ?? 'Field Official';

                $batches[] = [
                    'prefix' => $itemsPrefix,
                    'start_seq' => $minSeq,
                    'end_seq' => $maxSeq,
                    'count' => $items->count(),
                    'range_label' => "{$itemsPrefix}{$minSeq} – {$itemsPrefix}{$maxSeq}",
                    'created_at' => $items->first()->created_at ? $items->first()->created_at->format('d M Y, h:i A') : '—',
                    'created_by' => $creator,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'zone' => [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'code' => $prefix,
                ],
                'counts' => [
                    'total' => $totalGenerated,
                    'available' => $availableUnused,
                    'used' => $usedCount,
                ],
                'next_sequence' => (QrCode::where('prefix', $prefix)->max('sequence') ?? 0) + 1,
                'batches' => array_values($batches),
            ],
        ]);
    }

    /**
     * Generate QR codes for the user's assigned zone only.
     */
    public function generate(Request $request): JsonResponse
    {
        $user = $request->user();

        // Strict security rule: Field officials can ONLY generate QR codes for their own zone
        $zoneId = ($user->hasRole('Admin') || $user->hasRole('Super Admin'))
            ? ($request->input('zone_id') ?: $user->zone_id)
            : $user->zone_id;

        if (!$zoneId) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot generate QR codes because no zone is assigned to your account. Contact your administrator.',
            ], 422);
        }

        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:200',
        ]);

        $count = (int) $validated['count'];
        $zone = Location::findOrFail($zoneId);

        if (empty($zone->code) || $zone->code === 'N/A') {
            return response()->json([
                'status' => 'error',
                'message' => "Zone {$zone->name} does not have a unique zone code configured in the system.",
            ], 422);
        }

        $prefix = strtoupper(trim($zone->code));
        $labId = $user->lab_id ?? $zone->lab_id ?? 1;

        // Atomic lock to guarantee sequential numbering without gaps or collisions
        $createdCodes = DB::transaction(function () use ($prefix, $count, $labId, $zone, $user) {
            $maxSeq = QrCode::where('prefix', $prefix)->lockForUpdate()->max('sequence') ?? 0;
            $startFrom = $maxSeq + 1;

            $now = now();
            $records = [];
            $generatedCodes = [];

            for ($i = 0; $i < $count; $i++) {
                $seq = $startFrom + $i;
                $codeStr = $prefix . $seq;
                $records[] = [
                    'code' => $codeStr,
                    'prefix' => $prefix,
                    'sequence' => $seq,
                    'lab_id' => $labId,
                    'zone_id' => $zone->id,
                    'created_by' => $user->id,
                    'is_used' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $generatedCodes[] = $codeStr;
            }

            QrCode::insert($records);

            return [
                'prefix' => $prefix,
                'start_seq' => $startFrom,
                'end_seq' => $startFrom + $count - 1,
                'count' => $count,
                'codes' => $generatedCodes,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => "Successfully generated {$count} QR code(s) for {$zone->name} ({$createdCodes['prefix']}{$createdCodes['start_seq']} to {$createdCodes['prefix']}{$createdCodes['end_seq']}).",
            'data' => [
                'zone_name' => $zone->name,
                'zone_code' => $prefix,
                'start_seq' => $createdCodes['start_seq'],
                'end_seq' => $createdCodes['end_seq'],
                'count' => $count,
                'range_label' => "{$prefix}{$createdCodes['start_seq']} – {$prefix}{$createdCodes['end_seq']}",
                'codes' => $createdCodes['codes'],
            ],
        ]);
    }

    /**
     * Download printable A4 PDF sticker sheet for generated QR codes.
     */
    public function downloadPdf(Request $request)
    {
        $user = $request->user();

        $zoneId = ($user->hasRole('Admin') || $user->hasRole('Super Admin'))
            ? ($request->query('zone_id') ?: $user->zone_id)
            : $user->zone_id;

        if (!$zoneId) {
            abort(422, 'No zone assigned to your account.');
        }

        $zone = Location::findOrFail($zoneId);
        $prefix = strtoupper($zone->code ?: (string) $zone->id);

        $query = QrCode::where(function ($q) use ($zoneId, $prefix) {
            $q->where('zone_id', $zoneId)->orWhere('prefix', $prefix);
        });

        // Filter by sequence range or type
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('sequence', [(int) $request->start, (int) $request->end]);
        } elseif ($request->query('filter') === 'unused') {
            $query->where('is_used', false);
        }

        $codes = $query->orderBy('sequence', 'asc')->get();

        if ($codes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No QR codes found matching the requested range for your zone.',
            ], 404);
        }

        $labName = $codes->first()->lab?->name
            ?? $user->lab?->name
            ?? 'District Soil Testing Laboratory';

        $minSeq = $codes->min('sequence');
        $maxSeq = $codes->max('sequence');
        $batchLabel = "Batch {$prefix}{$minSeq} to {$prefix}{$maxSeq}";

        // Render base64 SVG data URIs for DomPDF
        $items = [];
        foreach ($codes as $c) {
            $svg = (string) SimpleQr::size(130)->errorCorrection('M')->generate($c->code);
            $items[] = [
                'code' => $c->code,
                'b64' => 'data:image/svg+xml;base64,' . base64_encode($svg),
            ];
        }

        // Chunk into pages of 20 stickers each (5 rows of 4)
        $pages = array_chunk($items, 20);

        $pdf = Pdf::loadView('pdf.qr-codes-stickers', [
            'zoneName' => $zone->name,
            'labName' => $labName,
            'batchLabel' => $batchLabel,
            'totalCount' => $codes->count(),
            'pages' => $pages,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = "QR_Stickers_{$prefix}_{$minSeq}_to_{$maxSeq}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Validate a QR code and enforce strict zone isolation.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        $qr = QrCode::with('zone')->where('code', $request->code)->first();

        if (!$qr) {
            return response()->json(['valid' => false, 'error' => 'QR code not found in system.']);
        }

        if ($qr->is_used) {
            return response()->json(['valid' => false, 'error' => 'QR code already used.']);
        }

        $user = $request->user();

        // Lab validation
        if ($user->lab_id && $qr->lab_id && $qr->lab_id !== $user->lab_id) {
            return response()->json(['valid' => false, 'error' => 'QR code belongs to a different lab.']);
        }

        // Zone validation: user from one zone CANNOT use QR codes of another zone
        if ($user->zone_id) {
            $isSameZone = ($qr->zone_id && $qr->zone_id == $user->zone_id) || ($qr->prefix === $user->zone?->code);
            if (!$isSameZone) {
                $otherZoneName = $qr->zone?->name ?? ('Zone ' . $qr->prefix);
                return response()->json([
                    'valid' => false,
                    'error' => "This QR code ({$qr->code}) belongs to {$otherZoneName} and is not valid for {$user->zone?->name}.",
                ]);
            }
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'id' => $qr->id,
                'code' => $qr->code,
                'prefix' => $qr->prefix,
                'sequence' => $qr->sequence,
                'zone_name' => $qr->zone?->name ?? ('Zone ' . $qr->prefix),
            ],
        ]);
    }
}
