<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function states()
    {
        $states = Location::where('type', 'state')->orderBy('name')->get();
        return response()->json([
            'data' => $states->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'type' => $s->type,
                'code' => $s->code,
            ]),
        ]);
    }

    public function ancestors($id)
    {
        $location = Location::findOrFail($id);
        $ids = [$location->id];
        $parentId = $location->parent_id;
        while ($parentId) {
            $ids[] = $parentId;
            $parent = Location::find($parentId);
            $parentId = $parent?->parent_id;
        }
        $all = Location::whereIn('id', $ids)->get()->keyBy('id');
        $chain = [];
        foreach ($ids as $locId) {
            $loc = $all->get($locId);
            $chain[] = $loc ? ['id' => $loc->id, 'name' => $loc->name, 'type' => $loc->type] : null;
        }
        $chain = array_values(array_filter($chain));
        return response()->json(['data' => $chain]);
    }

    public function children($parentId)
    {
        $parent = Location::findOrFail($parentId);

        $types = [
            'state' => 'district',
            'district' => 'zone',
            'zone' => 'panchayat',
            'panchayat' => 'village',
        ];

        $childType = $types[$parent->type] ?? null;
        if (!$childType) {
            return response()->json(['message' => 'No children available for this location type.'], 422);
        }

        $children = Location::where('type', $childType)
            ->where('parent_id', $parentId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'type' => $childType,
            'data' => $children->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'type' => $c->type,
                'code' => $c->code,
                'parent_id' => $c->parent_id,
            ]),
        ]);
    }
}
