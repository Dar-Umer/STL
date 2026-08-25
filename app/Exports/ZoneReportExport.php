<?php

namespace App\Exports;

use App\Models\Location;
use App\Models\SoilSample;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ZoneReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $labId;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($labId = null, $dateFrom = null, $dateTo = null)
    {
        $this->labId = $labId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $zones = Location::where('type', 'zone')
            ->withCount(['fieldOfficials'])
            ->when($this->labId, function ($q) {
                $q->whereIn('id', function ($sub) {
                    $sub->select('zone_id')->from('farmers')
                        ->where('lab_id', $this->labId)
                        ->whereNotNull('zone_id');
                });
            })
            ->get()->keyBy('id');

        $stats = SoilSample::selectRaw('farmers.zone_id, soil_samples.status, soil_samples.source, COUNT(*) as total')
            ->join('farmers', 'soil_samples.farmer_id', '=', 'farmers.id')
            ->whereIn('farmers.zone_id', $zones->keys())
            ->groupBy('farmers.zone_id', 'soil_samples.status', 'soil_samples.source')
            ->get()
            ->groupBy('zone_id');

        return $zones->map(function ($zone) use ($stats) {
            $s = $stats->get($zone->id, collect());
            $zone->samples_count = $s->sum('total');
            $zone->collected_count = $s->where('status', 'collected')->sum('total');
            $zone->received_count = $s->where('status', 'received')->sum('total');
            $zone->testing_count = $s->where('status', 'testing')->sum('total');
            $zone->reported_count = $s->where('status', 'reported')->sum('total');
            $zone->self_samples_count = $s->where('source', 'self')->sum('total');
            return $zone;
        })->sortByDesc('samples_count')->values();
    }

    public function headings(): array
    {
        return [
            'Zone Name', 'Total Samples', 'Field', 'Self',
            'Collected', 'Received', 'Testing', 'Reported',
            'Field Officials', 'Completion (%)',
        ];
    }

    public function map($zone): array
    {
        $rate = $zone->samples_count > 0
            ? round(($zone->reported_count / $zone->samples_count) * 100, 1)
            : 0;
        return [
            $zone->name,
            $zone->samples_count,
            $zone->samples_count - ($zone->self_samples_count ?? 0),
            $zone->self_samples_count ?? 0,
            $zone->collected_count,
            $zone->received_count,
            $zone->testing_count,
            $zone->reported_count,
            $zone->field_officials_count,
            $rate,
        ];
    }

    public function registerEvents(): array
    {
        $dateFrom = $this->dateFrom ? \Carbon\Carbon::parse($this->dateFrom)->format('d/m/Y') : 'All';
        $dateTo = $this->dateTo ? \Carbon\Carbon::parse($this->dateTo)->format('d/m/Y') : 'All';
        return [
            AfterSheet::class => function (AfterSheet $event) use ($dateFrom, $dateTo) {
                $event->sheet->insertNewRowBefore(1, 1);
                $event->sheet->setCellValue('A1', "Period: {$dateFrom} - {$dateTo}");
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $event->sheet->mergeCells('A1:J1');
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [2 => ['font' => ['bold' => true]]];
    }
}
