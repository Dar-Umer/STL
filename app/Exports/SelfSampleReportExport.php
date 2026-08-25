<?php

namespace App\Exports;

use App\Models\Lab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SelfSampleReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
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
        $q = Lab::withCount([
            'soilSamples as self_samples_count' => fn($q) => $q->where('source', 'self'),
            'soilSamples as self_received_count' => fn($q) => $q->where('source', 'self')->where('status', 'received'),
            'soilSamples as self_testing_count' => fn($q) => $q->where('source', 'self')->where('status', 'testing'),
            'soilSamples as self_reported_count' => fn($q) => $q->where('source', 'self')->where('status', 'reported'),
        ]);
        if ($this->labId) {
            $q->where('id', $this->labId);
        }
        return $q->orderBy('self_samples_count', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Lab Name', 'Code', 'Total Self-Samples',
            'Received', 'Testing', 'Reported', 'Report Rate (%)',
        ];
    }

    public function map($lab): array
    {
        $rate = $lab->self_samples_count > 0
            ? round(($lab->self_reported_count / $lab->self_samples_count) * 100, 1)
            : 0;
        return [
            $lab->name,
            $lab->code,
            $lab->self_samples_count,
            $lab->self_received_count,
            $lab->self_testing_count,
            $lab->self_reported_count,
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
                $event->sheet->mergeCells('A1:G1');
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [2 => ['font' => ['bold' => true]]];
    }
}
