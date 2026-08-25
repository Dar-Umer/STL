<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sample Acknowledgement - {{ $sample->sample_code }}</title>
    <script>window.onload = function () { window.print(); }</script>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px double #28a745; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { margin: 0 0 5px; font-size: 18pt; color: #28a745; }
        .header h2 { margin: 0; font-size: 14pt; color: #555; font-weight: normal; }
        .header p { margin: 3px 0 0; font-size: 9pt; color: #888; }
        .ref-row { text-align: right; font-size: 10pt; color: #666; margin-bottom: 20px; }
        .section { margin-bottom: 18px; }
        .section-title { font-size: 11pt; font-weight: bold; color: #28a745; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 10px; }
        table.details { width: 100%; border-collapse: collapse; }
        table.details td { padding: 4px 6px; font-size: 10pt; }
        table.details td.label { width: 140px; color: #888; }
        table.details td.value { font-weight: bold; color: #333; }
        .footer { margin-top: 35px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 9pt; color: #888; text-align: center; }
        .stamp-area { margin-top: 30px; }
        .stamp-table { width: 100%; }
        .stamp-table td { width: 33%; text-align: center; font-size: 10pt; padding-top: 50px; }
        .stamp-table td .line { border-top: 1px solid #333; padding-top: 5px; display: inline-block; width: 180px; }
        .badge { display: inline-block; padding: 3px 10px; font-size: 9pt; border-radius: 3px; background: #17a2b8; color: #fff; }
        .badge-success { background: #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SOIL TESTING LABORATORY</h1>
        <h2>Sample Collection Acknowledgement</h2>
        <p>{{ $sample->lab->name ?? 'Soil Testing Lab' }} &mdash; {{ $sample->lab->address ?? '' }}</p>
    </div>

    <div class="ref-row">
        <strong>Acknowledgement No:</strong> ACK-{{ $sample->sample_code }}<br>
        <strong>Date:</strong> {{ $sample->collected_at?->format('d F Y') }}
    </div>

    <div class="section">
        <div class="section-title">Sample Information</div>
        <table class="details">
            <tr><td class="label">Sample Code</td><td class="value">{{ $sample->sample_code }}</td></tr>
            <tr><td class="label">Status</td><td><span class="badge badge-success">{{ $sample->status->label() }}</span></td></tr>
            <tr><td class="label">Collection Date</td><td>{{ $sample->collected_at?->format('d F Y, h:i A') }}</td></tr>
            <tr><td class="label">Laboratory</td><td>{{ $sample->lab->name ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Farmer Details</div>
        <table class="details">
            <tr><td class="label">Name</td><td class="value">{{ $sample->farmer->name ?? '—' }}</td></tr>
            <tr><td class="label">Phone</td><td>{{ $sample->farmer->phone ?? '—' }}</td></tr>
            @if($sample->farmer?->parentage)
            <tr><td class="label">Parentage</td><td>{{ $sample->farmer->parentage }}</td></tr>
            @endif
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
    </div>

    <div class="section">
        <div class="section-title">Plot Details</div>
        <table class="details">
            <tr><td class="label">Plot Name</td><td class="value">{{ $sample->plot->plot_name ?? '—' }}</td></tr>
            @if($sample->plot?->khasra_no)
            <tr><td class="label">Khasra No</td><td>{{ $sample->plot->khasra_no }}</td></tr>
            @endif
            @if($sample->plot?->area_kanals)
            <tr><td class="label">Area (Kanals)</td><td>{{ number_format($sample->plot->area_kanals, 2) }}</td></tr>
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
            @if($sample->plot?->latitude && $sample->plot?->longitude)
            <tr><td class="label">Coordinates</td><td>{{ $sample->plot->latitude }}, {{ $sample->plot->longitude }}</td></tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Collected By</div>
        <table class="details">
            <tr><td class="label">Name</td><td class="value">{{ $sample->collector->name ?? '—' }}</td></tr>
            <tr><td class="label">Date</td><td>{{ $sample->collected_at?->format('d F Y, h:i A') }}</td></tr>
        </table>
    </div>

    <div class="stamp-area">
        <table class="stamp-table">
            <tr>
                <td><div class="line">Collected By</div></td>
                <td><div class="line">Verified By</div></td>
                <td><div class="line">Lab Incharge</div></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>This is a computer-generated acknowledgement. {{ $sample->lab->name ?? '' }} &mdash; {{ $sample->lab->address ?? '' }}</p>
        <p>Phone: {{ $sample->lab->phone ?? '' }} | Email: {{ $sample->lab->email ?? '' }}</p>
    </div>
</body>
</html>
