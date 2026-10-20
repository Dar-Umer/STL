<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sample Intake Acknowledgement - {{ $sample->sample_code }}</title>
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
            font-size: 8pt;
            color: #1e293b;
            line-height: 1.35;
            background: #ffffff;
        }

        /* Certificate Outer Decorative Frame */
        .page-frame {
            border: 2px solid #059669;
            padding: 8px 10px;
            position: relative;
            background: #ffffff;
        }
        .frame-inner-border {
            border: 0.5px solid #a7f3d0;
            padding: 8px 10px;
        }

        /* Top Government & Lab Identity Header */
        .govt-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .emblem-cell {
            width: 65px;
            vertical-align: middle;
            text-align: left;
        }
        .emblem-badge {
            width: 55px;
            height: 55px;
            background: #ecfdf5;
            border: 1.5px solid #059669;
            border-radius: 50%;
            text-align: center;
            vertical-align: middle;
            display: inline-block;
            line-height: 55px;
            color: #059669;
            font-weight: bold;
            font-size: 16pt;
        }
        .govt-title-cell {
            vertical-align: middle;
            text-align: center;
            padding: 0 6px;
        }
        .govt-state-title {
            font-size: 11pt;
            font-weight: bold;
            color: #065f46;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .govt-dept-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #334155;
            margin-top: 1px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .lab-name-title {
            font-size: 10pt;
            font-weight: bold;
            color: #059669;
            margin-top: 2px;
        }
        .lab-meta-sub {
            font-size: 6.8pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* Digital QR Verification Card */
        .qr-cell {
            width: 75px;
            vertical-align: middle;
            text-align: right;
        }
        .qr-card {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 3px;
            background: #ffffff;
            border-radius: 4px;
            text-align: center;
        }
        .qr-card img {
            width: 60px;
            height: 60px;
            display: block;
        }
        .qr-hint {
            font-size: 5pt;
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
            padding: 5px 8px;
            margin: 6px 0 8px 0;
            border-radius: 4px;
        }
        .report-banner h1 {
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin: 0;
        }
        .report-banner p {
            font-size: 6.5pt;
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
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .meta-bar td {
            padding: 4px 8px;
            font-size: 7.2pt;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .meta-bar td:last-child {
            border-right: none;
        }
        .meta-bar .m-label {
            color: #64748b;
            font-size: 5.8pt;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
        }
        .meta-bar .m-val {
            color: #0f172a;
            font-weight: bold;
            font-size: 7.8pt;
        }
        .badge-status {
            display: inline-block;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 2px 7px;
            font-size: 6.8pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }

        /* Two-Column Grid: Beneficiary & Plot Particulars */
        .two-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 8px;
        }
        .two-col-cell {
            width: 50%;
            vertical-align: top;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            border-radius: 4px;
            padding: 0;
            overflow: hidden;
        }
        .box-title {
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            padding: 3.5px 8px;
            font-size: 7pt;
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
            padding: 2.8px 8px;
            font-size: 7.2pt;
            border-bottom: 0.5px solid #f1f5f9;
            vertical-align: top;
        }
        .details-table td.lbl {
            width: 100px;
            color: #64748b;
            font-weight: normal;
        }
        .details-table td.val {
            color: #0f172a;
            font-weight: bold;
        }

        /* Protocol & Turnaround Box */
        .protocol-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3.5px solid #059669;
            border-radius: 4px;
            margin-bottom: 12px;
            padding: 6px 10px;
        }
        .protocol-title {
            font-size: 7.2pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }
        .protocol-text {
            font-size: 6.8pt;
            color: #334155;
            line-height: 1.4;
        }

        /* Signatures & Chain of Custody */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 12px;
        }
        .sig-table td {
            width: 33.33%;
            vertical-align: top;
            text-align: center;
            font-size: 6.8pt;
            padding: 4px 8px;
        }
        .sig-line {
            width: 140px;
            border-top: 1px solid #64748b;
            margin: 40px auto 4px auto;
        }
        .sig-role {
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            font-size: 6.8pt;
        }
        .sig-sub {
            font-size: 5.8pt;
            color: #64748b;
        }

        /* Legal Notice & Audit Trail Footer */
        .footer-note {
            font-size: 5.8pt;
            color: #94a3b8;
            text-align: center;
            margin-top: 8px;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 4px;
            line-height: 1.3;
        }
        .footer-note strong {
            color: #64748b;
        }
    </style>
</head>
<body>
    @php
        $trackUrl = route('public.track') . '?code=' . urlencode($sample->sample_code);
        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->margin(0)->generate($trackUrl);
        $qrBase64 = base64_encode($qrSvg);
    @endphp

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
                            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="Track Sample">
                            <div class="qr-hint">Scan to Track</div>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- CERTIFICATE BANNER RIBBON --}}
            <div class="report-banner">
                <h1>OFFICIAL SAMPLE INTAKE ACKNOWLEDGEMENT &amp; RECEIPT</h1>
                <p>Standardized Soil Specimen Chain-of-Custody Record</p>
            </div>

            {{-- METADATA PASSPORT BAR --}}
            <table class="meta-bar">
                <tr>
                    <td style="width: 25%;">
                        <span class="m-label">Receipt Reference</span>
                        <span class="m-val">ACK-{{ $sample->sample_code }}</span>
                    </td>
                    <td style="width: 25%;">
                        <span class="m-label">Sample Bag Code</span>
                        <span class="m-val">{{ $sample->sample_code }}</span>
                    </td>
                    <td style="width: 25%;">
                        <span class="m-label">Collection / Intake Date</span>
                        <span class="m-val">{{ $sample->collected_at?->format('d M Y, h:i A') ?? now()->format('d M Y') }}</span>
                    </td>
                    <td style="width: 25%; text-align: right;">
                        <span class="m-label">Intake Status</span>
                        <span class="badge-status">{{ strtoupper($sample->status->label()) }}</span>
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

            {{-- PROTOCOL & TURNAROUND TIMELINE BOX --}}
            <div class="protocol-box">
                <div class="protocol-title">&#9874; Testing Protocol, Assurance &amp; Report Availability</div>
                <div class="protocol-text">
                    This soil sample has been collected following composite V-notch core sampling protocols (15–20 cm depth) and received for standardized chemical testing.
                    The laboratory analysis evaluates <strong>12 essential parameters</strong>: Soil Reaction (pH), Electrical Conductivity (EC), Organic Carbon (OC), Available Nitrogen (N), Available Phosphorus (P), Available Potassium (K), Available Sulphur (S), and Micronutrients (Zinc, Iron, Copper, Manganese, Boron).
                    The official Soil Health Certificate with personalized agronomic fertilizer dosage recommendations will be generated within <strong>5–7 business days</strong>.
                </div>
            </div>

            {{-- CHAIN OF CUSTODY SIGNATURES --}}
            <table class="sig-table">
                <tr>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-role">Sample Depositor / Farmer</div>
                        <div class="sig-sub">{{ $sample->farmer->name ?? 'Beneficiary' }}</div>
                    </td>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-role">Sample Collector</div>
                        <div class="sig-sub">{{ $sample->collector->name ?? 'Field Official' }} (Horticulture)</div>
                    </td>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-role">Lab Receiving Chemist</div>
                        <div class="sig-sub">{{ $sample->lab->name ?? 'Soil Testing Lab' }}</div>
                    </td>
                </tr>
            </table>

            {{-- LEGAL DISCLAIMER & AUDIT TRAIL FOOTER --}}
            <div class="footer-note">
                <p>
                    <strong>Important Notice:</strong> Keep this acknowledgement receipt safely. You can track sample testing progress and download the certified report online anytime by scanning the QR code above or visiting {{ route('public.track') }} using Sample Code: <strong>{{ $sample->sample_code }}</strong>.
                </p>
                <p style="margin-top: 1px;">
                    Department of Horticulture, Government of Jammu &amp; Kashmir &bull; Laboratory Helpline: {{ $sample->lab->phone ?? '+91-1933-241234' }} &bull; Generated: {{ now()->format('d M Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
