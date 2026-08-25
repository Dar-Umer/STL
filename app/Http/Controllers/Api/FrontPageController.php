<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FrontPageController extends Controller
{
    public function index(): JsonResponse
    {
        $appAnnouncements = json_decode(setting('front.app_announcements', '[]'), true) ?: [];

        return response()->json([
            'data' => [
                'maintenance_mode' => (bool) setting('maintenance_mode', false),
                'required_version' => setting('app_version', '1.0.0'),
                'force_update' => (bool) setting('force_update', false),
                'store_url' => setting('app_update_url', ''),
                'app_announcements' => $appAnnouncements,
                'collection_guide' => setting('front.collection_guide', ''),
                'contacts' => [
                    'cho_phone' => setting('front.cho_phone', '+91-XXXX-XXXXXX'),
                    'helpline' => setting('front.helpline', '1800-180-1551'),
                    'lab_phone' => setting('front.lab_phone', '+91-XXXX-XXXXXX'),
                    'cho_email' => setting('front.cho_email', 'cho.pulwama@jk.gov.in'),
                ],
                'working_hours' => [
                    'enabled' => (bool) setting('app.working_hours_enabled', false),
                    'start' => setting('app.work_start_time', '09:00'),
                    'end' => setting('app.work_end_time', '17:00'),
                    'days' => json_decode(setting('app.working_days', '[1,2,3,4,5]'), true) ?: [1, 2, 3, 4, 5],
                ],
            ],
        ]);
    }
}
