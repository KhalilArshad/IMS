@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <style>
        .report-table-wrap {
            overflow-x: auto;
        }
        .report-table th,
        .report-table td {
            white-space: nowrap;
            font-size: 13px;
            vertical-align: middle;
        }
        .report-table thead th {
            background: #0d6efd;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .report-table tfoot td {
            font-weight: 600;
            background: #f8f9fa;
        }
        .text-end-num {
            text-align: right;
        }
    </style>
@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Driver Daily Report</li>
                    </ol>
                </nav>
            </div>
        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <?php
                                $system_date = App\Models\Setting::select('id','system_date')->first();
                                $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
                            ?>
                            <form method="GET" action="{{ url('getAllDriverDailyReport') }}" class="d-flex flex-wrap gap-2 align-items-end">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                               value="{{ old('date', $oldDate ?? $system_date) }}">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                                @if (!empty($oldDate) && count($driverReports) > 0)
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <a href="{{ url('allDriverDailyReportPrint') }}?date={{ $oldDate }}"
                                           target="_blank" class="btn btn-success">Print PDF</a>
                                    </div>
                                </div>
                                @endif
                            </form>
                        </div>

                        @if (empty($oldDate))
                            <div class="alert alert-info mb-0">Select a date and click Search to view the report.</div>
                        @elseif (count($driverReports) === 0)
                            <div class="alert alert-warning mb-0">No driver activity found for this date.</div>
                        @else
                            <div class="report-table-wrap">
                                <table class="table table-bordered report-table">
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
                                                <td>{{ $row['driver_name'] }}</td>
                                                @foreach ($items as $item)
                                                    <td class="text-end-num">{{ number_format($row['item_sales'][$item->id] ?? 0, 2) }}</td>
                                                @endforeach
                                                <td class="text-end-num">{{ number_format($row['total_sale'], 2) }}</td>
                                                <td class="text-end-num">{{ number_format($row['total_purchase'], 2) }}</td>
                                                @foreach ($expenseTypes as $type)
                                                    <td class="text-end-num">{{ number_format($row['expenses'][$type] ?? 0, 2) }}</td>
                                                @endforeach
                                                <td class="text-end-num">{{ number_format($row['net_profit'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">Total</td>
                                            @foreach ($items as $item)
                                                <td class="text-end-num">{{ number_format($totals['item_sales'][$item->id] ?? 0, 2) }}</td>
                                            @endforeach
                                            <td class="text-end-num">{{ number_format($totals['total_sale'], 2) }}</td>
                                            <td class="text-end-num">{{ number_format($totals['total_purchase'], 2) }}</td>
                                            @foreach ($expenseTypes as $type)
                                                <td class="text-end-num">{{ number_format($totals['expenses'][$type] ?? 0, 2) }}</td>
                                            @endforeach
                                            <td class="text-end-num">{{ number_format($totals['net_profit'], 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <p class="text-muted mt-2 mb-0" style="font-size: 12px;">
                                Net Profit = Total Sale − Total Purchase − all vehicle expenses.
                                Total Purchase = stock assigned to the driver on this date (not the supplier purchase bill).
                                Item columns show sales of each product by the driver on the selected date.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
@endsection
