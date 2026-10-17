@extends('layouts.app')

@section('style')
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection

@section('wrapper')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            {{-- ============ BREADCRUMB ============ --}}
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 no-print">
                <div class="breadcrumb-title pe-3">Reports</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Driver Overall Details Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <meta name="csrf-token" content="{{ csrf_token() }}">

            {{-- ============ FILTER CARD ============ --}}
            <div class="row no-print">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ url('driverOverallDetailsReport') }}"
                                class="d-flex flex-wrap gap-2">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="filter">Report Type</label>
                                        <select name="filter" id="filter" class="form-control">
                                            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Daily
                                            </option>
                                            <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Weekly
                                            </option>
                                            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly
                                            </option>
                                            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Yearly
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="date">Select Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ $date }}">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="driver_id">Driver</label>
                                        <select name="driver_id" id="driver_id" class="form-control">
                                            <option value="">All Drivers</option>
                                            @foreach ($allDrivers as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ request('driver_id') == $d->id ? 'selected' : '' }}>
                                                    {{ $d->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <div class="form-group d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <button id="printReportBtn" type="button" class="btn btn-success">
                                            <i class="bx bx-printer"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ PRINTABLE AREA STARTS ============ --}}
            <div id="printableArea">

                {{-- Summary Cards --}}
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-3">
                    <div class="col">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="text-uppercase mb-1">Total Sales</h6>
                                <h3 class="mb-0">{{ number_format($totalSales, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="text-uppercase mb-1">Purchase Cost</h6>
                                <h3 class="mb-0">{{ number_format($totalPurchase, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="text-uppercase mb-1">Total Expense</h6>
                                <h3 class="mb-0">{{ number_format($totalExpense, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card {{ $totalProfit >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                            <div class="card-body">
                                <h6 class="text-uppercase mb-1">Net Profit</h6>
                                <h3 class="mb-0">{{ number_format($totalProfit, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Table --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    Driver Wise Sales / Purchase / Expense / Profit
                                    <small class="text-muted">({{ ucfirst($filter) }} — {{ $date }})</small>
                                </h5>
                                <span class="badge bg-info">{{ count($drivers) }} Drivers</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Driver Name</th>
                                                <th>Phone No</th>
                                                <th class="text-end">Total Sales</th>
                                                <th class="text-end">Purchase Cost</th>
                                                <th class="text-end">Gross</th>
                                                <th class="text-end">Expense</th>
                                                <th class="text-end">Net Profit</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($drivers as $index => $driver)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><strong>{{ $driver->name }}</strong></td>
                                                    <td>{{ $driver->phone_no ?? '-' }}</td>
                                                    <td class="text-end text-primary">
                                                        {{ number_format($driver->total_sales, 2) }}</td>
                                                    <td class="text-end text-warning">
                                                        {{ number_format($driver->total_purchase, 2) }}</td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($driver->total_gross, 2) }}</td>
                                                    <td class="text-end text-danger">
                                                        {{ number_format($driver->total_expense, 2) }}</td>
                                                    <td
                                                        class="text-end fw-bold {{ $driver->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($driver->net_profit, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($driver->net_profit > 0)
                                                            <span class="badge bg-success">Profit</span>
                                                        @elseif($driver->net_profit < 0)
                                                            <span class="badge bg-danger">Loss</span>
                                                        @else
                                                            <span class="badge bg-secondary">No Profit</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">
                                                        <i class="bx bx-info-circle fs-3 d-block mb-2"></i>
                                                        No records found for the selected filter.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if (count($drivers) > 0)
                                            <tfoot class="table-light fw-bold">
                                                <tr>
                                                    <td colspan="3" class="text-end">Grand Total:</td>
                                                    <td class="text-end text-primary">{{ number_format($totalSales, 2) }}
                                                    </td>
                                                    <td class="text-end text-warning">
                                                        {{ number_format($totalPurchase, 2) }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($totalSales - $totalPurchase, 2) }}</td>
                                                    <td class="text-end text-danger">{{ number_format($totalExpense, 2) }}
                                                    </td>
                                                    <td
                                                        class="text-end {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($totalProfit, 2) }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- ============ PRINTABLE AREA ENDS ============ --}}

        </div>
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#printReportBtn').click(function() {

                // 1. Grab only the printable section
                var printContents = document.getElementById('printableArea').innerHTML;

                // 2. Create a HIDDEN iframe (user never sees it)
                var iframe = document.createElement('iframe');
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                document.body.appendChild(iframe);

                // 3. Write the print HTML into the iframe
                var doc = iframe.contentWindow.document;
                doc.open();
                doc.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Driver Overall Details Report</title>
                        <style>
                            * { box-sizing: border-box; margin: 0; padding: 0; }

                            html, body {
                                font-family: Arial, Helvetica, sans-serif !important;
                                font-size: 12px !important;
                                font-weight: 400 !important;
                                color: #222 !important;
                                line-height: 1.45;
                                background: #fff;
                                padding: 20px;
                            }

                            /* Default: everything normal weight */
                            body, p, span, div, small, td, th, li, label {
                                font-weight: 400 !important;
                            }

                            /* Only these stay bold */
                            strong, b, h1, h2, h3, h4, h5, h6, .fw-bold {
                                font-weight: 700 !important;
                            }

                            /* ─── Report Header ─── */
                            .report-header {
                                text-align: center;
                                margin-bottom: 22px;
                                padding-bottom: 12px;
                                border-bottom: 2px solid #333;
                            }
                            .report-header h2 {
                                font-size: 22px !important;
                                font-weight: 700 !important;
                                margin-bottom: 8px !important;
                                color: #111 !important;
                                letter-spacing: 0.5px;
                            }
                            .report-header small {
                                font-size: 12px !important;
                                color: #555 !important;
                                display: inline-block;
                            }

                            /* ─── Summary Cards ─── */
                            .row {
                                display: flex;
                                flex-wrap: wrap;
                                gap: 12px;
                                margin-bottom: 15px;
                            }
                            .row > [class*="col"] { flex: 1; min-width: 170px; }
                            .card {
                                border: 1px solid #ddd;
                                border-radius: 6px;
                                padding: 14px 12px;
                                text-align: center;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            .card h6 {
                                font-size: 11px !important;
                                font-weight: 600 !important;
                                text-transform: uppercase;
                                margin-bottom: 8px !important;
                                letter-spacing: 0.6px;
                            }
                            .card h3 {
                                font-size: 20px !important;
                                font-weight: 700 !important;
                                margin: 0 !important;
                            }
                            .bg-primary { background: #0d6efd !important; color: #fff !important; }
                            .bg-warning { background: #ffc107 !important; color: #000 !important; }
                            .bg-danger  { background: #dc3545 !important; color: #fff !important; }
                            .bg-success { background: #198754 !important; color: #fff !important; }

                            /* ─── Table ─── */
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                font-size: 12px !important;
                            }
                            thead th {
                                background: #343a40 !important;
                                color: #fff !important;
                                font-weight: 600 !important;
                                padding: 9px 10px !important;
                                border: 1px solid #2b2f33 !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                                white-space: nowrap;
                                text-align: left;
                            }
                            tbody td {
                                padding: 8px 10px !important;
                                border: 1px solid #ccc !important;
                                font-weight: 400 !important;
                                font-size: 12px !important;
                                vertical-align: middle;
                            }
                            tbody tr:nth-child(even) td {
                                background: #f9f9f9 !important;
                            }
                            tfoot td {
                                background: #ececec !important;
                                font-weight: 700 !important;
                                padding: 9px 10px !important;
                                border: 1px solid #bbb !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }

                            /* Utilities */
                            .text-end    { text-align: right !important; }
                            .text-center { text-align: center !important; }
                            .text-primary { color: #0d6efd !important; }
                            .text-warning { color: #cc8a00 !important; }
                            .text-danger  { color: #dc3545 !important; }
                            .text-success { color: #198754 !important; }
                            .text-muted   { color: #6c757d !important; }

                            /* Badges */
                            .badge {
                                display: inline-block;
                                padding: 3px 9px;
                                font-size: 10.5px !important;
                                font-weight: 600 !important;
                                border-radius: 4px;
                                color: #fff !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            .badge.bg-success   { background: #198754 !important; }
                            .badge.bg-danger    { background: #dc3545 !important; }
                            .badge.bg-secondary { background: #6c757d !important; }
                            .badge.bg-info      { background: #0dcaf0 !important; color: #000 !important; }

                            /* Page setup */
                            @page {
                                size: A4 landscape;
                                margin: 10mm;
                            }
                        </style>
                    </head>
                    <body>

                        <div class="report-header">
                            <h2>Driver Overall Details Report</h2>
                            <small>
                                {{ ucfirst($filter) }} Report &nbsp;|&nbsp;
                                Date: {{ $date }} &nbsp;|&nbsp;
                                Printed: {{ now()->format('d M Y, h:i A') }}
                            </small>
                        </div>

                        ${printContents}

                    </body>
                    </html>
                `);
                doc.close();

                // 4. Print as soon as iframe finishes loading
                iframe.onload = function() {
                    setTimeout(function() {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();

                        // 5. Clean up the iframe after print dialog closes
                        setTimeout(function() {
                            document.body.removeChild(iframe);
                        }, 1000);
                    }, 200);
                };
            });

        });
    </script>
@endsection
