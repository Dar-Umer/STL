<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Target;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $zoneId = $user->zone_id;

        if (!$zoneId) {
            return response()->json(['message' => 'No zone assigned to your account.', 'targets' => []]);
        }

        $targets = Target::with('zone', 'creator')
            ->forZone($zoneId)
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function (Target $target) {
                return [
                    'id' => $target->id,
                    'zone_id' => $target->zone_id,
                    'zone_name' => $target->zone->name ?? null,
                    'target_count' => $target->target_count,
                    'start_date' => $target->start_date->toDateString(),
                    'end_date' => $target->end_date->toDateString(),
                    'collected_count' => $target->collectedCount(),
                    'achieved_count' => $target->achievedCount(),
                    'progress_percent' => $target->progressPercent(),
                    'remaining_count' => $target->remainingCount(),
                    'is_exceeded' => $target->isExceeded(),
                    'created_by' => $target->creator->name ?? null,
                    'created_at' => $target->created_at->toDateTimeString(),
                ];
            });

        return response()->json(['targets' => $targets]);
    }
}
