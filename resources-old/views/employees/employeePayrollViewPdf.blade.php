<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Year Payroll Slip</title>
    <style>
        body {
            font-family: sans-serif;
            color: #213547;
            font-size: 12px;
        }

        .sheet {
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 24px;
            background: #ffffff;
        }

        .header {
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: #0f172a;
        }

        .header p {
            margin: 0;
            color: #475569;
        }

        .info-table,
        .summary-table,
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 10px;
            border: 1px solid #dbe4f0;
            width: 25%;
        }

        .summary-table {
            margin: 18px 0;
        }

        .summary-table td {
            padding: 10px;
            border: 1px solid #dbe4f0;
            width: 25%;
        }

        .label {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }

        .value {
            display: block;
            margin-top: 4px;
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }

        .detail-table {
            margin-top: 16px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #dbe4f0;
            padding: 9px 8px;
            text-align: left;
        }

        .detail-table th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 11px;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 18px;
            text-align: right;
            color: #64748b;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <h1>Employee Year Payslip</h1>
            <p>{{ $employee->name }} | {{ $year }} payroll summary and monthly record</p>
        </div>

        <table class="info-table">
            <tr>
                <td><span class="label">Employee</span><span class="value">{{ $employee->name }}</span></td>
                <td><span class="label">Designation</span><span class="value">{{ $employee->designation ?? 'N/A' }}</span></td>
                <td><span class="label">Phone</span><span class="value">{{ $employee->phone_no ?? 'N/A' }}</span></td>
                <td><span class="label">Joining Date</span><span class="value">{{ $employee->date_of_joining ?? 'N/A' }}</span></td>
            </tr>
        </table>

        <table class="summary-table">
            <tr>
                <td><span class="label">Total Salary</span><span class="value">{{ number_format($summary['total_salary'], 2) }}</span></td>
                <td><span class="label">Total Advance</span><span class="value">{{ number_format($summary['total_advance'], 2) }}</span></td>
                <td><span class="label">Paid In Old</span><span class="value">{{ number_format($summary['total_paid_old'], 2) }}</span></td>
                <td><span class="label">Current Remaining</span><span class="value">{{ number_format($summary['current_remaining'], 2) }}</span></td>
            </tr>
        </table>

        <table class="detail-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Salary</th>
                    <th>Advance</th>
                    <th>Old Remaining</th>
                    <th>Paid In Old</th>
                    <th>Overtime</th>
                    <th>Current Paid</th>
                    <th>Overall Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->month }}</td>
                        <td>{{ number_format($payroll->salary ?? 0, 2) }}</td>
                        <td>{{ number_format($payroll->advance ?? 0, 2) }}</td>
                        <td>{{ number_format($payroll->add_in_remaining ?? 0, 2) }}</td>
                        <td>{{ number_format($payroll->paid_in_advance ?? 0, 2) }}</td>
                        <td>{{ number_format($payroll->overtime ?? 0, 2) }}</td>
                        <td>{{ number_format($payroll->total_salary_to_be_paid ?? 0, 2) }}</td>
                        <td>{{ number_format($payroll->remaining ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Generated on {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>
</body>
</html>
