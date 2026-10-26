<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DispatchBatch;
use App\Models\Lab;
use App\Models\Location;
use App\Models\SoilSample;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CoveringLetterController extends Controller
{
    /**
     * Get zone details, defaults, and collected samples for the covering letter.
     */
    public function getData(Request $request)
    {
        $user = $request->user();

        // 1. Resolve Zone & Location Hierarchy
        $zone = $user->zone_id ? Location::with('parent.parent')->find($user->zone_id) : null;
        $district = $zone?->parent ?? ($user->district_id ? Location::find($user->district_id) : null);
        $state = $district?->parent ?? Location::where('type', 'state')->first();

        // 2. Resolve Lab
        $lab = $user->lab_id ? Lab::find($user->lab_id) : Lab::where('is_active', true)->first();

        $zoneName = $zone ? $zone->name : 'General Field Zone';
        $districtName = $district ? $district->name : 'District Directorate';
        $labName = $lab ? $lab->name : 'District Soil Testing Laboratory';
        $officerName = $user->name;
        $officerDesignation = $user->getRoleNames()->first() ?? 'Field Official';

        // 3. Dynamic Tag Replacements for Admin Templates
        $tags = [
            '{ZONE}' => $zoneName,
            '{DISTRICT}' => $districtName,
            '{LAB}' => $labName,
            '{OFFICER}' => $officerName,
            '{DATE}' => date('d/m/Y'),
        ];

        // 4. Resolve System-Wide Admin Default Templates
        $adminHeaderTemplate = setting('covering_letter.header_template') ?: "OFFICE OF THE HORTICULTURE DEVELOPMENT OFFICER, {ZONE}";
        $adminAddressedToTemplate = setting('covering_letter.addressed_to_template') ?: "The Incharge / Testing Officer,\n{LAB},\n{DISTRICT}, Jammu & Kashmir.";
        $adminSubjectTemplate = setting('covering_letter.subject_template') ?: "Transmission of physical soil samples collected from {ZONE} for laboratory testing and issuance of Soil Health Cards.";
        $adminBodyTemplate = setting('covering_letter.body_template') ?: (
            "Sir / Madam,\n\n" .
            "Enclosed please find physical soil samples duly collected, processed, barcode-tagged, and packaged by the field extension team of {ZONE} under the Annual Soil Testing & Soil Health Management Programme.\n\n" .
            "The samples have been collected as per standard composite sampling protocols from various orchardists / farmers across the Panchayats and Villages of {ZONE}. A comprehensive descriptive schedule of the dispatched samples, farmer profiles, parentage, residence, and QR identification codes is cataloged in the schedule below.\n\n" .
            "You are kindly requested to accept the physical samples, perform the required chemical / nutrient parameter analysis (including pH, EC, Organic Carbon, Macro and Micro nutrients), and expedite the test reports for issuing Soil Health Cards to the concerned orchardists."
        );
        $adminHdoTemplate = setting('covering_letter.hdo_title_template') ?: "Horticulture Development Officer (HDO)\n{ZONE}";

        $adminHeader = str_replace(array_keys($tags), array_values($tags), $adminHeaderTemplate);
        $adminAddressedTo = str_replace(array_keys($tags), array_values($tags), $adminAddressedToTemplate);
        $adminSubject = str_replace(array_keys($tags), array_values($tags), $adminSubjectTemplate);
        $adminBody = str_replace(array_keys($tags), array_values($tags), $adminBodyTemplate);
        $adminHdoTitle = str_replace(array_keys($tags), array_values($tags), $adminHdoTemplate);

        $defaultDispatchNo = "";
        $defaultDate = "";
        $defaultHeader = $adminHeader;
        $defaultSubheader = "Department of Horticulture, Government of Jammu & Kashmir";
        $defaultAddressedTo = $adminAddressedTo;
        $defaultSubject = $adminSubject;
        $defaultBody = $adminBody;
        $hdoTitle = $adminHdoTitle;

        // 5. Check if this User has previously saved custom Covering Letter text
        $userCustomJson = setting("user.{$user->id}.covering_letter_custom");
        $userCustom = $userCustomJson ? json_decode($userCustomJson, true) : null;
        $hasUserSaved = false;

        if (!empty($userCustom) && is_array($userCustom)) {
            $hasUserSaved = true;
            if (!empty($userCustom['dispatch_no'])) $defaultDispatchNo = $userCustom['dispatch_no'];
            if (!empty($userCustom['date'])) $defaultDate = $userCustom['date'];
            if (!empty($userCustom['header_title'])) $defaultHeader = $userCustom['header_title'];
            if (!empty($userCustom['addressed_to'])) $defaultAddressedTo = $userCustom['addressed_to'];
            if (!empty($userCustom['subject'])) $defaultSubject = $userCustom['subject'];
            if (!empty($userCustom['body_text'])) $defaultBody = $userCustom['body_text'];
            if (!empty($userCustom['hdo_title'])) $hdoTitle = $userCustom['hdo_title'];
            if (!empty($userCustom['officer_name'])) $officerName = $userCustom['officer_name'];
        }

        // 6. Query Samples for this User / Zone
        // Strict Business Rule: ONLY samples in 'collected' status and NOT YET in any batch can be selected
        $query = SoilSample::with([
            'farmer.village', 'farmer.panchayat', 'farmer.zone',
            'plot.crop', 'plot.soilType', 'plot.village', 'plot.panchayat',
            'collector', 'lab', 'qrCode'
        ])
        ->where('status', 'collected')
        ->whereNull('dispatch_batch_id');

        if ($user->zone_id) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('farmer', fn($fq) => $fq->where('zone_id', $user->zone_id))
                  ->orWhere('collected_by', $user->id);
            });
        } else {
            $query->where('collected_by', $user->id);
        }

        $samples = $query->orderBy('collected_at', 'desc')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'sample_code' => $s->sample_code,
                'qr_code' => $s->qrCode->code ?? $s->sample_code,
                'lab_number' => $s->lab_number,
                'status' => $s->status->value,
                'status_label' => $s->status->label(),
                'collected_at' => $s->collected_at ? $s->collected_at->format('d M Y') : '—',
                'farmer_name' => $s->farmer->name ?? '—',
                'farmer_phone' => $s->farmer->phone ?? '—',
                'parentage' => $s->farmer->parentage ?? '—',
                'aadhar_no' => $s->farmer->aadhar_no ?? '—',
                'village' => $s->farmer->village->name ?? $s->plot->village->name ?? '—',
                'panchayat' => $s->farmer->panchayat->name ?? $s->plot->panchayat->name ?? '—',
                'plot_name' => $s->plot->plot_name ?? 'Plot',
                'khasra_no' => $s->plot->khasra_no ?? '—',
                'crop_name' => $s->plot->crop->name ?? '—',
                'soil_type' => $s->plot->soilType->name ?? '—',
                'age_of_plants' => $s->plot->age_of_plants ? $s->plot->age_of_plants . ' yrs' : '—',
                'area_kanals' => $s->plot->area_kanals ? $s->plot->area_kanals . ' K' : '—',
            ];
        });

        $nextBatchNo = DispatchBatch::generateNextBatchNo($user->zone_id);
        $totalBatchesCount = DispatchBatch::where(function ($q) use ($user) {
            if ($user->zone_id) {
                $q->where('zone_id', $user->zone_id)->orWhere('created_by', $user->id);
            } else {
                $q->where('created_by', $user->id);
            }
        })->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'zone_name' => $zoneName,
                'district_name' => $districtName,
                'lab_name' => $labName,
                'next_batch_no' => $nextBatchNo,
                'unbatched_count' => $samples->count(),
                'total_batches_count' => $totalBatchesCount,
                'default_dispatch_no' => $defaultDispatchNo,
                'default_date' => $defaultDate,
                'default_header' => $defaultHeader,
                'default_subheader' => $defaultSubheader,
                'default_addressed_to' => $defaultAddressedTo,
                'default_subject' => $defaultSubject,
                'default_body' => $defaultBody,
                'hdo_title' => $hdoTitle,
                'officer_name' => $officerName,
                'officer_designation' => $officerDesignation,
                'officer_phone' => $user->phone ?? '',
                'has_user_saved' => $hasUserSaved,
                'admin_defaults' => [
                    'header_title' => $adminHeader,
                    'addressed_to' => $adminAddressedTo,
                    'subject' => $adminSubject,
                    'body_text' => $adminBody,
                    'hdo_title' => $adminHdoTitle,
                ],
                'samples' => $samples,
            ]
        ]);
    }

    /**
     * Generate & download the official PDF covering letter with the selected samples table.
     */
    public function generatePdf(Request $request)
    {
        $validated = $request->validate([
            'sample_ids' => 'required|array|min:1',
            'sample_ids.*' => 'exists:soil_samples,id',
            'batch_id' => 'nullable|integer',
            'dispatch_no' => 'nullable|string|max:150',
            'date' => 'nullable|string|max:50',
            'header_title' => 'required|string|max:255',
            'subheader' => 'nullable|string|max:255',
            'addressed_to' => 'required|string|max:500',
            'subject' => 'required|string|max:500',
            'body_text' => 'required|string',
            'hdo_title' => 'nullable|string|max:255',
            'officer_name' => 'nullable|string|max:255',
            'officer_designation' => 'nullable|string|max:255',
            'officer_phone' => 'nullable|string|max:50',
            'zone_name' => 'nullable|string|max:255',
            'district_name' => 'nullable|string|max:255',
        ]);

        $dispatchNo = $validated['dispatch_no'] ?? '';
        $date = $validated['date'] ?? '';

        $samples = SoilSample::with([
            'farmer.village', 'farmer.panchayat', 'farmer.zone', 'farmer.district',
            'plot.crop', 'plot.soilType', 'plot.village', 'plot.panchayat',
            'collector', 'lab', 'qrCode'
        ])
        ->whereIn('id', $validated['sample_ids'])
        ->where('status', 'collected')
        ->orderBy('collected_at', 'asc')
        ->get();

        if ($samples->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only samples in "Collected" status can be dispatched with a covering letter. The selected sample(s) have either already been received at the lab or are not in collected status.',
            ], 422);
        }

        // Check if regenerating for an existing batch or creating a new batch
        $batch = null;
        if (!empty($validated['batch_id'])) {
            $batch = DispatchBatch::find($validated['batch_id']);
        }

        if (!$batch) {
            // Business rule: Once a sample has been added to a batch, it cannot be added to another batch
            $alreadyBatched = SoilSample::whereIn('id', $validated['sample_ids'])
                ->whereNotNull('dispatch_batch_id')
                ->with('dispatchBatch')
                ->get();

            if ($alreadyBatched->isNotEmpty()) {
                $first = $alreadyBatched->first();
                $bCode = $first->dispatchBatch?->batch_no ?? 'another batch';
                return response()->json([
                    'status' => 'error',
                    'message' => "Sample {$first->sample_code} is already included in Batch {$bCode}. Once a sample has been added to a batch, it cannot be added to another batch.",
                ], 422);
            }

            // Generate batch number like BAT{ZoneID}01
            $batchNo = DispatchBatch::generateNextBatchNo($request->user()->zone_id);
            $labId = $samples->first()->lab_id ?? $request->user()->zone?->lab_id ?? 1;

            $batch = DispatchBatch::create([
                'batch_no' => $batchNo,
                'dispatch_no' => $dispatchNo,
                'date' => $date,
                'zone_id' => $request->user()->zone_id,
                'lab_id' => $labId,
                'created_by' => $request->user()->id,
                'sample_count' => $samples->count(),
                'status' => 'dispatched',
                'header_title' => $validated['header_title'],
                'subheader' => $validated['subheader'] ?? 'Department of Horticulture, Government of Jammu & Kashmir',
                'addressed_to' => $validated['addressed_to'],
                'subject' => $validated['subject'],
                'body_text' => $validated['body_text'],
                'hdo_title' => $validated['hdo_title'] ?? 'Horticulture Development Officer',
                'officer_name' => !empty(trim($validated['officer_name'] ?? '')) ? $validated['officer_name'] : ($request->user()?->name ?? 'Field Official'),
                'officer_designation' => $validated['officer_designation'] ?? 'Field Official',
                'officer_phone' => $validated['officer_phone'] ?? auth()->user()->phone,
            ]);

            // Assign samples to this batch
            SoilSample::whereIn('id', $samples->pluck('id'))->update([
                'dispatch_batch_id' => $batch->id,
            ]);
        }

        $pdf = Pdf::loadView('pdf.covering-letter', [
            'batch_no' => $batch->batch_no,
            'batch' => $batch,
            'dispatch_no' => $dispatchNo,
            'date' => $date,
            'header_title' => $validated['header_title'],
            'subheader' => $validated['subheader'] ?? 'Department of Horticulture, Government of Jammu & Kashmir',
            'addressed_to' => $validated['addressed_to'],
            'subject' => $validated['subject'],
            'body_text' => $validated['body_text'],
            'hdo_title' => $validated['hdo_title'] ?? 'Horticulture Development Officer',
            'officer_name' => !empty(trim($validated['officer_name'] ?? '')) ? $validated['officer_name'] : ($request->user()?->name ?? 'Field Official'),
            'officer_designation' => $validated['officer_designation'] ?? 'Field Official',
            'officer_phone' => $validated['officer_phone'] ?? auth()->user()->phone,
            'zone_name' => $validated['zone_name'] ?? (auth()->user()->zone->name ?? 'Zone'),
            'district_name' => $validated['district_name'] ?? '',
            'samples' => $samples,
            'total_samples' => $samples->count(),
        ]);

        $pdf->setPaper('a4', 'portrait');

        // Persist User's customized Covering Letter text for subsequent dispatches
        if ($request->user()) {
            set_setting("user.{$request->user()->id}.covering_letter_custom", json_encode([
                'dispatch_no' => $dispatchNo,
                'date' => $date,
                'header_title' => $validated['header_title'],
                'addressed_to' => $validated['addressed_to'],
                'subject' => $validated['subject'],
                'body_text' => $validated['body_text'],
                'hdo_title' => $validated['hdo_title'] ?? '',
                'officer_name' => $validated['officer_name'] ?? '',
            ]));
        }

        $filename = "Covering_Letter_{$batch->batch_no}.pdf";

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'X-Batch-No' => $batch->batch_no,
            'X-Batch-Id' => (string) $batch->id,
            'Access-Control-Expose-Headers' => 'X-Batch-No, X-Batch-Id, Content-Disposition',
        ]);
    }

    /**
     * Explicitly save user's customized covering letter text for next time.
     */
    public function saveUserDefaults(Request $request)
    {
        $validated = $request->validate([
            'dispatch_no' => 'nullable|string|max:150',
            'date' => 'nullable|string|max:50',
            'header_title' => 'nullable|string|max:255',
            'addressed_to' => 'nullable|string|max:500',
            'subject' => 'nullable|string|max:500',
            'body_text' => 'nullable|string',
            'hdo_title' => 'nullable|string|max:255',
            'officer_name' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $existing = json_decode(setting("user.{$user->id}.covering_letter_custom", '{}'), true) ?: [];
        $merged = array_merge($existing, array_filter($validated, fn($v) => !is_null($v)));
        set_setting("user.{$user->id}.covering_letter_custom", json_encode($merged));

        return response()->json([
            'status' => 'success',
            'message' => 'Covering letter preferences saved successfully.',
            'data' => $merged,
        ]);
    }

    /**
     * Reset user's customized covering letter text back to official admin defaults.
     */
    public function resetUserDefaults(Request $request)
    {
        $user = $request->user();
        forget_setting("user.{$user->id}.covering_letter_custom");
        \App\Models\Setting::where('key', "user.{$user->id}.covering_letter_custom")->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reset to official admin defaults.',
        ]);
    }
}
