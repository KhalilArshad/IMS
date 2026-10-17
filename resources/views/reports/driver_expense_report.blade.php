@extends('layouts.app')

@section('wrapper')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            {{-- ============ BREADCRUMB ============ --}}
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Reports</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Driver Expense Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            {{-- ============ FILTER CARD ============ --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ url('driverExpenseReport') }}" class="d-flex flex-wrap gap-2">
                                <div class="col-sm-2">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="{{ $fromDate }}">
                                </div>
                                <div class="col-sm-2">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="{{ $toDate }}">
                                </div>
                                <div class="col-sm-3">
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
                                <div class="col-sm-2">
                                    <label for="expense_type">Expense Type</label>
                                    <select name="expense_type" id="expense_type" class="form-control">
                                        <option value="">All Types</option>
                                        @foreach ($allExpenseTypes as $type)
                                            <option value="{{ $type }}"
                                                {{ request('expense_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <button id="printExpenseReportBtn" type="button"
                                            class="btn btn-success">Print</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ REPORT (ONE FRAME) ============ --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                Driver Expense Report
                                <small class="text-muted">({{ $fromDate }} to {{ $toDate }})</small>
                            </h5>
                            <div>
                                <span class="badge bg-info">{{ count($driverSummary) }} Drivers</span>
                                <span class="badge bg-secondary">{{ $totalRecords }} Records</span>
                                <span class="badge bg-danger">Total: {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>

                        <div class="card-body" id="expensePrintArea">

                            {{-- ---------- A) DRIVER-WISE SUMMARY ---------- --}}
                            <h6 class="mb-2">Driver Wise Summary</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Driver Name</th>
                                            @foreach ($columnTypes as $type)
                                                <th class="text-end">{{ $type }}</th>
                                            @endforeach
                                            <th class="text-end">Records</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($driverSummary as $i => $row)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td><strong>{{ $row->driver_name }}</strong></td>
                                                @foreach ($columnTypes as $type)
                                                    <td class="text-end">
                                                        {{ number_format($row->by_type->get($type, 0), 2) }}</td>
                                                @endforeach
                                                <td class="text-end">{{ $row->count }}</td>
                                                <td class="text-end fw-bold text-danger">
                                                    {{ number_format($row->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($columnTypes) + 4 }}"
                                                    class="text-center py-4 text-muted">
                                                    No expenses found for the selected filter.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if (count($driverSummary) > 0)
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td colspan="2" class="text-end">Grand Total:</td>
                                                @foreach ($columnTypes as $type)
                                                    <td class="text-end">{{ number_format($typeTotals->get($type, 0), 2) }}
                                                    </td>
                                                @endforeach
                                                <td class="text-end">{{ $totalRecords }}</td>
                                                <td class="text-end text-danger">{{ number_format($grandTotal, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>

                            {{-- ---------- B) DETAILED EXPENSES ---------- --}}
                            <h6 class="mb-2">Detailed Expenses</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Date</th>
                                            <th>Vehicle</th>
                                            <th>Expense Type</th>
                                            <th>Description</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($expensesByDriver as $rows)
                                            <tr class="table-secondary">
                                                <td colspan="6"><strong>Driver:
                                                        {{ $rows->first()->driver_name }}</strong>
                                                </td>
                                            </tr>
                                            @foreach ($rows as $k => $e)
                                                <tr>
                                                    <td>{{ $k + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($e->date)->format('d-m-Y') }}</td>
                                                    <td>{{ $e->vehicle_name }} ({{ $e->registration_no }})</td>
                                                    <td>{{ $e->expense_type }}</td>
                                                    <td>{{ $e->description ?? '-' }}</td>
                                                    <td class="text-end">{{ number_format($e->amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="fw-bold">
                                                <td colspan="5" class="text-end">Sub Total
                                                    ({{ $rows->first()->driver_name }})
                                                    :</td>
                                                <td class="text-end">{{ number_format($rows->sum('amount'), 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    No expenses found for the selected filter.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($totalRecords > 0)
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td colspan="5" class="text-end">Grand Total:</td>
                                                <td class="text-end text-danger">{{ number_format($grandTotal, 2) }}</td>
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
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#printExpenseReportBtn').click(function() {

                var title = 'Driver Expense Report ({{ $fromDate }} to {{ $toDate }})';
                var body = $('#expensePrintArea').html();

                // Create HIDDEN iframe (user never sees a new page)
                var iframe = document.createElement('iframe');
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                document.body.appendChild(iframe);

                var doc = iframe.contentWindow.document;
                doc.open();
                doc.write(
                    '<html><head><title>Driver Expense Report</title>' +
                    '<style>' +
                    '@page { size: landscape; margin: 10mm; }' +
                    'body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }' +
                    'h3 { text-align: center; margin: 0 0 12px; }' +
                    'h6 { margin: 12px 0 6px; font-size: 13px; }' +
                    'table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }' +
                    'th, td { border: 1px solid #000; padding: 4px 6px; }' +
                    'th { background: #eee; }' +
                    '.text-end { text-align: right; }' +
                    '.text-center { text-align: center; }' +
                    '.table-secondary td { background: #ddd; }' +
                    '</style></head><body>' +
                    '<h3>' + title + '</h3>' + body +
                    '</body></html>'
                );
                doc.close();

                // Print as soon as iframe finishes loading
                iframe.onload = function() {
                    setTimeout(function() {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        setTimeout(function() {
                            document.body.removeChild(iframe);
                        }, 1000);
                    }, 200);
                };
            });
        });
    </script>
@endsection
