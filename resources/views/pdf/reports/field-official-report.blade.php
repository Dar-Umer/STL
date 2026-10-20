<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Field Official Collection Performance Report</title>
    <style>
        @page { margin: 8mm 8mm; size: a4 landscape; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif; }
        body { font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif; font-size: 7.5pt; color: #1e293b; line-height: 1.3; background: #ffffff; }
        
        .report-header {
            border-bottom: 2px solid #059669;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .header-table { width: 100%; border-collapse: collapse; }
        .dept-title { font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .report-title { font-size: 13pt; font-weight: bold; color: #065f46; letter-spacing: 0.5px; margin-top: 1px; }
        .report-sub { font-size: 6.8pt; color: #64748b; margin-top: 2px; }
        .meta-pill { display: inline-block; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 7pt; }

        table.data-table { width: 100%; border-collapse: collapse; font-size: 7pt; margin-top: 6px; }
        table.data-table th { background: #059669; color: #ffffff; padding: 4px 6px; text-align: left; font-size: 6.5pt; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #047857; }
        table.data-table th.r, table.data-table td.r { text-align: right; }
        table.data-table th.c, table.data-table td.c { text-align: center; }
        table.data-table td { padding: 3px 6px; border: 1px solid #e2e8f0; vertical-align: middle; }
        table.data-table tr:nth-child(even) td { background: #f8fafc; }
        
        .footer { font-size: 6pt; color: #94a3b8; text-align: center; border-top: 0.5px solid #e2e8f0; padding-top: 4px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="report-header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="dept-title">Government of Jammu &amp; Kashmir &bull; Department of Horticulture &amp; Agriculture</div>
                    <div class="report-title">FIELD OFFICIAL COLLECTION &amp; ACTIVITY REPORT</div>
                    <div class="report-sub">Field Extension Operatives Activity Registry @if(isset($dateFrom)) &bull; Period: <strong>{{ $dateFrom }}</strong> to <strong>{{ $dateTo }}</strong> @endif</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span class="meta-pill">Generated: {{ now()->format('d M Y, h:i A') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="c" style="width: 24px;">#</th>
                <th>Official Name</th>
                <th>Contact Email</th>
                <th>Assigned Zone</th>
                <th class="r">Total Collected</th>
                <th class="r">In Transit</th>
                <th class="r">Received at Lab</th>
                <th class="r">In Testing</th>
                <th class="r">Reported</th>
                <th class="c" style="width: 85px;">Last Activity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $official)
                <tr>
                    <td class="c" style="font-weight: bold; color: #64748b;">{{ $i + 1 }}</td>
                    <td style="font-weight: bold; color: #0f172a;">{{ $official->name }}</td>
                    <td>{{ $official->email }}</td>
                    <td>{{ $official->zone->name ?? '—' }}</td>
                    <td class="r" style="font-weight: bold;">{{ number_format($official->total_collected) }}</td>
                    <td class="r">{{ number_format($official->collected_count) }}</td>
                    <td class="r">{{ number_format($official->received_count) }}</td>
                    <td class="r" style="color: #d97706; font-weight: bold;">{{ number_format($official->testing_count) }}</td>
                    <td class="r" style="color: #059669; font-weight: bold;">{{ number_format($official->reported_count) }}</td>
                    <td class="c">{{ $official->last_collection ? \Carbon\Carbon::parse($official->last_collection)->format('d M Y') : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="c" style="color:#94a3b8; padding: 14px;">No field official collection activity recorded for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Soil Health &amp; Laboratory Operations Management Portal &bull; Official Government Audit Document &bull; Generated: {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
