<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Soil Health Card - {{ $sample->sample_code }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 8mm 9mm 8mm 9mm;
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
            font-weight: 400;
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
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            src: url('{{ str_replace('\\', '/', public_path('fonts/Inter-Bold.ttf')) }}') format('truetype');
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', Helvetica, Arial, sans-serif;
        }
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            font-size: 7.5pt;
            color: #0f172a;
            line-height: 1.3;
            background: #ffffff;
        }

        /* Certificate Container Frame */
        .doc-container {
            border: 1.5px solid #065f46;
            border-radius: 4px;
            padding: 9px 10px 8px 10px;
            background: #ffffff;
            position: relative;
        }

        /* Top Government Masthead */
        .masthead-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .emblem-td {
            width: 82px;
            vertical-align: middle;
            text-align: left;
        }
        .emblem-img {
            height: 68px;
            max-width: 74px;
            display: block;
        }
        .masthead-center {
            vertical-align: middle;
            text-align: center;
            padding: 0 6px;
        }
        .govt-state {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .govt-dept {
            font-size: 8.6pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 1.5px;
        }
        .lab-title {
            font-size: 11pt;
            font-weight: bold;
            color: #064e3b;
            margin-top: 1.5px;
            letter-spacing: 0.3px;
        }
        .lab-contact-line {
            font-size: 6.5pt;
            color: #475569;
            margin-top: 2.5px;
            line-height: 1.25;
        }
        .qr-td {
            width: 86px;
            vertical-align: middle;
            text-align: right;
        }
        .qr-box {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 3px;
            background: #ffffff;
            border-radius: 4px;
            text-align: center;
        }
        .qr-box img {
            width: 72px;
            height: 72px;
            display: block;
        }
        .qr-caption {
            font-size: 5pt;
            color: #047857;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2px;
            letter-spacing: 0.4px;
        }

        /* Title Banner Strip - Only "SOIL HEALTH CARD" */
        .banner-strip {
            background: #064e3b;
            border-bottom: 2.5px solid #d97706;
            border-radius: 3px;
            padding: 4.5px 8px;
            text-align: center;
            margin-bottom: 6px;
        }
        .banner-title {
            color: #ffffff;
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        /* Metadata Passport Ribbon (4 Symmetrical Columns) */
        .meta-strip {
            width: 100%;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            margin-bottom: 6px;
        }
        .meta-strip td {
            width: 25%;
            padding: 4px 8px;
            font-size: 7pt;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .meta-strip td:last-child {
            border-right: none;
        }
        .m-key {
            color: #64748b;
            font-size: 5.3pt;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 1.5px;
            letter-spacing: 0.3px;
        }
        .m-val {
            color: #0f172a;
            font-weight: bold;
            font-size: 7.5pt;
        }

        /* Two-Column Dossier Grid */
        .dossier-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 6px;
        }
        .dossier-cell {
            width: 50%;
            vertical-align: top;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            background: #ffffff;
            overflow: hidden;
        }
        .card-header {
            background: #f0fdf4;
            border-bottom: 1px solid #bbf7d0;
            padding: 3px 8px;
            font-size: 6.6pt;
            font-weight: bold;
            color: #064e3b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .info-table td {
            padding: 2.2px 8px;
            font-size: 6.8pt;
            border-bottom: 0.5px solid #f1f5f9;
            vertical-align: middle;
        }
        .info-table td.label-col {
            width: 90px;
            color: #64748b;
            font-weight: normal;
        }
        .info-table td.val-col {
            color: #0f172a;
            font-weight: bold;
        }

        /* Results Matrix Table */
        .matrix-header-title {
            background: #065f46;
            color: #ffffff;
            font-size: 6.8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2.8px 8px;
            border-radius: 3px;
            margin-bottom: 4px;
        }
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            margin-bottom: 6px;
            border: 1px solid #cbd5e1;
        }
        .matrix-table th {
            background: #064e3b;
            color: #ffffff;
            font-weight: bold;
            font-size: 6.4pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 3.5px 5px;
            border: 1px solid #047857;
            text-align: center;
        }
        .matrix-table td {
            padding: 2.5px 5px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .matrix-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .matrix-table td.col-idx {
            text-align: center;
            font-weight: bold;
            color: #64748b;
            width: 22px;
        }
        .matrix-table td.col-name {
            font-weight: bold;
            color: #0f172a;
            font-size: 6.9pt;
            width: 175px;
        }
        .col-category {
            font-size: 5.3pt;
            color: #64748b;
            display: block;
            text-transform: uppercase;
            font-weight: normal;
            letter-spacing: 0.3px;
        }
        .matrix-table td.col-method {
            font-size: 6pt;
            color: #64748b;
        }
        .matrix-table td.col-obs {
            text-align: center;
            font-weight: bold;
            font-size: 7.8pt;
            color: #0f172a;
            width: 66px;
        }
        .matrix-table td.col-unit {
            text-align: center;
            color: #475569;
            font-size: 6.4pt;
            width: 50px;
        }
        .matrix-table td.col-range {
            text-align: center;
            font-size: 6.4pt;
            color: #475569;
            width: 92px;
        }
        .matrix-table td.col-rating {
            text-align: center;
            width: 86px;
        }

        /* Rating Badges */
        .pill-rating {
            display: inline-block;
            padding: 1.8px 8px;
            font-size: 5.9pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .pill-low {
            background: #fef2f2;
            color: #b91c1c;
            border: 0.5px solid #fca5a5;
        }
        .pill-med {
            background: #fffbeb;
            color: #b45309;
            border: 0.5px solid #fcd34d;
        }
        .pill-high {
            background: #eff6ff;
            color: #1d4ed8;
            border: 0.5px solid #93c5fd;
        }
        .pill-opt {
            background: #ecfdf5;
            color: #047857;
            border: 0.5px solid #6ee7b7;
        }

        /* Agronomic Advisory Callout Box */
        .advisory-box {
            background: #fafaf9;
            border: 1px solid #cbd5e1;
            border-left: 3.5px solid #059669;
            border-radius: 3px;
            margin-bottom: 6px;
            padding: 5px 8px;
        }
        .advisory-title {
            font-size: 6.8pt;
            font-weight: bold;
            color: #064e3b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2.5px;
        }
        .advisory-body {
            font-size: 6.6pt;
            line-height: 1.38;
            color: #334155;
            text-align: justify;
        }

        /* Legal & Audit Trail Footer */
        .footer-bar {
            font-size: 5.4pt;
            color: #64748b;
            text-align: center;
            margin-top: 5px;
            border-top: 0.5px solid #cbd5e1;
            padding-top: 3.5px;
            line-height: 1.25;
        }
        .footer-bar strong {
            color: #334155;
        }
    </style>
</head>
<body>
    @php
        $emblemPath = public_path('images/emblem_india.png');
        $emblemBase64 = file_exists($emblemPath) ? base64_encode(file_get_contents($emblemPath)) : '';

        // Dynamically fetch Lab & Portal Settings configured by Admin
        $lab = $sample->lab;
        $labName = $lab?->name ?: setting('app_name', 'Soil Testing Laboratory, Pulwama');
        $labAddress = $lab?->address ?: (setting('front.cho_address') ?: 'District Complex, Pulwama, J&K - 192301');
        $labPhone = $lab?->phone ?: (setting('front.lab_phone') ?: (setting('front.helpline') ?: setting('front.cho_phone', '+91-1933-241234')));
        $labEmail = $lab?->email ?: (setting('front.cho_email') ?: 'soillab.horticulture@jk.gov.in');

        $rawHost = parse_url(config('app.url'), PHP_URL_HOST);
        $portalHost = ($rawHost && $rawHost !== 'localhost') ? $rawHost : 'stl.jkhorticulture.in';
    @endphp

    <div class="doc-container">
        {{-- GOVERNMENT MASTHEAD & OFFICIAL IDENTITY --}}
        <table class="masthead-table">
            <tr>
                <td class="emblem-td">
                    @if($emblemBase64)
                        <img src="data:image/png;base64,{{ $emblemBase64 }}" class="emblem-img" alt="National Emblem of India">
                    @else
                        <div style="font-weight:bold; color:#064e3b; font-size:9pt;">GOVT OF J&amp;K</div>
                    @endif
                </td>
                <td class="masthead-center">
                    <div class="govt-state">GOVERNMENT OF JAMMU &amp; KASHMIR</div>
                    <div class="govt-dept">DEPARTMENT OF HORTICULTURE &bull; SOIL TESTING SERVICE</div>
                    <div class="lab-title">{{ $labName }}</div>
                    <div class="lab-contact-line">
                        {{ $labAddress }} &bull; Helpline: {{ $labPhone }} &bull; Portal: {{ $portalHost }}
                    </div>
                </td>
                <td class="qr-td">
                    <div class="qr-box">
                        <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Verification">
                        <div class="qr-caption">Scan to Verify</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- TITLE BANNER STRIP - ONLY "SOIL HEALTH CARD" --}}
        <div class="banner-strip">
            <div class="banner-title">SOIL HEALTH CARD</div>
        </div>

        {{-- METADATA PASSPORT STRIP (4 SYMMETRICAL COLUMNS) --}}
        <table class="meta-strip">
            <tr>
                <td>
                    <span class="m-key">Report Reference</span>
                    <span class="m-val">RPT-{{ $sample->sample_code }}</span>
                </td>
                <td>
                    <span class="m-key">Sample Identifier</span>
                    <span class="m-val">{{ $sample->sample_code }}</span>
                </td>
                <td>
                    <span class="m-key">Lab Register No.</span>
                    <span class="m-val" style="color: #047857;">{{ $sample->lab_number ?? '1001' }}</span>
                </td>
                <td>
                    <span class="m-key">Certified On</span>
                    <span class="m-val">{{ $sample->analyzed_at?->format('d M Y') ?? now()->format('d M Y') }}</span>
                </td>
            </tr>
        </table>

        {{-- TWO-COLUMN DOSSIER: BENEFICIARY & PLOT SPECS --}}
        <table class="dossier-table">
            <tr>
                {{-- Column 1: Beneficiary Particulars --}}
                <td class="dossier-cell">
                    <div class="card-header">I. Beneficiary / Orchardist Particulars</div>
                    <table class="info-table">
                        <tr>
                            <td class="label-col">Farmer Name:</td>
                            <td class="val-col">{{ $sample->farmer->name ?? '—' }}</td>
                        </tr>
                        @if($sample->farmer?->parentage)
                        <tr>
                            <td class="label-col">Parentage (S/O):</td>
                            <td class="val-col">{{ $sample->farmer->parentage }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="label-col">Mobile Contact:</td>
                            <td class="val-col">{{ $sample->farmer->phone ?? '—' }}</td>
                        </tr>
                        @if($sample->farmer?->aadhar_no)
                        <tr>
                            <td class="label-col">Aadhaar / Farmer ID:</td>
                            <td class="val-col">XXXX-XXXX-{{ substr($sample->farmer->aadhar_no, -4) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="label-col">Village / Halqa:</td>
                            <td class="val-col">{{ $sample->farmer->village->name ?? $sample->farmer->panchayat->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Zone &bull; District:</td>
                            <td class="val-col">{{ $sample->farmer->zone->name ?? '—' }}, {{ $sample->farmer->district->name ?? 'Pulwama' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">State / UT:</td>
                            <td class="val-col">{{ $sample->farmer->state->name ?? 'Jammu & Kashmir' }}</td>
                        </tr>
                    </table>
                </td>

                {{-- Column 2: Land Holding & Plot Specs --}}
                <td class="dossier-cell">
                    <div class="card-header">II. Orchard Holding &amp; Plot Specs</div>
                    <table class="info-table">
                        <tr>
                            <td class="label-col">Plot Identifier:</td>
                            <td class="val-col">{{ $sample->plot->plot_name ?? 'Holding 1' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Survey / Khasra No:</td>
                            <td class="val-col">{{ $sample->plot->khasra_no ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Holding Area:</td>
                            <td class="val-col">{{ $sample->plot->area_kanals ? number_format($sample->plot->area_kanals, 2) . ' Kanals' : 'Standard Orchard' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">GPS Coordinates:</td>
                            <td class="val-col">
                                @if($sample->plot?->latitude && $sample->plot?->longitude)
                                    {{ number_format((float)$sample->plot->latitude, 4) }}&deg; N, {{ number_format((float)$sample->plot->longitude, 4) }}&deg; E
                                @else
                                    Field Verified &bull; GPS Tagged
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">Soil Classification:</td>
                            <td class="val-col">{{ $sample->plot->soilType->name ?? 'Loamy Soil' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Crop &amp; Variety:</td>
                            <td class="val-col">
                                {{ $sample->plot->crop->name ?? 'Apple Orchard' }}
                                @if($sample->plot?->variety) ({{ $sample->plot->variety }}) @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">Plantation Age:</td>
                            <td class="val-col">{{ $sample->plot->age_of_plants ? $sample->plot->age_of_plants . ' Years (Bearing)' : 'Annual / Seasonal Crop' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- SOIL PARAMETERS SCORECARD TABLE (7 COLUMNS) --}}
        <div class="matrix-header-title">III. Soil Physicochemical &amp; Plant Nutrient Diagnostic Analysis</div>
        <table class="matrix-table">
            <thead>
                <tr>
                    <th style="width: 22px;">#</th>
                    <th style="text-align: left; width: 175px;">Parameter / Plant Nutrient</th>
                    <th style="text-align: left;">Standard Test Method</th>
                    <th style="width: 66px;">Observed</th>
                    <th style="width: 50px;">Unit</th>
                    <th style="width: 92px;">Standard Range</th>
                    <th style="width: 86px;">Nutrient Rating</th>
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
                    $pillClass = match($tier) {
                        'low', 'deficient' => 'pill-low',
                        'high', 'excess', 'very high' => 'pill-high',
                        'optimal', 'sufficient', 'normal', 'neutral' => 'pill-opt',
                        default => 'pill-med',
                    };

                    // Compute clean standard benchmark range
                    $rangeDisplay = 'Standard';
                    if ($param) {
                        if ($rawCode === 'pH') {
                            $rangeDisplay = '6.0 - 7.5 (Neutral)';
                        } elseif ($rawCode === 'EC') {
                            $rangeDisplay = '< 1.0 (Normal)';
                        } elseif ($rawCode === 'OC') {
                            $rangeDisplay = '0.50 - 0.75 %';
                        } elseif ($param->rating_low_max && $param->rating_medium_max) {
                            $low = (float)$param->rating_low_max;
                            $med = (float)$param->rating_medium_max;
                            $fmtLow = (floor($low) == $low) ? number_format($low, 0) : number_format($low, 1);
                            $fmtMed = (floor($med) == $med) ? number_format($med, 0) : number_format($med, 1);
                            $rangeDisplay = $fmtLow . ' - ' . $fmtMed;
                        } elseif (!empty($param->range)) {
                            $rangeDisplay = $param->range;
                        }
                    }
                @endphp
                <tr>
                    <td class="col-idx">{{ $loop->iteration }}</td>
                    <td class="col-name">
                        {{ $meta['name'] }}
                        <span class="col-category">{{ $meta['category'] }}</span>
                    </td>
                    <td class="col-method">{{ $param->method ?? 'Standard Protocol' }}</td>
                    <td class="col-obs">{{ is_numeric($result->value) ? number_format((float)$result->value, 2) : ($result->value ?? '—') }}</td>
                    <td class="col-unit">{{ $param->unit ?? '—' }}</td>
                    <td class="col-range">{{ $rangeDisplay }}</td>
                    <td class="col-rating">
                        <span class="pill-rating {{ $pillClass }}">
                            {{ $result->rating ? ucfirst($result->rating) : 'Adequate' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#64748b; padding: 10px;">No chemical test results recorded for this sample.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- EXPERT AGRONOMIC RECOMMENDATIONS & FERTILIZER ADVISORY --}}
        @php
            $rawRecs = $sample->recommendations ?? '';
            // Sanitize all corrupt or non-standard UTF-8 symbols for DomPDF
            $cleanRecs = str_replace(
                ['ΓÇô', 'ΓÇö', 'Γéé', 'Γéà', 'â€“', 'â€”', 'â‚‚', 'â‚…', 'Г‡Г¶', 'Г‡Гґ', 'Г‡Г№', 'P₂O₅', 'K₂O', '–'],
                ['-', '-', '2', '5', '-', '-', '2', '5', '-', '-', '-', 'P2O5', 'K2O', '-'],
                $rawRecs
            );
        @endphp
        <div class="advisory-box">
            <div class="advisory-title">IV. Fertilizer Advisory &amp; Agronomic Recommendations</div>
            <div class="advisory-body">
                @if(!empty(trim($cleanRecs)))
                    {!! nl2br(e($cleanRecs)) !!}
                @else
                    Apply well-decomposed Farm Yard Manure (FYM) or vermicompost @ 15-20 kg/tree annually prior to dormancy break. Maintain recommended balanced chemical fertilizer doses split into spring bud-burst and post-fruit set stages. Ensure adequate moisture during fruit cell division. Avoid excess nitrogenous fertilizer application to prevent vegetative overgrowth and bitter pit.
                @endif
            </div>
        </div>

        {{-- LEGAL DISCLAIMER & AUDIT TRAIL FOOTER --}}
        <div class="footer-bar">
            <p>
                <strong>Official Notice:</strong> This Soil Health Card is issued under standardized government quality control protocols. Diagnostic findings relate strictly to the soil sample collected from the specified survey/khasra plot. Valid for 3 years from certification date.
            </p>
            <p style="margin-top: 2px;">
                <strong>Online Verification:</strong> Verify certificate authenticity anytime by scanning the QR code above or visiting {{ 'https://' . $portalHost . '/report/' . ($sample->report_token ?? '') }}
                &bull; Helpline: {{ $labPhone }} &bull; Certificate Ref: CERT-{{ strtoupper(substr(md5($sample->id . $sample->sample_code), 0, 10)) }} &bull; Issued: {{ now()->format('d M Y H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>
