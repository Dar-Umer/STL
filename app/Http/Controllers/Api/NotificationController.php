<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Location;
use App\Models\Notification;
use App\Models\DeviceToken;
use App\Models\SampleRequest;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function pendingRequests(Request $request)
    {
        $user = $request->user();

        if (!$user->zone_id) {
            return response()->json(['message' => 'No zone assigned to your account.'], 403);
        }

        $requests = SampleRequest::with('zone')
            ->where('zone_id', $user->zone_id)
            ->whereIn('status', ['pending', 'assigned'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'zone_id' => $user->zone_id,
            'data' => $requests->map(function ($r) {
                return [
                    'id' => $r->id,
                    'request_id' => $r->request_id,
                    'farmer_name' => $r->farmer_name,
                    'farmer_phone' => $r->farmer_phone,
                    'farmer_address' => $r->farmer_address,
                    'zone' => $r->zone->name ?? '',
                    'status' => $r->status,
                    'created_at' => $r->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function requestDetail($id)
    {
        $request = SampleRequest::with('zone')->findOrFail($id);

        return response()->json(['data' => [
            'id' => $request->id,
            'request_id' => $request->request_id,
            'farmer_name' => $request->farmer_name,
            'farmer_phone' => $request->farmer_phone,
            'farmer_address' => $request->farmer_address,
            'zone' => $request->zone->name ?? '',
            'status' => $request->status,
            'created_at' => $request->created_at->toDateTimeString(),
        ]]);
    }

    public function unreadNotifications(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) use ($user) {
                  $sub->where('zone_id', $user->zone_id)->whereNull('user_id');
              });
        })
        ->where('is_read', false)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'data' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'body' => $n->body,
                    'data' => $n->data,
                    'created_at' => $n->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function acceptRequest($id)
    {
        $request = SampleRequest::findOrFail($id);

        if (!in_array($request->status, ['pending', 'assigned'])) {
            return response()->json(['message' => 'Request cannot be accepted in its current state.'], 422);
        }

        $request->update([
            'field_official_id' => request()->user()->id,
            'status' => 'assigned',
        ]);

        return response()->json([
            'message' => 'Request accepted successfully.',
            'data' => [
                'id' => $request->id,
                'request_id' => $request->request_id,
                'status' => $request->status,
            ],
        ]);
    }

    public function prepareFarmer($id)
    {
        $sampleRequest = SampleRequest::with('zone')->findOrFail($id);
        $user = request()->user();

        $existing = Farmer::where('phone', $sampleRequest->farmer_phone)->first();
        if ($existing) {
            $existing->load(['state', 'district', 'zone', 'panchayat', 'village', 'lab']);
            return response()->json(['data' => [
                'id' => $existing->id,
                'name' => $existing->name,
                'phone' => $existing->phone,
                'parentage' => $existing->parentage,
                'aadhar_no' => $existing->aadhar_no,
                'state' => $existing->state ? ['id' => $existing->state->id, 'name' => $existing->state->name] : null,
                'district' => $existing->district ? ['id' => $existing->district->id, 'name' => $existing->district->name] : null,
                'zone' => $existing->zone ? ['id' => $existing->zone->id, 'name' => $existing->zone->name] : null,
                'panchayat' => $existing->panchayat ? ['id' => $existing->panchayat->id, 'name' => $existing->panchayat->name] : null,
                'village' => $existing->village ? ['id' => $existing->village->id, 'name' => $existing->village->name] : null,
                'plot_count' => $existing->plots()->count(),  // intentional: single-farmer lookup
                'created_at' => $existing->created_at->toDateTimeString(),
            ]]);
        }

        $zoneId = $sampleRequest->zone_id;
        $districtId = null;
        $stateId = null;
        if ($zoneId) {
            $zone = Location::find($zoneId);
            if ($zone && $zone->parent_id) {
                $district = Location::find($zone->parent_id);
                $districtId = $district?->id;
                $stateId = ($district && $district->parent_id) ? $district->parent_id : null;
            }
        }

        $labId = $user->lab_id;
        if (!$labId) {
            return response()->json(['message' => 'No lab assigned.'], 403);
        }

        $farmer = Farmer::create([
            'name' => $sampleRequest->farmer_name,
            'phone' => $sampleRequest->farmer_phone,
            'state_id' => $stateId,
            'district_id' => $districtId,
            'zone_id' => $zoneId,
            'lab_id' => $labId,
            'created_by' => $user->id,
        ]);
        $farmer->load(['state', 'district', 'zone', 'panchayat', 'village', 'lab']);

        return response()->json(['data' => [
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
        ]], 201);
    }

    public function listAll(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) use ($user) {
                  $sub->where('zone_id', $user->zone_id)->whereNull('user_id');
              });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return response()->json($notifications->through(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'data' => $n->data,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->toDateTimeString(),
            ];
        }));
    }

    public function registerDevice(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'token' => 'required|string|max:500',
            'platform' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        DeviceToken::updateOrCreate(
            ['user_id' => $request->user()->id, 'token' => $request->token],
            ['platform' => $request->platform]
        );

        return response()->json(['message' => 'Device registered for push notifications.']);
    }

    public function markRead(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'ids' => 'nullable|array',
            'ids.*' => 'integer|exists:notifications,id',
            'all' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $query = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) use ($user) {
                  $sub->where('zone_id', $user->zone_id)->whereNull('user_id');
              });
        });

        if ($request->boolean('all')) {
            $query->update(['is_read' => true]);
        } elseif ($request->filled('ids')) {
            Notification::whereIn('id', $request->ids)->update(['is_read' => true]);
        }

        return response()->json(['message' => 'Notifications marked as read.']);
    }
}
