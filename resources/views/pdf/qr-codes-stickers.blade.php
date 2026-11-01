<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>QR Code Stickers - {{ $zoneName }}</title>
<style>
    @page {
        size: A4 portrait;
        margin: 6mm 6mm 6mm 6mm;
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 8pt;
        color: #1e293b;
        background: #ffffff;
    }
    .page {
        page-break-after: always;
    }
    .page:last-child {
        page-break-after: avoid;
    }
    .header {
        text-align: center;
        margin-bottom: 3mm;
        border-bottom: 1.5px solid #047857;
        padding-bottom: 2mm;
    }
    .title {
        font-size: 11pt;
        font-weight: bold;
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .sub {
        font-size: 7.5pt;
        color: #64748b;
        margin-top: 1mm;
    }
    table.grid {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    table.grid td {
        width: 25%;
        padding: 2mm 1.5mm;
        vertical-align: top;
    }
    .sticker {
        border: 1px dashed #94a3b8;
        border-radius: 4px;
        padding: 3mm 2mm 3mm 2mm;
        text-align: center;
        background: #ffffff;
        min-height: 44mm;
    }
    .sticker-badge {
        background: #047857;
        color: #ffffff;
        font-size: 6pt;
        font-weight: bold;
        text-transform: uppercase;
        padding: 1.5px 2px;
        border-radius: 2px;
        margin-bottom: 2.5mm;
        display: block;
        overflow: hidden;
        white-space: nowrap;
        letter-spacing: 0.3px;
    }
    .sticker-qr {
        margin: 0 auto;
        text-align: center;
    }
    .sticker-qr img {
        width: 28mm;
        height: 28mm;
        display: inline-block;
    }
    .sticker-code {
        font-family: 'Courier-Bold', Courier, monospace;
        font-size: 10.5pt;
        font-weight: bold;
        color: #0f172a;
        margin-top: 2mm;
        letter-spacing: 0.5px;
    }
    .footer {
        text-align: center;
        margin-top: 3mm;
        font-size: 6pt;
        color: #94a3b8;
    }
</style>
</head>
<body>
@foreach($pages as $pageIndex => $pageItems)
<div class="page">
    <div class="header">
        <div class="title">{{ $zoneName }} &bull; Soil Sample QR Stickers</div>
        <div class="sub">{{ $labName }} &bull; {{ $batchLabel }} &bull; Page {{ $pageIndex + 1 }} of {{ count($pages) }} &bull; Total: {{ $totalCount }} Stickers</div>
    </div>
    <table class="grid">
        @foreach(array_chunk($pageItems, 4) as $row)
        <tr>
            @foreach($row as $item)
            <td>
                <div class="sticker">
                    <div class="sticker-badge">{{ $zoneName }}</div>
                    <div class="sticker-qr">
                        <img src="{{ $item['b64'] }}" alt="{{ $item['code'] }}" />
                    </div>
                    <div class="sticker-code">{{ $item['code'] }}</div>
                </div>
            </td>
            @endforeach
            @for($p = count($row); $p < 4; $p++)
            <td></td>
            @endfor
        </tr>
        @endforeach
    </table>
    <div class="footer">
        Generated via Soil Testing Lab System &bull; {{ now()->format('d M Y, h:i A') }} &bull; Strictly for use within {{ $zoneName }}
    </div>
</div>
@endforeach
</body>
</html>
