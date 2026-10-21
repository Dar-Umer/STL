<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Covering Letter - {{ $dispatch_no ?: 'Official Dispatch' }}</title>
    <style>
                @page {
            size: a4 portrait;
            margin: 0;
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: normal;
            src: url('{{ str_replace(DIRECTORY_SEPARATOR, '/', public_path('fonts/Inter-Regular.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            src: url('{{ str_replace(DIRECTORY_SEPARATOR, '/', public_path('fonts/Inter-Medium.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            src: url('{{ str_replace(DIRECTORY_SEPARATOR, '/', public_path('fonts/Inter-SemiBold.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: bold;
            src: url('{{ str_replace(DIRECTORY_SEPARATOR, '/', public_path('fonts/Inter-Bold.ttf')) }}') format('truetype');
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.4;
            background: #ffffff;
            padding: 28px 36px 20px 36px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #047857;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 11pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .header h2 {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 8.5pt;
            color: #475569;
            font-weight: 500;
        }
        .dispatch-bar {
            width: 100%;
            margin-bottom: 14px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 6px;
        }
        .dispatch-bar td {
            font-size: 8.5pt;
        }
        .addressed-to {
            margin-bottom: 12px;
            line-height: 1.45;
            font-size: 8.5pt;
        }
        .addressed-to strong {
            color: #0f172a;
        }
        .subject-box {
            background-color: #f0fdf4;
            border-left: 3.5px solid #059669;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }
        .subject-box strong {
            color: #065f46;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .body-text {
            font-size: 8.5pt;
            color: #334155;
            margin-bottom: 14px;
            text-align: justify;
            line-height: 1.45;
        }
        .section-heading {
            font-size: 9pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }
        table.samples-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.samples-table th {
            background-color: #047857;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #047857;
            text-align: left;
        }
        table.samples-table td {
            font-size: 7.5pt;
            padding: 4.5px 6px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            vertical-align: middle;
        }
        table.samples-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            font-weight: bold;
            color: #065f46;
        }
        .signatures-section {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .signatures-section td {
            vertical-align: top;
        }
        .sig-box {
            padding: 8px 12px;
            line-height: 1.4;
        }
        .receipt-footer-section {
            position: absolute;
            bottom: 22px;
            left: 36px;
            right: 36px;
        }
        .cut-line {
            text-align: center;
            border-top: 1.5px dashed #94a3b8;
            position: relative;
            margin: 8px 0 2px 0;
        }
        .cut-label {
            display: inline-block;
            position: relative;
            top: -9px;
            background: #ffffff;
            padding: 0 10px;
            font-size: 7pt;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cut-label img {
            vertical-align: middle;
            margin-right: 4px;
        }
        .receipt-certificate {
            border: 1.5px dashed #047857;
            background-color: #fcfdfd;
            border-radius: 4px;
            padding: 10px 12px;
            margin-top: 0;
            page-break-inside: avoid;
        }
        .receipt-certificate h4 {
            font-size: 8pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .receipt-certificate p {
            font-size: 8pt;
            color: #334155;
            line-height: 1.5;
            margin-bottom: 8px;
        }
        .receipt-grid {
            width: 100%;
            margin-top: 10px;
        }
        .receipt-grid td {
            font-size: 7.5pt;
            color: #334155;
            padding-top: 4px;
        }
        .footer-note {
            font-size: 6.5pt;
            color: #94a3b8;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    {{-- Official Header --}}
    <div class="header">
        <h1>Government of Jammu & Kashmir &bull; Department of Horticulture</h1>
        <h2>{{ $header_title }}</h2>
        <p>Zone: <strong>{{ $zone_name }}</strong> @if($district_name) &bull; District: <strong>{{ $district_name }}</strong> @endif &bull; UT of Jammu & Kashmir</p>
    </div>

    {{-- Dispatch No & Date --}}
    <table class="dispatch-bar">
        <tr>
            <td style="text-align: left; width: 60%;">
                <strong>Dispatch No:</strong>
                @if(!empty(trim($dispatch_no ?? '')))
                    <span style="font-weight: bold;">{{ $dispatch_no }}</span>
                @else
                    <span style="letter-spacing: 2px; color: #64748b;">____________________________________</span>
                @endif
            </td>
            <td style="text-align: right; width: 40%;">
                <strong>Date:</strong>
                @if(!empty(trim($date ?? '')))
                    <span style="font-weight: 500;">{{ $date }}</span>
                @else
                    <span style="letter-spacing: 2px; color: #64748b;">________________________</span>
                @endif
            </td>
        </tr>
    </table>

    {{-- Addressed To --}}
    <div class="addressed-to">
        <strong>To,</strong><br>
        {!! nl2br(e($addressed_to)) !!}
    </div>

    {{-- Subject --}}
    <div class="subject-box">
        <strong>Subject:</strong> {{ $subject }}
    </div>

    {{-- Body Text --}}
    <div class="body-text">
        {!! nl2br(e($body_text)) !!}
    </div>

    {{-- Table of Samples --}}
    <table class="samples-table">
        <thead>
            <tr>
                <th style="width: 32px; text-align: center;">Sno</th>
                <th style="width: 110px;">Sample ID</th>
                <th>Name of Farmer</th>
                <th style="width: 120px;">Parentage</th>
                <th>Residence</th>
                <th style="width: 115px;">QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($samples as $index => $sample)
            @php
                $villageName = $sample->farmer->village->name ?? $sample->plot->village->name ?? null;
                $panchayatName = $sample->farmer->panchayat->name ?? $sample->plot->panchayat->name ?? null;
                $residenceParts = array_unique(array_filter([$villageName, $panchayatName]));
                $residence = !empty($residenceParts) ? implode(', ', $residenceParts) : '—';
                $qrCodeText = $sample->qrCode->code ?? $sample->sample_code;
            @endphp
            <tr>
                <td style="text-align: center; font-weight: bold; color: #475569;">{{ $index + 1 }}</td>
                <td><span class="badge">{{ $sample->sample_code }}</span></td>
                <td><strong>{{ $sample->farmer->name ?? '—' }}</strong></td>
                <td>{{ $sample->farmer->parentage ?? '—' }}</td>
                <td>{{ $residence }}</td>
                <td style="font-size: 7.5pt; color: #065f46; font-weight: bold;">{{ $qrCodeText }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-heading" style="margin-top: -8px; margin-bottom: 6px; font-size: 8.5pt;">
        Schedule of Enclosed Soil Samples (Total: {{ $total_samples }})
    </div>

    {{-- Official Signatures Section --}}
    <table class="signatures-section">
        <tr>
            <td style="width: 50%; text-align: left;">
                <div class="sig-box" style="text-align: left;">
                    <div style="height: 25px;"></div>
                    <p style="font-size: 8.5pt; line-height: 1.35; color: #0f172a;">
                        <strong>Sig of Collector</strong><br>
                        {{ $officer_name ?: (auth()->user()?->name ?? 'Field Official') }}
                    </p>
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="sig-box" style="text-align: right;">
                    <div style="height: 25px;"></div>
                    @php
                        $displayHdo = trim($hdo_title ?? '');
                        if (empty($displayHdo)) {
                            $displayHdo = "Horticulture Development Officer (HDO)\n" . $zone_name;
                        } elseif (strpos($displayHdo, "\n") === false && strpos($displayHdo, ',') !== false) {
                            $displayHdo = str_replace(',', "\n", $displayHdo);
                        }
                    @endphp
                    <p style="font-size: 8.5pt; line-height: 1.35; color: #0f172a;"><strong>{!! nl2br(e($displayHdo)) !!}</strong></p>
                </div>
            </td>
        </tr>
    </table>

    {{-- Laboratory Acknowledgement & Physical Receipt Certificate (Footer Section) --}}
    <div class="receipt-footer-section">
        <div class="cut-line">
            <span class="cut-label"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAA8ElEQVR4nO1X2w3DIAzEVobzBB2iA3WITtDtqPhAItSxjXFKK8VSFIWgO3P4AZBzTisNl7Kny4GUNk4Wut0/AuP1fMBXtoAYcmk81AFSSM5wAo/Ai+T1iXaixUBuQk8auf+VvL63KGArcT+GUWCe+UVZqKWYiwELobY9WkpD2wu8AcY5Ia26/UYNqP9vIbOSF9spIAHMpKO0MJhpxxYnNFVh9jwwIvf/KEArY4AMOa856M4CWlAH4GcqITUTLSsa6ZCSGmgF8ZJL893d0GNcGT9UwFrbvY60bxjNgujTMY6An3E0RyvJWfcCuC6nabG9AbY42/i3lVgmAAAAAElFTkSuQmCC" width="12" height="12"> Cut / Detach Along This Line (For Zone Records)</span>
        </div>

        <div class="receipt-certificate">
            <h4>Physical Samples Receipt Certificate (For Zone Record)</h4>
            <p>
                Certified that <strong>{{ $total_samples }}</strong> physical soil samples in sealed bags corresponding to the barcodes listed in this dispatch letter with dispatch number <strong>{{ !empty(trim($dispatch_no ?? '')) ? $dispatch_no : '__________________________' }}</strong> dated <strong>{{ !empty(trim($date ?? '')) ? $date : '____________' }}</strong> have been physically received, verified, and entered into the laboratory testing workflow.
            </p>
            <table class="receipt-grid">
                <tr>
                    <td style="width: 50%; text-align: left;"><strong>Date Received:</strong> _____ / _____ / 202___</td>
                    <td style="width: 50%; text-align: right;"><strong>Receiving Official Signature:</strong> _____________________</td>
                </tr>
            </table>
        </div>

        <div class="footer-note" style="margin-top: 6px;">
            Generated electronically via Soil Lab Field Automation Portal &bull; Government of Jammu & Kashmir
        </div>
    </div>

</body>
</html>
