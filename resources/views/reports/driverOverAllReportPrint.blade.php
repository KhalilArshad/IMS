<!DOCTYPE html>
<html>
<head>
    <title>Driver Weekly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 20px;
        }
        h2, h4 {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }
        th {
            background: #f0f0f0;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>

<body onload="window.print()">

<h2>Driver Weekly Report</h2>
<p>
    <strong>Driver:</strong> {{ $driver->name }} <br>
    <strong>Period:</strong> {{ $dateFrom }} → {{ $dateTo }}
</p>

@foreach($invoices as $customerId => $weeks)
    <h4>Customer: {{ $weeks->first()->customer->name }}</h4>

    <table>
        <thead>
            <tr>
                <th>Week</th>
                <th>Last Entry Date</th>
                <th>Remaining</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        @foreach($weeks as $weekKey => $entry)
            @php
                [$year, $week] = explode('-', $weekKey);
                $start = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end   = \Carbon\Carbon::now()->setISODate($year, $week)->endOfWeek();
            @endphp
            <tr>
                <td>{{ $start->format('d M') }} - {{ $end->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                <td class="text-right">{{ number_format($entry->net_remaining, 2) }}</td>
                <td>{{ $entry->description }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endforeach

</body>
</html>
