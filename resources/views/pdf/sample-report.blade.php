<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Soil Test Report - {{ $sample->sample_code }}</title>
    <style>
        @page { margin: 8mm 8mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 8.5pt; color: #2d2d2d; line-height: 1.35; }
        .header { border-bottom: 2.5px solid #28a745; padding-bottom: 6px; margin-bottom: 5px; }
        .header h1 { margin: 0; font-size: 14pt; color: #28a745; letter-spacing: 1.5px; }
        .header h2 { margin: 1px 0 0; font-size: 9pt; color: #555; font-weight: normal; }
        .header .lab-info { font-size: 7pt; color: #777; margin-top: 2px; }
        .ref-row { text-align: right; font-size: 7pt; color: #666; margin-bottom: 4px; }
        .section-title { font-size: 8.5pt; font-weight: bold; color: #fff; background: #28a745; padding: 3px 7px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.details { width: 100%; border-collapse: collapse; }
        table.details td { padding: 1.5px 3px; font-size: 8pt; }
        table.details td.label { width: 100px; color: #888; }
        table.details td.value { font-weight: bold; color: #2d2d2d; }
        table.results { width: 100%; border-collapse: collapse; font-size: 8pt; }
        table.results th { background: #28a745; color: #fff; padding: 3px 5px; text-align: left; font-size: 7.5pt; }
        table.results td { padding: 2px 5px; border-bottom: 0.5px solid #e8e8e8; }
        table.results tr:nth-child(even) td { background: #fafafa; }
        .rating-low { background: #ffc107; color: #333; padding: 1px 6px; font-size: 7pt; }
        .rating-medium { background: #17a2b8; color: #fff; padding: 1px 6px; font-size: 7pt; }
        .rating-high { background: #28a745; color: #fff; padding: 1px 6px; font-size: 7pt; }
        .rec-text { font-size: 7.5pt; line-height: 1.4; text-align: justify; padding: 3px 6px; background: #f6fdf6; border-left: 2px solid #28a745; margin: 0; }
        .footer { font-size: 6.5pt; color: #999; text-align: center; border-top: 0.5px solid #ddd; padding-top: 2px; margin-top: 3px; }
        .badge { padding: 1px 6px; font-size: 7pt; background: #28a745; color: #fff; }
        .col-left { width: 50%; vertical-align: top; padding-right: 6px; }
        .col-right { width: 50%; vertical-align: top; }
        .info-grid { width: 100%; border-collapse: collapse; }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; }
        .qr-wrap { text-align: right; vertical-align: middle; width: 95px; }
        .qr-wrap img { width: 85px; height: 85px; display: inline-block; }
        .qr-label { font-size: 6pt; color: #aaa; text-align: center; margin-top: 1px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="header" style="border-bottom:none;padding-bottom:0;margin-bottom:0">
                    <h1>SOIL TESTING LABORATORY</h1>
                    <h2>Soil Analysis Report</h2>
                    <div class="lab-info">{{ $sample->lab->name ?? 'Soil Testing Lab' }} &mdash; {{ $sample->lab->address ?? '' }} | {{ $sample->lab->phone ?? '' }} | {{ $sample->lab->email ?? '' }}</div>
                </div>
            </td>
            <td class="qr-wrap">
                <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR">
                <div class="qr-label">Scan to download PDF</div>
            </td>
        </tr>
    </table>

    <div class="header" style="margin-top:5px"></div>

    <div class="ref-row">
        <strong>Report No:</strong> RPT-{{ $sample->sample_code }} &nbsp;|&nbsp;
        <strong>Date:</strong> {{ $sample->analyzed_at?->format('d F Y') ?? $sample->updated_at?->format('d F Y') }}
    </div>

    <div class="section-title">Sample &amp; Farmer Details</div>
    <table class="info-grid">
        <tr>
            <td class="col-left">
                <table class="details">
                    <tr><td class="label">Sample Code</td><td class="value">{{ $sample->sample_code }}</td></tr>
                    <tr><td class="label">Collection Date</td><td>{{ $sample->collected_at?->format('d F Y') }}</td></tr>
                    <tr><td class="label">Received Date</td><td>{{ $sample->received_at?->format('d F Y') ?? '—' }}</td></tr>
                    <tr><td class="label">Analysis Date</td><td>{{ $sample->analyzed_at?->format('d F Y') ?? '—' }}</td></tr>
                    <tr><td class="label">Status</td><td><span class="badge">{{ $sample->status->label() }}</span></td></tr>
                </table>
            </td>
            <td class="col-right">
                <table class="details">
                    <tr><td class="label">Farmer Name</td><td class="value">{{ $sample->farmer->name ?? '—' }}</td></tr>
                    @if($sample->farmer?->parentage)
                    <tr><td class="label">Parentage</td><td>{{ $sample->farmer->parentage }}</td></tr>
                    @endif
                    <tr><td class="label">Phone</td><td>{{ $sample->farmer->phone ?? '—' }}</td></tr>
                    @if($sample->farmer?->state?->name)
                    <tr><td class="label">State</td><td>{{ $sample->farmer->state->name }}</td></tr>
                    @endif
                    @if($sample->farmer?->district?->name)
                    <tr><td class="label">District</td><td>{{ $sample->farmer->district->name }}</td></tr>
                    @endif
                    @if($sample->farmer?->zone?->name)
                    <tr><td class="label">Zone</td><td>{{ $sample->farmer->zone->name }}</td></tr>
                    @endif
                    @if($sample->farmer?->panchayat?->name)
                    <tr><td class="label">Panchayat</td><td>{{ $sample->farmer->panchayat->name }}</td></tr>
                    @endif
                    @if($sample->farmer?->village?->name)
                    <tr><td class="label">Village</td><td>{{ $sample->farmer->village->name }}</td></tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title" style="margin-top:3px">Plot &amp; Field Details</div>
    <table class="info-grid">
        <tr>
            <td class="col-left">
                <table class="details">
                    <tr><td class="label">Plot Name</td><td class="value">{{ $sample->plot->plot_name ?? '—' }}</td></tr>
                    @if($sample->plot?->khasra_no)
                    <tr><td class="label">Khasra No</td><td>{{ $sample->plot->khasra_no }}</td></tr>
                    @endif
                    @if($sample->plot?->area_kanals)
                    <tr><td class="label">Area (Kanals)</td><td>{{ number_format($sample->plot->area_kanals, 2) }}</td></tr>
                    @endif
                    @if($sample->plot?->latitude && $sample->plot?->longitude)
                    <tr><td class="label">Coordinates</td><td>{{ $sample->plot->latitude }}, {{ $sample->plot->longitude }}</td></tr>
                    @endif
                </table>
            </td>
            <td class="col-right">
                <table class="details">
                    @if($sample->plot?->soilType)
                    <tr><td class="label">Soil Type</td><td class="value">{{ $sample->plot->soilType->name }}</td></tr>
                    @endif
                    @if($sample->plot?->crop)
                    <tr><td class="label">Crop</td><td class="value">{{ $sample->plot->crop->name }}{{ $sample->plot?->variety ? ' ('.$sample->plot->variety.')' : '' }}</td></tr>
                    @endif
                    @if($sample->plot?->age_of_plants)
                    <tr><td class="label">Crop Age</td><td>{{ $sample->plot->age_of_plants }} years</td></tr>
                    @endif
                    @if($sample->plot?->state?->name)
                    <tr><td class="label">State</td><td>{{ $sample->plot->state->name }}</td></tr>
                    @endif
                    @if($sample->plot?->district?->name)
                    <tr><td class="label">District</td><td>{{ $sample->plot->district->name }}</td></tr>
                    @endif
                    @if($sample->plot?->zone?->name)
                    <tr><td class="label">Zone</td><td>{{ $sample->plot->zone->name }}</td></tr>
                    @endif
                    @if($sample->plot?->panchayat?->name)
                    <tr><td class="label">Panchayat</td><td>{{ $sample->plot->panchayat->name }}</td></tr>
                    @endif
                    @if($sample->plot?->village?->name)
                    <tr><td class="label">Village</td><td>{{ $sample->plot->village->name }}</td></tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Test Results</div>
    <table class="results">
        <thead>
            <tr>
                <th style="width:28px">#</th>
                <th>Parameter</th>
                <th style="width:55px">Value</th>
                <th style="width:58px">Range</th>
                <th style="width:38px">Unit</th>
                <th style="width:50px">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sample->results as $result)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $result->parameter->name ?? '—' }}</td>
                <td><strong>{{ $result->value ?? '—' }}</strong></td>
                <td><small>{{ $result->parameter->range ?? '—' }}</small></td>
                <td>{{ $result->parameter->unit ?? '—' }}</td>
                <td>
                    @if($result->rating)
                    @php $tier = strtolower($result->rating); @endphp
                    <span style="{{ $result->parameter->getRatingBadgeStyle($tier) }}">{{ $result->parameter->getRatingLabel($tier) }}</span>
                    @else
                    —
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#888">No test results available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($sample->recommendations)
    <div class="section-title" style="margin-top:3px">Recommendations</div>
    <div class="rec-text">{{ $sample->recommendations }}</div>
    @endif

    <table class="details" style="margin-top:3px">
        <tr><td class="label" style="width:100px">Collected By</td><td>{{ $sample->collector->name ?? '—' }}</td></tr>
        <tr><td class="label">Analyzed By</td><td>{{ $sample->analyst->name ?? '—' }}</td></tr>
    </table>

    <div class="footer">
        <p><strong>Disclaimer:</strong> This report is computer-generated and does not require a physical signature. The results are for the tested sample only.</p>
        <p>{{ $sample->lab->name ?? '' }} &mdash; {{ $sample->lab->address ?? '' }} | Phone: {{ $sample->lab->phone ?? '' }} | Email: {{ $sample->lab->email ?? '' }}</p>
    </div>
</body>
</html>