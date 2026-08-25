<?php

namespace App\Http\Controllers;

use App\Models\QrCode;

class QrPrintController extends Controller
{
    public function print($prefix)
    {
        $query = QrCode::where('prefix', $prefix);

        if (request()->has('start') && request()->has('end')) {
            $query->whereBetween('sequence', [(int) request('start'), (int) request('end')]);
        }

        $user = auth()->user();
        if ($user->hasRole('Admin')) {
            if (request()->has('lab_id')) {
                $query->where('lab_id', (int) request('lab_id'));
            }
        } elseif ($user->hasRole('Lab Manager')) {
            $query->where('lab_id', $user->lab_id);
        } else {
            $query->where('created_by', $user->id);
        }

        $codes = $query->orderBy('sequence')->get();

        if ($codes->isEmpty()) {
            abort(404, 'No QR codes found for this prefix.');
        }

        $labName = $codes->first()->lab?->name
            ?? $user?->lab?->name
            ?? $user?->name
            ?? 'Soil Lab';

        return view('admin.qr-codes-print', compact('codes', 'labName'));
    }

    public function printSelected()
    {
        $ids = explode(',', request('ids'));
        if (empty($ids)) {
            abort(404, 'No QR codes specified.');
        }

        $query = QrCode::whereIn('id', $ids);
        $user = auth()->user();
        if ($user->hasRole('Admin')) {
            if (request()->has('lab_id')) {
                $query->where('lab_id', (int) request('lab_id'));
            }
        } elseif ($user->hasRole('Lab Manager')) {
            $query->where('lab_id', $user->lab_id);
        } else {
            $query->where('created_by', $user->id);
        }

        $codes = $query->orderBy('prefix')->orderBy('sequence')->get();

        if ($codes->isEmpty()) {
            abort(404, 'No QR codes found.');
        }

        $labName = $codes->first()->lab?->name
            ?? $user?->lab?->name
            ?? $user?->name
            ?? 'Soil Lab';

        return view('admin.qr-codes-print', compact('codes', 'labName'));
    }
}
