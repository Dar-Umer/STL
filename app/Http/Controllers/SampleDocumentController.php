<?php

namespace App\Http\Controllers;

use App\Models\SoilSample;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SampleDocumentController extends Controller
{
    private function authorizeSampleAccess(SoilSample $sample): void
    {
        $user = auth()->user() ?? request()->user();
        if ($user->hasRole('Admin')) return;
        if ($user->hasRole('Farmer')) {
            $farmer = $user->farmer;
            if (!$farmer || $sample->farmer_id !== $farmer->id) abort(403);
            return;
        }
        if ($user->hasRole('Lab Manager')) {
            if ($sample->lab_id !== $user->lab_id) abort(403);
            return;
        }
        if ($user->hasRole('Field Official')) {
            if ($sample->lab_id !== $user->lab_id) abort(403);
            return;
        }
        abort(403);
    }

    public function acknowledgement($sampleId)
    {
        $sample = SoilSample::with(['farmer.state', 'farmer.district', 'farmer.zone', 'farmer.panchayat', 'farmer.village', 'plot', 'collector', 'lab'])->findOrFail($sampleId);
        $this->authorizeSampleAccess($sample);
        return view('pdf.acknowledgement', compact('sample'));
    }

    public function acknowledgementPdf($sampleId)
    {
        $sample = SoilSample::with(['farmer.state', 'farmer.district', 'farmer.zone', 'farmer.panchayat', 'farmer.village', 'plot', 'collector', 'lab'])->findOrFail($sampleId);
        $this->authorizeSampleAccess($sample);
        $pdf = Pdf::loadView('pdf.acknowledgement', compact('sample'));
        return $pdf->download("acknowledgement-{$sample->sample_code}.pdf");
    }

    public function downloadReport($sampleId)
    {
        $sample = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.zone', 'farmer.panchayat', 'farmer.village',
            'plot.soilType', 'plot.crop', 'plot.state', 'plot.district', 'plot.zone', 'plot.panchayat', 'plot.village',
            'collector', 'analyst', 'lab',
            'results.parameter', 'qrCode',
        ])->findOrFail($sampleId);

        $this->authorizeSampleAccess($sample);

        if ($sample->status->value !== 'reported') {
            abort(404);
        }

        $qrSvg = QrCode::generate(route('public.report', $sample->report_token));
        $qrBase64 = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.sample-report', compact('sample', 'qrBase64'));
        return $pdf->download("report-{$sample->sample_code}.pdf");
    }

    public function report($token)
    {
        $sample = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.zone', 'farmer.panchayat', 'farmer.village',
            'plot.soilType', 'plot.crop', 'plot.state', 'plot.district', 'plot.zone', 'plot.panchayat', 'plot.village',
            'collector', 'analyst', 'lab',
            'results.parameter', 'qrCode',
        ])->where('report_token', $token)->firstOrFail();

        if ($sample->status->value !== 'reported') {
            abort(404);
        }

        $qrSvg = QrCode::generate(route('public.report', $sample->report_token));
        $qrBase64 = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.sample-report', compact('sample', 'qrBase64'));
        return $pdf->download("report-{$sample->sample_code}.pdf");
    }

    public function evaluationBatchPdf($batchId)
    {
        $batch = \App\Models\EvaluationBatch::with(['lab', 'receivedBy'])->findOrFail($batchId);

        $samples = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.zone', 'farmer.panchayat', 'farmer.village',
            'plot.crop', 'plot.soilType',
            'results.parameter',
        ])
        ->where('evaluation_batch_id', $batch->id)
        ->orderBy('lab_number', 'asc')
        ->get();

        $parameters = \App\Models\SoilTestParameter::where('is_active', true)
            ->orderBy('order_column', 'asc')
            ->get();

        $lab = $batch->lab ?? (auth()->user()?->lab ?? \App\Models\Lab::first());

        $pdf = Pdf::loadView('pdf.evaluation-sheet', compact('batch', 'samples', 'parameters', 'lab'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("evaluation-sheet-{$batch->batch_code}.pdf");
    }

    public function evaluationSelectedPdf(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids)) {
            abort(400, 'No samples selected.');
        }

        $samples = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.zone', 'farmer.panchayat', 'farmer.village',
            'plot.crop', 'plot.soilType',
            'results.parameter',
            'evaluationBatch',
        ])
        ->whereIn('id', $ids)
        ->orderBy('lab_number', 'asc')
        ->get();

        $parameters = \App\Models\SoilTestParameter::where('is_active', true)
            ->orderBy('order_column', 'asc')
            ->get();

        $batch = (object) [
            'batch_code' => 'EVB-CUSTOM-' . date('Ymd'),
            'created_at' => now(),
            'lab_number_from' => $samples->first()?->lab_number ?? '—',
            'lab_number_to' => $samples->last()?->lab_number ?? '—',
            'receivedBy' => auth()->user(),
        ];

        $lab = auth()->user()?->lab ?? \App\Models\Lab::first();

        $pdf = Pdf::loadView('pdf.evaluation-sheet', compact('batch', 'samples', 'parameters', 'lab'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("evaluation-sheet-selected.pdf");
    }
}
