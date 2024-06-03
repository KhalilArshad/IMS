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
            <div class="breadcrumb-title pe-3">Supplier</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Voucher</li>
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
                    <div class="row mb-4">
                        <form method="GET" action="{{ url('payment-voucher') }}" class="d-flex flex-wrap gap-2">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control">
                                        <option value="">Select supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $supplier->id == $oldSupplierId ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="date_from">Date</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $oldDateFrom }}">
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
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                            <th>Sr #</th>
                                                                <th>Supplier Name</th>
                                                                <th>Date</th>
                                                                 <th>Previous Total</th>
                                                                <th>Paid Amount</th>
                                                                <th>Remaining</th>
                                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($paymentVouchers  as $vouchers)
                                            <tr>
                                            <td>{{ $i }}</td>
                                                                <td>{{ $vouchers->supplier->name }}</td>
                                                                <td>{{ $vouchers->date }}</td>
                                                                <td>{{ $vouchers->previous_balance }}</td>
                                                                <td>{{ $vouchers->paid_amount }}</td>
                                                                <td>{{ $vouchers->remaining }}</td>
                                                                <td>
                                                                <div class="d-flex order-actions">
                                                                <a href="view-voucher/{{ $vouchers->id }}" class="ms-3"  target="_blank"><i class='bx bxs-show  text-info'></i></a>
                                                                </div>
                                                            </td>
                                            </tr>
                                            @php($i++)
                                            @endforeach
                                        </tbody>

                                    </table>
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
    $(document).ready(function () {
            $('#example').DataTable();
    });
    
    </script>

    
@endsection