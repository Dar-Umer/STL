<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DispatchBatch;
use App\Models\SoilSample;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DispatchBatchController extends Controller
{
    /**
     * List batches for the authenticated field official / zone.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = DispatchBatch::with(['zone', 'lab', 'creator', 'receiver', 'evaluationBatch'])
            ->where(function ($q) use ($user) {
                if ($user->zone_id) {
                    $q->where('zone_id', $user->zone_id)->orWhere('created_by', $user->id);
                } else {
                    $q->where('created_by', $user->id);
                }
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('batch_no', 'like', "%{$s}%")
                  ->orWhere('dispatch_no', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%");
            });
        }

        $batches = $query->orderBy('id', 'desc')->paginate($request->input('per_page', 20));

        $data = $batches->through(function ($b) {
            return [
                'id' => $b->id,
                'batch_no' => $b->batch_no,
                'dispatch_no' => $b->dispatch_no ?? '—',
                'date' => $b->date ?? '—',
                'sample_count' => $b->sample_count,
                'status' => $b->status,
                'zone_name' => $b->zone->name ?? 'Zone',
                'lab_name' => $b->lab->name ?? 'District Laboratory',
                'created_by_name' => $b->creator->name ?? 'Field Official',
                'created_at' => $b->created_at ? $b->created_at->format('d M Y, h:i A') : '—',
                'received_at' => $b->received_at ? $b->received_at->format('d M Y, h:i A') : null,
                'received_by_name' => $b->receiver->name ?? null,
                'evaluation_batch_code' => $b->evaluationBatch->batch_code ?? null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show single batch details and its full sample schedule.
     */
    public function show(Request $request, $id)
    {
        $batch = DispatchBatch::with([
            'zone', 'lab', 'creator', 'receiver', 'evaluationBatch',
            'soilSamples.farmer.village', 'soilSamples.farmer.panchayat', 'soilSamples.farmer.zone',
            'soilSamples.plot.crop', 'soilSamples.plot.soilType', 'soilSamples.plot.village',
            'soilSamples.qrCode', 'soilSamples.collector'
        ])->findOrFail($id);

        $samples = $batch->soilSamples->map(function ($s) {
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

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $batch->id,
                'batch_no' => $batch->batch_no,
                'dispatch_no' => $batch->dispatch_no ?? '—',
                'date' => $batch->date ?? '—',
                'sample_count' => $batch->sample_count,
                'status' => $batch->status,
                'zone_name' => $batch->zone->name ?? 'Zone',
                'district_name' => $batch->zone->district->name ?? '',
                'lab_name' => $batch->lab->name ?? 'District Laboratory',
                'created_by_name' => $batch->creator->name ?? 'Field Official',
                'created_at' => $batch->created_at ? $batch->created_at->format('d M Y, h:i A') : '—',
                'received_at' => $batch->received_at ? $batch->received_at->format('d M Y, h:i A') : null,
                'received_by_name' => $batch->receiver->name ?? null,
                'evaluation_batch_code' => $batch->evaluationBatch->batch_code ?? null,
                'header_title' => $batch->header_title,
                'subheader' => $batch->subheader,
                'addressed_to' => $batch->addressed_to,
                'subject' => $batch->subject,
                'body_text' => $batch->body_text,
                'hdo_title' => $batch->hdo_title,
                'officer_name' => $batch->officer_name,
                'officer_designation' => $batch->officer_designation,
                'officer_phone' => $batch->officer_phone,
                'samples' => $samples,
            ]
        ]);
    }

    /**
     * Re-download Covering Letter PDF for an existing batch.
     */
    public function downloadPdf($id)
    {
        $batch = DispatchBatch::with([
            'zone.district', 'lab', 'creator', 'receiver',
            'soilSamples.farmer.village', 'soilSamples.farmer.panchayat', 'soilSamples.farmer.zone',
            'soilSamples.plot.crop', 'soilSamples.plot.soilType', 'soilSamples.plot.village',
            'soilSamples.plot.panchayat', 'soilSamples.qrCode', 'soilSamples.collector'
        ])->findOrFail($id);

        $zoneName = $batch->zone->name ?? 'Zone';
        $districtName = $batch->zone->district->name ?? '';

        $pdf = Pdf::loadView('pdf.covering-letter', [
            'batch_no' => $batch->batch_no,
            'dispatch_no' => $batch->dispatch_no ?? '',
            'date' => $batch->date ?? '',
            'header_title' => $batch->header_title ?: "OFFICE OF THE HORTICULTURE DEVELOPMENT OFFICER, {$zoneName}",
            'subheader' => $batch->subheader ?: 'Department of Horticulture, Government of Jammu & Kashmir',
            'addressed_to' => $batch->addressed_to ?: "The Incharge / Testing Officer,\n{$batch->lab->name},\nJammu & Kashmir.",
            'subject' => $batch->subject ?: "Transmission of physical soil samples collected from {$zoneName} for laboratory testing and issuance of Soil Health Cards.",
            'body_text' => $batch->body_text,
            'hdo_title' => $batch->hdo_title ?: "Horticulture Development Officer (HDO)\n{$zoneName}",
            'officer_name' => $batch->officer_name ?: ($batch->creator->name ?? 'Field Official'),
            'officer_designation' => $batch->officer_designation ?: 'Field Official',
            'officer_phone' => $batch->officer_phone ?: ($batch->creator->phone ?? ''),
            'zone_name' => $zoneName,
            'district_name' => $districtName,
            'samples' => $batch->soilSamples,
            'total_samples' => $batch->soilSamples->count(),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = "Covering_Letter_{$batch->batch_no}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Cancel/delete a dispatch batch if not yet received by the lab.
     * This unlinks all samples so they can be re-batched.
     */
    public function destroy(Request $request, $id)
    {
        $batch = DispatchBatch::findOrFail($id);

        if ($batch->status === 'received') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot cancel Batch ' . $batch->batch_no . ' because it has already been received and checked-in by the laboratory.',
            ], 422);
        }

        // Release samples back to unbatched pool
        SoilSample::where('dispatch_batch_id', $batch->id)->update([
            'dispatch_batch_id' => null,
        ]);

        $batchNo = $batch->batch_no;
        $batch->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Batch {$batchNo} cancelled successfully. All samples have been released back to your unbatched collection.",
        ]);
    }
}
