<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evaluation Worksheet</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 6mm 5mm 6mm 5mm;
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
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #0f172a;
        }
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-size: 8.5px;
            line-height: 1.1;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Printable Bench Table Only */
        table.bench-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        table.bench-table th {
            background: #0f172a;
            color: #ffffff;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            padding: 5px 2px;
            border: 1px solid #000000;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            word-wrap: break-word;
        }
        table.bench-table th.col-lab {
            background: #064e3b;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: 800;
        }
        table.bench-table th.col-sample {
            background: #1e293b;
            color: #ffffff;
            font-size: 8px;
        }
        table.bench-table th .param-unit {
            display: block;
            font-size: 6.5px;
            font-weight: normal;
            opacity: 0.85;
            text-transform: none;
            letter-spacing: 0;
        }
        table.bench-table td {
            border: 1px solid #334155;
            padding: 3px 3px;
            font-size: 8.5px;
            vertical-align: middle;
            height: 29px;
            overflow: hidden;
            word-wrap: break-word;
        }
        table.bench-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        td.cell-lab {
            text-align: center;
            font-weight: 800;
            font-size: 10px;
            color: #064e3b;
            background: #f0fdf4 !important;
            border-left: 1.5px solid #000000;
            border-right: 1.5px solid #000000;
        }
        td.cell-sample {
            text-align: center;
            font-size: 7.5px;
            color: #1e293b;
            font-weight: 600;
            line-height: 1.1;
            white-space: nowrap;
        }
        td.cell-param {
            text-align: center;
            font-weight: 700;
            color: #047857;
            font-size: 8.5px;
            background: #ffffff;
        }
        td.cell-remarks {
            font-size: 7.5px;
            color: #334155;
            padding-left: 4px;
        }
    </style>
</head>
<body>

    <table class="bench-table">
        <thead>
            <tr>
                <th class="col-lab" style="width: 50px; white-space: nowrap;">Lab Code</th>
                <th class="col-sample" style="width: 78px; white-space: nowrap;">Sample No</th>
                @foreach($parameters as $param)
                    <th style="width: 27px;">
                        {{ $param->name }}
                        @if($param->unit)
                            <span class="param-unit">{{ $param->unit }}</span>
                        @endif
                    </th>
                @endforeach
                <th style="width: 55px;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($samples as $sample)
                @php
                    $resultMap = $sample->results->keyBy('parameter_id');
                @endphp
                <tr>
                    <td class="cell-lab">{{ $sample->lab_number ?? '—' }}</td>
                    <td class="cell-sample">{{ $sample->sample_code }}</td>

                    {{-- Dynamic parameter cells --}}
                    @foreach($parameters as $param)
                        @php
                            $val = $resultMap->get($param->id)?->value;
                        @endphp
                        <td class="cell-param">
                            {{ $val !== null ? $val : '' }}
                        </td>
                    @endforeach

                    <td class="cell-remarks">
                        {{ $sample->evaluation_remarks ?? '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 3 + count($parameters) }}" style="text-align: center; padding: 15px; color: #94a3b8;">
                        No samples recorded in this evaluation worksheet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
