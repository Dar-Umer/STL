<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Technician Report</title>
    <style>
        @page { margin: 6mm 6mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 7.5pt; color: #2d2d2d; }
        .header { border-bottom: 2px solid #28a745; padding-bottom: 4px; margin-bottom: 6px; }
        .header h1 { margin: 0; font-size: 13pt; color: #28a745; }
        .header .sub { font-size: 7pt; color: #777; }
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
        <h1>TECHNICIAN REPORT</h1>
        <div class="sub">Generated on {{ now()->format('d F Y h:i A') }} @if(isset($dateFrom)) | Period: {{ $dateFrom }} - {{ $dateTo }} @endif</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Lab</th>
                <th class="text-right">Total</th>
                <th class="text-right">Received</th>
                <th class="text-right">Testing</th>
                <th class="text-right">Reported</th>
                <th>Last Analysis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $tech)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $tech->name }}</td>
                    <td>{{ $tech->email }}</td>
                    <td>{{ $tech->lab->name ?? '—' }}</td>
                    <td class="text-right">{{ number_format($tech->total_analyzed) }}</td>
                    <td class="text-right">{{ number_format($tech->received_count) }}</td>
                    <td class="text-right">{{ number_format($tech->testing_count) }}</td>
                    <td class="text-right">{{ number_format($tech->reported_count) }}</td>
                    <td>{{ $tech->last_analysis ? \Carbon\Carbon::parse($tech->last_analysis)->format('d/m/Y') : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center" style="color:#999;padding:10px;">No data available.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">This report is computer-generated from the Soil Testing Laboratory Management System.</div>
</body>
</html>
