<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Unread Reminders</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Customer Reminders</h2>

<table>
    <thead>
        <tr>
            <th>Sr #</th>
            <th>Customer</th>
            <th>Invoice</th>
            <th>Invoice Date</th>
            <th>Reminder Date</th>
            {{-- <th>Message</th> --}}
        </tr>
    </thead>
    <tbody>
        @php($i = 1)
        @foreach($reminders as $reminder)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $reminder->customer->name ?? '' }}</td>
            <td>{{ $reminder->invoice->invoice_no ?? '' }}</td>
            <td>{{ $reminder->invoice->date ?? '' }}</td>
            <td>{{ $reminder->reminder_date }}</td>
            {{-- <td>{{ $reminder->message }}</td> --}}
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>