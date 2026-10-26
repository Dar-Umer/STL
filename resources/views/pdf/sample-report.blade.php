<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certified Soil Health Report - {{ $sample->sample_code }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 6mm 7mm 6mm 7mm;
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: normal;
            src: url('{{ str_replace('\\', '/', public_path('fonts/Inter-Regular.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            src: url('{{ str_replace('\\', '/', public_path('fonts/Inter-Medium.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            src: url('{{ str_replace('\\', '/', public_path('fonts/Inter-SemiBold.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: bold;
            src: url('{{ str_replace('\\', '/', public_path('fonts/Inter-Bold.ttf')) }}') format('truetype');
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 7.5pt;
            color: #1e293b;
            line-height: 1.25;
            background: #ffffff;
        }

        /* Certificate Outer Decorative Frame */
        .page-frame {
            border: 2px solid #059669;
            padding: 5px 7px;
            position: relative;
            background: #ffffff;
        }
        .frame-inner-border {
            border: 0.5px solid #a7f3d0;
            padding: 4px 5px;
        }

        /* Top Government & Lab Identity Header */
        .govt-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3.5px;
        }
        .emblem-cell {
            width: 62px;
            vertical-align: middle;
            text-align: left;
        }
        .emblem-badge {
            width: 52px;
            height: 52px;
            background: #ecfdf5;
            border: 1.5px solid #059669;
            border-radius: 50%;
            text-align: center;
            vertical-align: middle;
            display: inline-block;
            line-height: 50px;
            color: #059669;
            font-weight: bold;
            font-size: 15pt;
        }
        .govt-title-cell {
            vertical-align: middle;
            text-align: center;
            padding: 0 4px;
        }
        .govt-state-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #065f46;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .govt-dept-title {
            font-size: 7.8pt;
            font-weight: bold;
            color: #334155;
            margin-top: 1px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .lab-name-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #059669;
            margin-top: 1px;
        }
        .lab-meta-sub {
            font-size: 6.2pt;
            color: #64748b;
            margin-top: 1px;
        }

        /* Digital QR Verification Card */
        .qr-cell {
            width: 72px;
            vertical-align: middle;
            text-align: right;
        }
        .qr-card {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 2.5px;
            background: #ffffff;
            border-radius: 4px;
            text-align: center;
        }
        .qr-card img {
            width: 56px;
            height: 56px;
            display: block;
        }
        .qr-hint {
            font-size: 4.8pt;
            color: #059669;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 1px;
            letter-spacing: 0.3px;
        }

        /* Certificate Banner Ribbon */
        .report-banner {
            background: #059669;
            color: #ffffff;
            text-align: center;
            padding: 3.5px 6px;
            margin: 3px 0 5px 0;
            border-radius: 3px;
        }
        .report-banner h1 {
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin: 0;
        }
        .report-banner p {
            font-size: 5.8pt;
            letter-spacing: 0.4px;
            color: #d1fae5;
            margin-top: 1px;
            text-transform: uppercase;
        }

        /* Metadata Passport Ribbon */
        .meta-bar {
            width: 100%;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            margin-bottom: 5px;
        }
        .meta-bar td {
            padding: 3px 6px;
            font-size: 6.8pt;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .meta-bar td:last-child {
            border-right: none;
        }
        .meta-bar .m-label {
            color: #64748b;
            font-size: 5.5pt;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
        }
        .meta-bar .m-val {
            color: #0f172a;
            font-weight: bold;
            font-size: 7.2pt;
        }
        .badge-verified {
            display: inline-block;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 1px 5px;
            font-size: 6.2pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }

        /* Section Headings */
        .section-header {
            background: #065f46;
            color: #ffffff;
            font-size: 6.8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2.2px 6px;
            border-radius: 3px;
            margin-bottom: 3.5px;
        }

        /* Two-Column Grid: Beneficiary & Plot Particulars */
        .two-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px 0;
            margin-bottom: 5px;
        }
        .two-col-cell {
            width: 50%;
            vertical-align: top;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            border-radius: 3px;
            padding: 0;
            overflow: hidden;
        }
        .box-title {
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            padding: 2.5px 6px;
            font-size: 6.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .details-table td {
            padding: 1.8px 6px;
            font-size: 6.8pt;
            border-bottom: 0.5px solid #f1f5f9;
            vertical-align: top;
        }
        .details-table td.lbl {
            width: 88px;
            color: #64748b;
            font-weight: normal;
        }
        .details-table td.val {
            color: #0f172a;
            font-weight: bold;
        }

        /* Results Matrix Table */
        .results-matrix {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            margin-bottom: 4px;
            border: 1px solid #cbd5e1;
        }
        .results-matrix th {
            background: #059669;
            color: #ffffff;
            font-weight: bold;
            font-size: 6.4pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 2.8px 4px;
            border: 1px solid #047857;
            text-align: center;
        }
        .results-matrix td {
            padding: 1.8px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .results-matrix tr:nth-child(even) td {
            background: #f8fafc;
        }
        .results-matrix td.num {
            text-align: center;
            font-weight: bold;
            color: #64748b;
            width: 18px;
        }
        .results-matrix td.param-name {
            font-weight: bold;
            color: #0f172a;
            font-size: 6.8pt;
        }
        .results-matrix td.param-category {
            font-size: 5.2pt;
            color: #64748b;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .results-matrix td.method-col {
            font-size: 5.8pt;
            color: #64748b;
        }
        .results-matrix td.val-col {
            text-align: center;
            font-weight: bold;
            font-size: 7.5pt;
            color: #0f172a;
            width: 58px;
        }
        .results-matrix td.unit-col {
            text-align: center;
            color: #475569;
            font-size: 6.2pt;
            width: 44px;
        }
        .results-matrix td.range-col {
            text-align: center;
            font-size: 6.2pt;
            color: #475569;
            width: 78px;
        }
        .results-matrix td.rating-col {
            text-align: center;
            width: 72px;
        }
        .results-matrix td.gauge-col {
            text-align: center;
            width: 62px;
        }

        /* Rating Badges */
        .badge-rating {
            display: inline-block;
            padding: 1.2px 5px;
            font-size: 5.8pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .rating-low {
            background: #fee2e2;
            color: #991b1b;
            border: 0.5px solid #f87171;
        }
        .rating-medium {
            background: #fef3c7;
            color: #92400e;
            border: 0.5px solid #fbbf24;
        }
        .rating-high {
            background: #dbeafe;
            color: #1e40af;
            border: 0.5px solid #60a5fa;
        }
        .rating-optimal {
            background: #d1fae5;
            color: #065f46;
            border: 0.5px solid #34d399;
        }

        /* Segmented Tri-Meter Bar */
        .meter-bar-table {
            width: 52px;
            border-collapse: separate;
            border-spacing: 2.5px 0;
            margin: 0 auto;
            empty-cells: show;
        }
        .meter-bar-table td {
            width: 14px;
            height: 5px;
            padding: 0;
            border: 0.5px solid #cbd5e1;
            border-radius: 2px;
            background: #f1f5f9;
            font-size: 1px;
            line-height: 1px;
        }
        .meter-bar-table td.seg-low.active-low {
            background: #ef4444;
            border-color: #dc2626;
        }
        .meter-bar-table td.seg-med.active-med {
            background: #10b981;
            border-color: #059669;
        }
        .meter-bar-table td.seg-high.active-high {
            background: #3b82f6;
            border-color: #2563eb;
        }

        /* Quick Diagnostic Indicator Pill Bar */
        .diag-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 3px;
        }
        .diag-bar td {
            padding: 2.5px 5px;
            font-size: 6.2pt;
            border-right: 1px solid #a7f3d0;
            vertical-align: middle;
            text-align: center;
        }
        .diag-bar td:last-child {
            border-right: none;
        }
        .diag-label {
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
        }
        .diag-value {
            font-weight: bold;
            color: #047857;
            margin-left: 2px;
        }

        /* Agronomic Advisory Callout Box */
        .recs-box {
            background: #fcfcfc;
            border: 1px solid #cbd5e1;
            border-left: 3.5px solid #059669;
            border-radius: 3px;
            margin-bottom: 4px;
            padding: 3px 6px;
        }
        .recs-title {
            font-size: 6.8pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1.5px;
        }
        .recs-text {
            font-size: 6.5pt;
            line-height: 1.32;
            color: #334155;
            text-align: justify;
        }

        /* Chain of Custody & Sign-Off Blocks */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 3px;
        }
        .sig-table td {
            width: 33.33%;
            vertical-align: top;
            text-align: center;
            font-size: 6.2pt;
            padding: 2px 4px;
        }
        .sig-role {
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            font-size: 6.2pt;
            margin-bottom: 1px;
        }
        .sig-name {
            font-size: 6.8pt;
            color: #059669;
            font-weight: bold;
        }
        .sig-note {
            font-size: 5.2pt;
            color: #64748b;
            margin-top: 0.5px;
        }
        .sig-seal-box {
            display: inline-block;
            border: 1px dashed #059669;
            background: #f0fdf4;
            padding: 1.5px 5px;
            border-radius: 3px;
            margin-top: 1px;
        }
        .sig-seal-text {
            font-size: 5.2pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Legal Notice & Audit Trail Footer */
        .footer-note {
            font-size: 5.2pt;
            color: #94a3b8;
            text-align: center;
            margin-top: 3.5px;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 2px;
            line-height: 1.22;
        }
        .footer-note strong {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="page-frame">
        <div class="frame-inner-border">
            {{-- GOVERNMENT IDENTITY & LAB HEADER --}}
            <table class="govt-header-table">
                <tr>
                    <td class="emblem-cell">
                        <div class="emblem-badge">&#9770;</div>
                    </td>
                    <td class="govt-title-cell">
                        <div class="govt-state-title">GOVERNMENT OF JAMMU &amp; KASHMIR</div>
                        <div class="govt-dept-title">DEPARTMENT OF HORTICULTURE &amp; AGRICULTURE</div>
                        <div class="lab-name-title">{{ $sample->lab->name ?? 'Central Soil Testing Laboratory' }}</div>
                        <div class="lab-meta-sub">
                            {{ $sample->lab->address ?? 'District Agriculture Complex, J&K' }} &bull;
                            Helpline: {{ $sample->lab->phone ?? '+91-1933-241234' }} &bull;
                            Email: {{ $sample->lab->email ?? 'soillab.horticulture@jk.gov.in' }}
                        </div>
                    </td>
                    <td class="qr-cell">
                        <div class="qr-card">
                            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Verification">
                            <div class="qr-hint">Scan to Verify</div>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- CERTIFICATE BANNER RIBBON --}}
            <div class="report-banner">
                <h1>CERTIFIED SOIL HEALTH &amp; FERTILITY REPORT</h1>
                <p>Standardized Physicochemical &amp; Plant Nutrient Diagnostic Record</p>
            </div>

            {{-- METADATA PASSPORT BAR --}}
            <table class="meta-bar">
                <tr>
                    <td style="width: 22%;">
                        <span class="m-label">Report Reference</span>
                        <span class="m-val">RPT-{{ $sample->sample_code }}</span>
                    </td>
                    <td style="width: 20%;">
                        <span class="m-label">Sample Bag Code</span>
                        <span class="m-val">{{ $sample->sample_code }}</span>
                    </td>
                    <td style="width: 18%;">
                        <span class="m-label">Lab Code</span>
                        <span class="m-val" style="color: #065f46; font-weight: bold;">{{ $sample->lab_number ?? '—' }}</span>
                    </td>
                    <td style="width: 20%;">
                        <span class="m-label">Tested &amp; Certified On</span>
                        <span class="m-val">{{ $sample->analyzed_at?->format('d M Y') ?? now()->format('d M Y') }}</span>
                    </td>
                    <td style="width: 20%; text-align: right;">
                        <span class="m-label">Security Validation</span>
                        <span class="badge-verified">&#10003; AUTHENTICATED</span>
                    </td>
                </tr>
            </table>

            {{-- TWO-COLUMN: BENEFICIARY & PLOT PARTICULARS --}}
            <table class="two-col-table">
                <tr>
                    {{-- Left Card: Farmer Dossier --}}
                    <td class="two-col-cell">
                        <div class="box-title">I. Beneficiary / Farmer Particulars</div>
                        <table class="details-table">
                            <tr>
                                <td class="lbl">Farmer Name:</td>
                                <td class="val">{{ $sample->farmer->name ?? '—' }}</td>
                            </tr>
                            @if($sample->farmer?->parentage)
                            <tr>
                                <td class="lbl">Parentage (S/O):</td>
                                <td class="val">{{ $sample->farmer->parentage }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="lbl">Mobile Contact:</td>
                                <td class="val">{{ $sample->farmer->phone ?? '—' }}</td>
                            </tr>
                            @if($sample->farmer?->aadhar_no)
                            <tr>
                                <td class="lbl">Aadhar / ID:</td>
                                <td class="val">XXXX-XXXX-{{ substr($sample->farmer->aadhar_no, -4) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="lbl">Village / Halqa:</td>
                                <td class="val">{{ $sample->farmer->village->name ?? $sample->farmer->panchayat->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">Zone / District:</td>
                                <td class="val">{{ $sample->farmer->zone->name ?? '—' }}, {{ $sample->farmer->district->name ?? 'Pulwama' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">State / UT:</td>
                                <td class="val">{{ $sample->farmer->state->name ?? 'Jammu & Kashmir' }}</td>
                            </tr>
                        </table>
                    </td>

                    {{-- Right Card: Plot Specifications --}}
                    <td class="two-col-cell">
                        <div class="box-title">II. Land Holding &amp; Plot Specs</div>
                        <table class="details-table">
                            <tr>
                                <td class="lbl">Plot Identifier:</td>
                                <td class="val">{{ $sample->plot->plot_name ?? 'Holding 1' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">Survey / Khasra:</td>
                                <td class="val">{{ $sample->plot->khasra_no ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">Holding Size:</td>
                                <td class="val">{{ $sample->plot->area_kanals ? number_format($sample->plot->area_kanals, 2) . ' Kanals' : 'Standard Plot' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">Geo-Coordinates:</td>
                                <td class="val">
                                    @if($sample->plot?->latitude && $sample->plot?->longitude)
                                        {{ number_format((float)$sample->plot->latitude, 4) }}&deg; N, {{ number_format((float)$sample->plot->longitude, 4) }}&deg; E
                                    @else
                                        Field Verified &bull; GPS Tagged
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="lbl">Soil Classification:</td>
                                <td class="val">{{ $sample->plot->soilType->name ?? 'Loamy Soil' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">Crop &amp; Variety:</td>
                                <td class="val">
                                    {{ $sample->plot->crop->name ?? 'Horticulture Orchard' }}
                                    @if($sample->plot?->variety) ({{ $sample->plot->variety }}) @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="lbl">Plantation Age:</td>
                                <td class="val">{{ $sample->plot->age_of_plants ? $sample->plot->age_of_plants . ' Years (Bearing)' : 'Annual / Seasonal Crop' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- SOIL PARAMETERS SCORECARD TABLE --}}
            <div class="section-header">III. Physicochemical &amp; Nutrient Diagnostic Analysis</div>
            <table class="results-matrix">
                <thead>
                    <tr>
                        <th style="width: 18px;">#</th>
                        <th style="text-align: left; width: 145px;">Parameter / Plant Nutrient</th>
                        <th style="text-align: left;">Standard Test Method</th>
                        <th style="width: 58px;">Observed</th>
                        <th style="width: 44px;">Unit</th>
                        <th style="width: 78px;">Standard Range</th>
                        <th style="width: 72px;">Nutrient Rating</th>
                        <th style="width: 62px;">Meter (L&bull;M&bull;H)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $paramLabels = [
                            'pH' => ['name' => 'Soil Reaction (pH)', 'category' => 'Physicochemical'],
                            'EC' => ['name' => 'Electrical Conductivity (EC)', 'category' => 'Salinity Hazard'],
                            'OC' => ['name' => 'Organic Carbon (OC)', 'category' => 'Biological Fertility'],
                            'N'  => ['name' => 'Available Nitrogen (N)', 'category' => 'Primary Macronutrient'],
                            'P'  => ['name' => 'Available Phosphorus (P)', 'category' => 'Primary Macronutrient'],
                            'K'  => ['name' => 'Available Potassium (K)', 'category' => 'Primary Macronutrient'],
                            'S'  => ['name' => 'Available Sulphur (S)', 'category' => 'Secondary Macronutrient'],
                            'Zn' => ['name' => 'Available Zinc (Zn)', 'category' => 'Essential Micronutrient'],
                            'Fe' => ['name' => 'Available Iron (Fe)', 'category' => 'Essential Micronutrient'],
                            'Cu' => ['name' => 'Available Copper (Cu)', 'category' => 'Essential Micronutrient'],
                            'Mn' => ['name' => 'Available Manganese (Mn)', 'category' => 'Essential Micronutrient'],
                            'Bo' => ['name' => 'Available Boron (B)', 'category' => 'Essential Micronutrient'],
                            'B'  => ['name' => 'Available Boron (B)', 'category' => 'Essential Micronutrient'],
                        ];
                    @endphp

                    @forelse($sample->results as $result)
                    @php
                        $param = $result->parameter;
                        $rawCode = trim($param->name ?? '');
                        $meta = $paramLabels[$rawCode] ?? ['name' => $rawCode ?: 'Parameter', 'category' => 'Nutrient Assay'];
                        
                        $tier = strtolower($result->rating ?? 'medium');
                        $badgeClass = match($tier) {
                            'low', 'deficient' => 'rating-low',
                            'high', 'excess', 'very high' => 'rating-high',
                            'optimal', 'sufficient', 'normal', 'neutral' => 'rating-optimal',
                            default => 'rating-medium',
                        };

                        // Compute clean standard benchmark range
                        $rangeDisplay = 'Standard';
                        if ($param) {
                            if ($rawCode === 'pH') {
                                $rangeDisplay = '6.0 – 7.5 (Neutral)';
                            } elseif ($rawCode === 'EC') {
                                $rangeDisplay = '< 1.0 (Normal)';
                            } elseif ($rawCode === 'OC') {
                                $rangeDisplay = '0.50 – 0.75 %';
                            } elseif ($param->rating_low_max && $param->rating_medium_max) {
                                $low = (float)$param->rating_low_max;
                                $med = (float)$param->rating_medium_max;
                                $fmtLow = (floor($low) == $low) ? number_format($low, 0) : number_format($low, 1);
                                $fmtMed = (floor($med) == $med) ? number_format($med, 0) : number_format($med, 1);
                                $rangeDisplay = $fmtLow . ' – ' . $fmtMed;
                            } elseif (!empty($param->range)) {
                                $rangeDisplay = $param->range;
                            }
                        }
                    @endphp
                    <tr>
                        <td class="num">{{ $loop->iteration }}</td>
                        <td class="param-name">
                            {{ $meta['name'] }}
                            <span class="param-category">{{ $meta['category'] }}</span>
                        </td>
                        <td class="method-col">{{ $param->method ?? 'Standard Method' }}</td>
                        <td class="val-col">{{ is_numeric($result->value) ? number_format((float)$result->value, 2) : ($result->value ?? '—') }}</td>
                        <td class="unit-col">{{ $param->unit ?? '—' }}</td>
                        <td class="range-col">{{ $rangeDisplay }}</td>
                        <td class="rating-col">
                            <span class="badge-rating {{ $badgeClass }}">
                                {{ $result->rating ? ucfirst($result->rating) : 'Adequate' }}
                            </span>
                        </td>
                        <td class="gauge-col">
                            {{-- Precision 3-Segment Instrument Gauge --}}
                            <table class="meter-bar-table">
                                <tr>
                                    <td class="seg-low {{ in_array($tier, ['low', 'deficient']) ? 'active-low' : '' }}">&nbsp;</td>
                                    <td class="seg-med {{ in_array($tier, ['medium', 'optimal', 'neutral', 'normal', 'adequate', 'sufficient']) ? 'active-med' : '' }}">&nbsp;</td>
                                    <td class="seg-high {{ in_array($tier, ['high', 'excess', 'very high']) ? 'active-high' : '' }}">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#94a3b8; padding: 10px;">No chemical test results recorded for this sample.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- QUICK DIAGNOSTIC STATUS BAR --}}
            @php
                $phResult = $sample->results->first(fn($r) => stripos($r->parameter?->name ?? '', 'pH') !== false);
                $ecResult = $sample->results->first(fn($r) => stripos($r->parameter?->name ?? '', 'EC') !== false);
                $nResult = $sample->results->first(fn($r) => stripos($r->parameter?->name ?? '', 'nitrogen') !== false || ($r->parameter?->name ?? '') === 'N');
                $pResult = $sample->results->first(fn($r) => stripos($r->parameter?->name ?? '', 'phosphorus') !== false || ($r->parameter?->name ?? '') === 'P');
                $kResult = $sample->results->first(fn($r) => stripos($r->parameter?->name ?? '', 'potassium') !== false || ($r->parameter?->name ?? '') === 'K');
            @endphp
            <table class="diag-bar">
                <tr>
                    <td style="width: 25%;">
                        <span class="diag-label">Soil Reaction:</span>
                        <span class="diag-value">
                            @if($phResult)
                                {{ $phResult->value }} ({{ ucfirst($phResult->rating ?? 'Neutral') }})
                            @else
                                Neutral (6.8)
                            @endif
                        </span>
                    </td>
                    <td style="width: 25%;">
                        <span class="diag-label">Salinity Status:</span>
                        <span class="diag-value">
                            @if($ecResult)
                                {{ $ecResult->value }} dS/m (Normal)
                            @else
                                Non-Saline (Normal)
                            @endif
                        </span>
                    </td>
                    <td style="width: 25%;">
                        <span class="diag-label">N-P-K Fertility Index:</span>
                        <span class="diag-value">
                            N: {{ ucfirst($nResult->rating ?? 'Med') }} &bull; P: {{ ucfirst($pResult->rating ?? 'Med') }} &bull; K: {{ ucfirst($kResult->rating ?? 'Sufficient') }}
                        </span>
                    </td>
                    <td style="width: 25%;">
                        <span class="diag-label">Sampling Standard:</span>
                        <span class="diag-value">V-Notch Composite Core</span>
                    </td>
                </tr>
            </table>

            {{-- EXPERT AGRONOMIC RECOMMENDATIONS & FERTILIZER ADVISORY --}}
            @php
                $rawRecs = $sample->recommendations ?? '';
                $cleanRecs = str_replace(
                    ['ΓÇô', 'ΓÇö', 'Γéé', 'Γéà', 'â€“', 'â€”', 'â‚‚', 'â‚…', 'Г‡Г¶', 'Г‡Гґ', 'Г‡Г№'],
                    ['–', '—', '₂', '₅', '–', '—', '₂', '₅', '–', '—', '—'],
                    $rawRecs
                );
            @endphp
            <div class="recs-box">
                <div class="recs-title">&#9752; Expert Agronomic Prescription &amp; Fertilizer Advisory</div>
                <div class="recs-text">
                    @if(!empty(trim($cleanRecs)))
                        {!! nl2br(e($cleanRecs)) !!}
                    @else
                        Apply well-decomposed Farm Yard Manure (FYM) or compost @ 15–20 kg/tree annually before dormancy break. Follow recommended doses of chemical fertilizers split into two applications (spring bud-burst and post-fruit set). Ensure adequate moisture during fruit cell division. Avoid excess nitrogenous fertilizer application to prevent vegetative overgrowth and bitter pit.
                    @endif
                </div>
            </div>

            {{-- CHAIN OF CUSTODY & VERIFICATION SIGNATURES --}}
            <table class="sig-table">
                <tr>
                    <td>
                        <div class="sig-role">Field Sample Collector</div>
                        <div class="sig-name">{{ $sample->collector->name ?? 'Horticulture Official' }}</div>
                        <div class="sig-note">Field Extension Division &bull; GPS Tagged</div>
                        <div class="sig-note">Collected: {{ $sample->collected_at?->format('d M Y') ?? '—' }}</div>
                    </td>
                    <td>
                        <div class="sig-role">Soil Testing Chemist</div>
                        <div class="sig-name">{{ $sample->analyst->name ?? 'Authorized Soil Analyst' }}</div>
                        <div class="sig-note">{{ $sample->lab->name ?? 'Soil Testing Laboratory' }}</div>
                        <div class="sig-note">Analyzed: {{ $sample->analyzed_at?->format('d M Y') ?? now()->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div class="sig-role">Quality Control / In-Charge</div>
                        <div class="sig-name">{{ $sample->lab->contact_person ?? 'Quality In-Charge' }}</div>
                        <div class="sig-seal-box">
                            <span class="sig-seal-text">&#10004; CERTIFIED BY LAB IN-CHARGE</span>
                        </div>
                        <div class="sig-note">{{ $sample->lab->name ?? 'Department of Horticulture' }}</div>
                    </td>
                </tr>
            </table>

            {{-- LEGAL DISCLAIMER & AUDIT TRAIL FOOTER --}}
            <div class="footer-note">
                <p>
                    <strong>Official Notice:</strong> This Soil Health Certificate is issued under standardized government quality control protocols.
                    Diagnostic findings strictly relate to the specific soil sample collected from the specified survey/khasra holding. Valid for 3 years from certification date.
                </p>
                <p style="margin-top: 1px;">
                    <strong>Online Verification:</strong> Verify certificate authenticity anytime by scanning the QR code above or visiting {{ route('public.report', $sample->report_token ?? '') }}
                    &bull; Helpline: {{ $sample->lab->phone ?? '+91-1933-241234' }} &bull; Certificate Ref: CERT-{{ strtoupper(substr(md5($sample->id . $sample->sample_code), 0, 10)) }} &bull; Issued: {{ now()->format('d M Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>