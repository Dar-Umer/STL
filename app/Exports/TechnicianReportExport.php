<?php

namespace App\Exports;

use App\Models\SoilSample;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TechnicianReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
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
        $q = User::has('analyzedSamples');
        if ($this->labId) {
            $q->where('lab_id', $this->labId);
        }
        $users = $q->with('lab')->get();

        return $users->map(function ($user) {
            $from = $this->dateFrom ?? now()->subYear()->startOfDay()->format('Y-m-d');
            $to = ($this->dateTo ?? now()->endOfDay()->format('Y-m-d')) . ' 23:59:59';
            $samples = SoilSample::where('analyzed_by', $user->id)
                ->where('analyzed_at', '>=', $from)
                ->where('analyzed_at', '<=', $to);
            $all = (clone $samples)->count();
            $user->total_analyzed = $all;
            $user->received_count = (clone $samples)->where('status', 'received')->count();
            $user->testing_count = (clone $samples)->where('status', 'testing')->count();
            $user->reported_count = (clone $samples)->where('status', 'reported')->count();
            $user->last_analysis = (clone $samples)->max('analyzed_at');
            return $user;
        })->sortByDesc('total_analyzed')->values();
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Phone', 'Lab',
            'Total Analyzed', 'Received', 'Testing', 'Reported', 'Last Analysis',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->phone ?? '',
            $user->lab->name ?? '',
            $user->total_analyzed,
            $user->received_count,
            $user->testing_count,
            $user->reported_count,
            $user->last_analysis ? \Carbon\Carbon::parse($user->last_analysis)->format('d/m/Y') : '',
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
                $event->sheet->mergeCells('A1:I1');
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [2 => ['font' => ['bold' => true]]];
    }
}
