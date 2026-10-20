<?php

namespace App\Exports;

use App\Models\SoilSample;
use App\Models\SoilTestParameter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EvaluationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $sampleIds;
    protected $parameters;
    protected $index = 0;

    public function __construct(array $sampleIds = [])
    {
        $this->sampleIds = $sampleIds;
        $this->parameters = SoilTestParameter::where('is_active', true)->orderBy('order_column')->get();
    }

    public function collection()
    {
        $query = SoilSample::with([
            'farmer.state', 'farmer.district', 'farmer.village', 'farmer.zone',
            'plot.crop', 'plot.soilType',
            'collector', 'analyst', 'lab', 'evaluationBatch',
            'results.parameter',
        ])->whereNotNull('lab_number');

        if (!empty($this->sampleIds)) {
            $query->whereIn('id', $this->sampleIds);
        }

        if (!auth()->user()->hasRole('Admin')) {
            $query->where('lab_id', auth()->user()->lab_id);
        }

        return $query->orderBy('lab_number', 'asc')->get();
    }

    public function headings(): array
    {
        $headers = [
            '#',
            'Lab Code',
            'Sample Code',
            'Batch Code',
            'Farmer Name',
            'Mobile',
            'Village / Location',
            'Zone / Block',
            'District',
            'Date Received',
            'Status',
        ];

        foreach ($this->parameters as $param) {
            $unit = $param->unit ? " ({$param->unit})" : '';
            $headers[] = "{$param->name}{$unit}";
        }

        $headers[] = 'Tested By';
        $headers[] = 'Date Analyzed';
        $headers[] = 'Evaluation Remarks';

        return $headers;
    }

    /**
     * @param SoilSample $sample
     */
    public function map($sample): array
    {
        $this->index++;
        $results = $sample->results->keyBy('parameter_id');

        $row = [
            $this->index,
            $sample->lab_number,
            $sample->sample_code,
            $sample->evaluationBatch?->batch_code ?? '—',
            $sample->farmer?->name ?? '—',
            $sample->farmer?->phone ?? '—',
            $sample->farmer?->village?->name ?? $sample->farmer?->village_name ?? '—',
            $sample->farmer?->zone?->name ?? '—',
            $sample->farmer?->district?->name ?? '—',
            $sample->received_at ? $sample->received_at->format('d/m/Y H:i') : '—',
            ucfirst($sample->status->value),
        ];

        foreach ($this->parameters as $param) {
            $row[] = $results->get($param->id)?->value ?? '';
        }

        $row[] = $sample->analyst?->name ?? '—';
        $row[] = $sample->analyzed_at ? $sample->analyzed_at->format('d/m/Y') : '—';
        $row[] = $sample->evaluation_remarks ?? '';

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF047857'],
                ],
            ],
        ];
    }
}
