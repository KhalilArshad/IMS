@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
       

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Driver Daily Sale Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="row">
                    <div class="col-12">
                        @if (Session::has('status'))
                        <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show" id="dismiss">
                            <div class="text-white">{{ Session::get('message')}}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
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
                    <div class="row  mb-4">
                          <?php
                            $currentDate = date('Y-m-d');
                            ?>
                            <form method="GET"  action="{{ url('getDriverDailySaleReport') }}" class="d-flex flex-wrap gap-2">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="driver">Driver</label>
                                    <select name="driver_id" id="driver_id" class="form-control">
                                        <option value="">Select driver</option>
                                         @foreach($drivers as $driver)
                                         <option value="{{ $driver->id }}" {{ old('driver', $oldDriverId) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', $oldDateFrom ?? $currentDate) }}">
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to', $oldDateTo ?? $currentDate) }}">
                                </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_to"></label>
                                <div class="form-group align-self-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                                </div>
                            </form>
                        </div>
                        <div class="row p-2">
                            <div class="col-12">
                              
                                    <div class="container border rounded">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                <th>Sr #</th>
                                                <th>Customer</th>
                                                <th>Invoice No</th>
                                                <th>Item</th>
                                                <th>Purchase Price</th>
                                                <th>Selling Price</th>
                                                <th>Quantity</th>
                                                <th>total</th>
                                                <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php($i = 1)
                                        @php($totalProfit = 0)
                                            @foreach ($invoiceChildren  as $child)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $child->invoice->customer->name }}</td>
                                                <td>{{ $child->invoice->invoice_no }}</td>
                                                <td> {{ $child->items->name }}</td>
                                                <td>{{ $child->purchase_price }}</td>
                                                <td>{{ $child->selling_price}}</td>
                                                <td>{{ $child->quantity}}</td>
                                                <td>{{ $child->total}}</td>
                                                <td>{{ $child->profit}}</td>
                                            </tr>
                                            @php($totalProfit += $child->profit)
                                            @php($i++)
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                             <div class="text-center mt-4">
                                <strong>Total Bills: <u>{{$total_bills}}</u></strong> | &nbsp;&nbsp;
                                <strong>Total Discount: <u>{{$total_discounts}}</u></strong> | &nbsp;&nbsp;
                                <strong>Total After Discount: <u>{{$total_after_discount}}</u></strong> | &nbsp;&nbsp;
                                <strong>Total Paid Amount: <u>{{$total_paid_amount}}</u></strong> | &nbsp;&nbsp;
                                <strong>Total Remaining to Customer: <u>{{$total_remaining}}</u></strong>
                                <strong>Total Profit: <u>{{$totalProfit}}</u></strong>
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

@section("script")
    <script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <script>
  $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: true,
            'searching': true,
            'paging': true,
            'sorting': false,
            'info': false,
            buttons: ['pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });

    </script>

    
@endsection