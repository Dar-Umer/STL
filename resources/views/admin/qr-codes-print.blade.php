<!DOCTYPE html>
<html>
<head>
    <title>QR Code Stickers - {{ $codes[0]->prefix ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            width: 191mm;
            margin: 0 auto;
            background: #fff;
            color: #222;
            font-size: 9pt;
        }

        /* Toolbar — screen only */
        .toolbar {
            text-align: center;
            padding: 14px;
            background: #f4f6f9;
            border-bottom: 1px solid #d2d6de;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .toolbar button {
            padding: 10px 30px;
            margin: 0 6px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
        }
        .toolbar .btn-print { background: #28a745; color: #fff; }
        .toolbar .btn-print:hover { background: #218838; }
        .toolbar .btn-close { background: #6c757d; color: #fff; }
        .toolbar .btn-close:hover { background: #5a6268; }

        /* Sheet header */
        .sheet-header {
            text-align: center;
            padding: 4mm 0 3mm;
            border-bottom: 2px solid #1b4d3e;
            margin-bottom: 3mm;
        }
        .sheet-header h1 {
            font-size: 16pt;
            font-weight: 700;
            color: #1b4d3e;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .sheet-header .sub {
            font-size: 8pt;
            color: #555;
            margin-top: 1mm;
        }
        .sheet-header .sub span {
            margin: 0 4mm;
        }

        /* Sticker grid */
        .sticker-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            page-break-after: always;
        }
        .sticker-grid:last-child {
            page-break-after: avoid;
        }

        /* Individual sticker panel */
        .sticker {
            position: relative;
            padding: 2.5mm 2mm;
            text-align: center;
            border: 0.5pt solid #bbb;
            border-right: none;
            border-bottom: none;
            min-height: 52mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .sticker:nth-child(4n) {
            border-right: 0.5pt solid #bbb;
        }
        .sticker:nth-last-child(-n+4) {
            border-bottom: 0.5pt solid #bbb;
        }

        /* Brand bar */
        .sticker-brand {
            font-size: 5.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: #1b4d3e;
            color: #fff;
            padding: 0.8mm 2mm;
            border-radius: 2px;
            display: inline-block;
            margin-bottom: 1.5mm;
            width: 100%;
            max-width: 38mm;
        }

        /* QR container — adds white background padding for scanner reliability */
        .sticker-qr {
            background: #fff;
            padding: 1.5mm;
            border-radius: 3px;
            display: inline-block;
            line-height: 0;
            box-shadow: 0 0 0 0.5pt #ddd;
        }
        .sticker-qr svg {
            display: block;
            margin: 0 auto;
            width: 32mm;
            height: 32mm;
        }

        /* Code text */
        .sticker-code {
            font-family: 'Courier New', 'Consolas', monospace;
            font-size: 10pt;
            font-weight: 700;
            color: #111;
            margin-top: 1.5mm;
            letter-spacing: 0.5px;
        }
        .sticker-code::before {
            content: '◆ ';
            font-size: 6pt;
            color: #1b4d3e;
            vertical-align: middle;
        }

        /* Handwriting fields */
        .sticker-fields {
            margin-top: 1.5mm;
            width: 100%;
            max-width: 38mm;
        }
        .sticker-fields .field-row {
            display: flex;
            justify-content: space-between;
            font-size: 6pt;
            color: #777;
            margin-bottom: 0.5mm;
        }
        .sticker-fields .field-row .line {
            flex: 1;
            border-bottom: 0.5pt solid #bbb;
            margin-left: 2mm;
            min-height: 4mm;
        }

        /* Cut marks — tiny corner indicators */
        .cut-tl, .cut-tr, .cut-bl, .cut-br {
            position: absolute;
            width: 4mm;
            height: 4mm;
            pointer-events: none;
        }
        .cut-tl { top: -0.5pt; left: -0.5pt; border-top: 0.75pt solid #888; border-left: 0.75pt solid #888; }
        .cut-tr { top: -0.5pt; right: -0.5pt; border-top: 0.75pt solid #888; border-right: 0.75pt solid #888; }
        .cut-bl { bottom: -0.5pt; left: -0.5pt; border-bottom: 0.75pt solid #888; border-left: 0.75pt solid #888; }
        .cut-br { bottom: -0.5pt; right: -0.5pt; border-bottom: 0.75pt solid #888; border-right: 0.75pt solid #888; }

        /* Scissors icon at midpoints on grid borders */
        .cut-scissors-h {
            position: absolute;
            bottom: -3.5pt;
            left: 50%;
            transform: translateX(-50%);
            font-size: 5pt;
            color: #999;
        }

        /* Sheet footer */
        .sheet-footer {
            text-align: center;
            padding: 2mm 0;
            font-size: 6.5pt;
            color: #999;
            border-top: 1pt solid #ddd;
            margin-top: 1mm;
        }
        .sheet-footer span {
            margin: 0 3mm;
        }

        @media print {
            .toolbar { display: none; }
            .sticker { border-color: #999; }
            .sticker:nth-child(4n) { border-right-color: #999; }
            .sticker:nth-last-child(-n+4) { border-bottom-color: #999; }
            .sticker-fields .field-row .line { border-bottom-color: #999; }
            .sheet-header { border-bottom-color: #1b4d3e; }
            .cut-tl, .cut-tr, .cut-bl, .cut-br { border-color: #666; }
            .sticker-qr { box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="toolbar">
        <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print Stickers</button>
        <button class="btn-close" onclick="window.close()">&times; Close</button>
    </div>

    @php
        $total = $codes->count();
        $perPage = 20;
        $pages = (int) ceil($total / $perPage);
        $currentPrefix = $codes[0]->prefix ?? '';
    @endphp

    @foreach($codes->chunk($perPage) as $pageIndex => $chunk)
    <div class="sticker-grid">
        @foreach($chunk as $code)
        <div class="sticker">
            <div class="cut-tl"></div>
            <div class="cut-tr"></div>
            <div class="cut-bl"></div>
            <div class="cut-br"></div>

            <div class="sticker-brand">{{ $labName }}</div>

            <div class="sticker-qr">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->errorCorrection('H')->generate($code->code) !!}
            </div>

            <div class="sticker-code">{{ $code->code }}</div>
        </div>
        @endforeach

        {{-- Pad empty cells on last page --}}
        @for($i = $chunk->count(); $i < $perPage; $i++)
        <div class="sticker" style="border: 0.5pt solid #eee; background: #fafafa;">
            <div style="font-size:6pt; color:#ccc; margin-top:15mm;">empty</div>
        </div>
        @endfor
    </div>

    {{-- Page footer only on print --}}
    <div class="sheet-footer">
        <span>Soil Testing Management System</span>
        <span>Prefix: {{ $currentPrefix }}</span>
        <span>Page {{ $pageIndex + 1 }} of {{ $pages }}</span>
        <span>{{ now()->format('d/m/Y h:i A') }}</span>
    </div>
    @endforeach

</body>
</html>
