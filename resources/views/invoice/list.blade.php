@extends('layouts.app')
@section('style')
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section('wrapper')
    <style>
        .disabled-link {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .disabled-icon {
            pointer-events: none;
        }

        /* smaller filter controls */
        .filter-form .form-control,
        .filter-form .form-select,
        .filter-form .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            height: calc(1.5em + 0.5rem + 2px);
        }

        .filter-form .form-label {
            font-size: 0.75rem;
        }
    </style>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Sales</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="row">
                <div class="col-12">
                    @if (Session::has('status'))
                        <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show"
                            id="dismiss">
                            <div class="text-white">{{ Session::get('message') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            {{ Session::forget('status') }}
                            {{ Session::forget('message') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="alertPlaceholder" class="container mt-3"></div>
                            <div class="row p-2">
                                <div class="col-12">

                                    {{-- Date filter (left) + Show entries + Search (right) in one row --}}
                                    <form method="GET" action="{{ url('invoice-list') }}" id="filterForm"
                                        class="row g-2 mb-3 align-items-end filter-form">

                                        {{-- Left side: Date filter --}}
                                        <div class="col-md-6">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label mb-0">From Date</label>
                                                    <input type="date" name="date_from" class="form-control"
                                                        value="{{ request('date_from') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label mb-0">To Date</label>
                                                    <input type="date" name="date_to" class="form-control"
                                                        value="{{ request('date_to') }}">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm me-2">Filter</button>
                                                    <a href="{{ url('invoice-list') }}"
                                                        class="btn btn-secondary btn-sm">Reset</a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Right side: Show entries + Search --}}
                                        <div class="col-md-6">
                                            <div class="row g-2 align-items-end justify-content-end">
                                                {{-- Show entries --}}
                                                <div class="col-md-4">
                                                    <label class="form-label mb-0">Show</label>
                                                    <select name="per_page" id="perPage" class="form-select">
                                                        @foreach ([10, 25, 50, 100] as $size)
                                                            <option value="{{ $size }}"
                                                                {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                                                {{ $size }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- Search --}}
                                                <div class="col-md-8">
                                                    <label class="form-label mb-0">Search</label>
                                                    <input type="text" name="search" id="searchInput"
                                                        class="form-control" placeholder="Invoice no, customer, driver..."
                                                        value="{{ request('search') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:99%">
                                            <thead>
                                                <tr>
                                                    <th>Sr #</th>
                                                    <th>Driver</th>
                                                    <th>Customer Name</th>
                                                    <th>Invoice No</th>
                                                    <th>Date</th>
                                                    <th>Total Bill</th>
                                                    <th>Discount</th>
                                                    <th>Total After Discount</th>
                                                    <th>Current Payment</th>
                                                    <th>Remaining</th>
                                                    <th>Old Received</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($i = $invoices->firstItem() ?? 1)
                                                @forelse ($invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $invoice->driver->name }}</td>
                                                        <td>{{ $invoice->customer->name }}</td>
                                                        <td>{{ $invoice->invoice_no }}</td>
                                                        <td>{{ $invoice->date }}</td>
                                                        <td>{{ $invoice->total_bill }}</td>
                                                        <td>{{ $invoice->discount }}</td>
                                                        <td>{{ $invoice->total_after_discount }}</td>
                                                        <td>{{ $invoice->paid_amount }}</td>
                                                        <td>{{ $invoice->remaining }}</td>
                                                        <td>{{ $invoice->old_receive }}</td>
                                                        <td>{{ $invoice->status }}</td>
                                                        <td>
                                                            <div class="d-flex order-actions">
                                                                <a href="view-invoice/{{ $invoice->id }}" class="ms-3"
                                                                    target="_blank">
                                                                    <i class='bx bxs-show text-info'></i>
                                                                </a>
                                                                <a href="invoice-delete/{{ $invoice->id }}" class="ms-3"
                                                                    onclick="return confirm('Are you sure you want to delete this Invoice?')">
                                                                    <i class='bx bxs-trash text-danger'></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php($i++)
                                                @empty
                                                    <tr>
                                                        <td colspan="13" class="text-center">No invoices found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        {{-- Laravel pagination --}}
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                Showing {{ $invoices->firstItem() ?? 0 }} to
                                                {{ $invoices->lastItem() ?? 0 }} of {{ $invoices->total() }} invoices
                                            </div>
                                            <div>
                                                {{ $invoices->links('pagination::bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTable pagination + search disabled → Laravel handles both
            $('#example').DataTable({
                paging: false,
                info: false,
                searching: false,
                ordering: false
            });

            // "Show entries" dropdown → auto submit form
            $('#perPage').on('change', function() {
                $('#filterForm').submit();
            });

            // Press Enter in search box → submit form
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#filterForm').submit();
                }
            });
        });
    </script>
@endsection
