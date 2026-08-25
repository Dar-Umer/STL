<?php

namespace App\Exports;

use App\Models\SoilSample;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SoilSamplesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $user;
    protected $statusFilter;
    protected $dateFrom;
    protected $dateTo;
    protected $search;

    public function __construct($user, $statusFilter = '', $dateFrom = '', $dateTo = '', $search = '')
    {
        $this->user = $user;
        $this->statusFilter = $statusFilter;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->search = $search;
    }

    public function query()
    {
        $query = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.village', 'farmer.zone', 'farmer.panchayat',
            'plot.crop', 'plot.soilType', 'plot.soilColor',
            'collector', 'analyst', 'lab', 'qrCode',
        ]);

        if ($this->user->hasRole('Farmer')) {
            $farmer = $this->user->farmer;
            if ($farmer) {
                $query->where('farmer_id', $farmer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif (!$this->user->hasRole('Admin')) {
            $query->where('lab_id', $this->user->lab_id);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('sample_code', 'like', "%{$this->search}%")
                  ->orWhereHas('farmer', fn($f) => $f->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('plot', fn($p) => $p->where('plot_name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->dateFrom && $this->dateTo && $this->dateFrom > $this->dateTo) {
            [$this->dateFrom, $this->dateTo] = [$this->dateTo, $this->dateFrom];
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Sample Code',
            'Farmer Name',
            'Farmer Phone',
            'Farmer Aadhaar',
            'State',
            'District',
            'Zone',
            'Panchayat',
            'Village',
            'Plot Name',
            'Khasra No',
            'Area (Kanals)',
            'Crop',
            'Variety',
            'Soil Type',
            'Source',
            'Lab',
            'Status',
            'Collected By',
            'Collected At',
            'Received At',
            'Analyzed By',
            'Analyzed At',
            'QR Code',
            'Recommendations',
            'Created At',
        ];
    }

    public function map($sample): array
    {
        return [
            $sample->sample_code,
            $sample->farmer?->name ?? '',
            $sample->farmer?->phone ?? '',
            $sample->farmer?->aadhar_no ?? '',
            $sample->farmer?->state?->name ?? '',
            $sample->farmer?->district?->name ?? '',
            $sample->farmer?->zone?->name ?? '',
            $sample->farmer?->panchayat?->name ?? '',
            $sample->farmer?->village?->name ?? '',
            $sample->plot?->plot_name ?? '',
            $sample->plot?->khasra_no ?? '',
            $sample->plot?->area_kanals ?? '',
            $sample->plot?->crop?->name ?? '',
            $sample->plot?->variety ?? '',
            $sample->plot?->soilType?->name ?? '',
            $sample->source === 'self' ? 'Self' : 'Field',
            $sample->lab?->name ?? '',
            $sample->status->label(),
            $sample->collector?->name ?? '',
            $sample->collected_at?->format('d/m/Y H:i') ?? '',
            $sample->received_at?->format('d/m/Y H:i') ?? '',
            $sample->analyst?->name ?? '',
            $sample->analyzed_at?->format('d/m/Y H:i') ?? '',
            $sample->qr_code_path ?? $sample->qrCode?->code ?? '',
            $sample->recommendations ?? '',
            $sample->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
