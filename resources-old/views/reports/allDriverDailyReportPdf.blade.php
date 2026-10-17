<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Driver Daily Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .meta { text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 4px 5px; }
        th { background: #333; color: #fff; font-size: 9px; }
        td { font-size: 9px; }
        .num { text-align: right; }
        .name { text-align: left; }
        tfoot td { font-weight: bold; background: #eee; }
    </style>
</head>
<body>
    <div class="title">ALL DRIVER DAILY REPORT</div>
    <div class="meta">Date: {{ $date }}</div>

    <table>
        <thead>
            <tr>
                <th>Sr #</th>
                <th>Driver Name</th>
                @foreach ($items as $item)
                    <th>Total {{ $item->name }} Sale</th>
                @endforeach
                <th>Total Sale</th>
                <th>Total Purchase</th>
                @foreach ($expenseTypes as $type)
                    <th>{{ $type }}</th>
                @endforeach
                <th>Net Profit</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($driverReports as $row)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td class="name">{{ $row['driver_name'] }}</td>
                    @foreach ($items as $item)
                        <td class="num">{{ number_format($row['item_sales'][$item->id] ?? 0, 2) }}</td>
                    @endforeach
                    <td class="num">{{ number_format($row['total_sale'], 2) }}</td>
                    <td class="num">{{ number_format($row['total_purchase'], 2) }}</td>
                    @foreach ($expenseTypes as $type)
                        <td class="num">{{ number_format($row['expenses'][$type] ?? 0, 2) }}</td>
                    @endforeach
                    <td class="num">{{ number_format($row['net_profit'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                @foreach ($items as $item)
                    <td class="num">{{ number_format($totals['item_sales'][$item->id] ?? 0, 2) }}</td>
                @endforeach
                <td class="num">{{ number_format($totals['total_sale'], 2) }}</td>
                <td class="num">{{ number_format($totals['total_purchase'], 2) }}</td>
                @foreach ($expenseTypes as $type)
                    <td class="num">{{ number_format($totals['expenses'][$type] ?? 0, 2) }}</td>
                @endforeach
                <td class="num">{{ number_format($totals['net_profit'], 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
