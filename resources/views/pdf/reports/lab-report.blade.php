<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lab-wise Report</title>
    <style>
        @page { margin: 6mm 6mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 7.5pt; color: #2d2d2d; }
        .header { border-bottom: 2px solid #28a745; padding-bottom: 4px; margin-bottom: 6px; }
        .header h1 { margin: 0; font-size: 13pt; color: #28a745; }
        .header .sub { font-size: 7pt; color: #777; }
        .section-title { font-size: 8pt; font-weight: bold; color: #fff; background: #28a745; padding: 3px 6px; margin-bottom: 4px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; font-size: 7pt; }
        th { background: #28a745; color: #fff; padding: 3px 4px; text-align: left; font-size: 6.5pt; }
        td { padding: 2px 4px; border-bottom: 0.5px solid #e8e8e8; }
        tr:nth-child(even) td { background: #fafafa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { font-size: 6pt; color: #999; text-align: center; border-top: 0.5px solid #ddd; padding-top: 3px; margin-top: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAB-WISE REPORT</h1>
        <div class="sub">Generated on {{ now()->format('d F Y h:i A') }} @if(isset($dateFrom)) | Period: {{ $dateFrom }} - {{ $dateTo }} @endif</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Lab</th>
                <th>Code</th>
                <th class="text-right">Total</th>
                <th class="text-right">Field</th>
                <th class="text-right">Self</th>
                <th class="text-right">Collected</th>
                <th class="text-right">Received</th>
                <th class="text-right">Testing</th>
                <th class="text-right">Reported</th>
                <th class="text-right">Farmers</th>
                <th class="text-right">Users</th>
                <th class="text-right">Rate %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $lab)
                @php $r = $lab->soil_samples_count > 0 ? round(($lab->reported_count / $lab->soil_samples_count) * 100, 1) : 0; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $lab->name }}</td>
                    <td>{{ $lab->code }}</td>
                    <td class="text-right">{{ number_format($lab->soil_samples_count) }}</td>
                    <td class="text-right">{{ number_format($lab->soil_samples_count - ($lab->self_samples_count ?? 0)) }}</td>
                    <td class="text-right">{{ number_format($lab->self_samples_count ?? 0) }}</td>
                    <td class="text-right">{{ number_format($lab->collected_count) }}</td>
                    <td class="text-right">{{ number_format($lab->received_count) }}</td>
                    <td class="text-right">{{ number_format($lab->testing_count) }}</td>
                    <td class="text-right">{{ number_format($lab->reported_count) }}</td>
                    <td class="text-right">{{ number_format($lab->farmers_count) }}</td>
                    <td class="text-right">{{ number_format($lab->users_count) }}</td>
                    <td class="text-right">{{ $r }}%</td>
                </tr>
            @empty
                <tr><td colspan="12" class="text-center" style="color:#999;padding:10px;">No data available.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">This report is computer-generated from the Soil Testing Laboratory Management System.</div>
</body>
</html>
