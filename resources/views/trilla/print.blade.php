<!DOCTYPE html>
<html>
<head>
    <title>Trilla Report</title>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background:#f3f3f3;
        }

        .container{
            width:900px;
            margin:auto;
            background:white;
            padding:20px;
        }

        h3{
            text-align:center;
            margin-bottom:10px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th, table td{
            border:1px solid #000;
            padding:4px;
            font-size:13px;
        }

        .summary{
            margin-top:25px;
            width:400px;
        }

        .summary td{
            border:none;
            padding:4px;
        }

        .highlight{
            background:yellow;
            font-weight:bold;
        }

        .print-btn{
            margin-bottom:10px;
        }

        @media print{
            .print-btn{
                display:none;
            }
        }
    </style>
</head>

<body>

<div class="container">

<button onclick="window.print()" class="print-btn">Print</button>

<h3>
{{ $record->trilla->name }} OCTOBER {{ \Carbon\Carbon::parse($record->month)->year }}
</h3>

<table>
<thead>
<tr>
<th>Date</th>
<th>From</th>
<th>To</th>
<th>Trip Amount</th>
<th>Diesel</th>
<th>D Trip</th>
<th>Maintenance</th>
<th>Cash Received</th>
<th>Bill No</th>
</tr>
</thead>

<tbody>

@php
$totalTrip=0;
$totalDiesel=0;
$totalMaintenance=0;
$totalCash=0;
@endphp

@foreach($record->trillaRecordChild as $row)

@php
$totalTrip += $row->trip_amount;
$totalDiesel += $row->diesel;
$totalMaintenance += $row->maintenance;
$totalCash += $row->cash_received;
@endphp

<tr>
<td>{{ \Carbon\Carbon::parse($row->date)->format('j/M/y') }}</td>
<td>{{ $row->from_location }}</td>
<td>{{ $row->to_location }}</td>
<td>{{ $row->trip_amount }}</td>
<td>{{ $row->diesel }}</td>
<td>{{ $row->d_trip }}</td>
<td>{{ $row->maintenance }}</td>
<td>{{ $row->cash_received }}</td>
<td>{{ $row->bill_no }}</td>
</tr>

@endforeach

<tr>
<td colspan="2"><b>Total</b></td>
<td>{{ $totalTrip }}</td>
<td>{{ $totalDiesel }}</td>
<td>0</td>
<td>{{ $totalMaintenance }}</td>
<td>{{ $totalCash }}</td>
<td></td>
<td></td>
</tr>

</tbody>
</table>


<table class="summary">

<tr>
<td>Total Trip</td>
<td>{{ $totalTrip }}</td>
</tr>

<tr>
<td>Diesel Oct</td>
<td>{{ $totalDiesel }}</td>
</tr>

<tr>
<td>Salary Oct {{ \Carbon\Carbon::parse($record->month)->year }}</td>
<td>{{ $record->driver_salary }}</td>
</tr>

<tr>
<td>Maintenance</td>
<td>{{ $totalMaintenance }}</td>
</tr>

<tr>
<td><b>Total Exp</b></td>
<td>
{{ $totalDiesel + $record->driver_salary + $totalMaintenance }}
</td>
</tr>

<tr>
<td>Profit</td>
<td>
{{ $totalTrip - ($totalDiesel + $record->driver_salary + $totalMaintenance) }}
</td>
</tr>

<tr>
<td>Other Expense</td>
<td>{{ $record->other_expense }}</td>
</tr>

<tr class="highlight">
<td>Net Profit</td>
<td>{{ $record->net_profit }}</td>
</tr>

</table>

</div>

</body>
</html>