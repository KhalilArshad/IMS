@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<style>
/* Custom CSS for Select2 to match the form input styles */
.select2-container .select2-selection--single {
    height: 38px; 
}
.select2-container--default .select2-selection--single {
    border: 1px solid #ccc; 
    border-radius: 4px; 
    background-color: #fff;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px; 
    padding-left: 8px; 
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px; 
    position: absolute;
    top: 1px; 
    right: 1px; 
    width: 20px; 
}

</style>
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
                        <li class="breadcrumb-item active" aria-current="page">Supplier Ledger</li>
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
                        <form class="form-horizontal" action="{{ url('getSupplierLedger') }}" method="post">
                    <div class="row  mb-2">
                        @csrf
                        <div class="col-sm-3">
                        <div class="form-group">
                            <label for="" class="form-label">Supplier Name</label>
                                <select class="select2-single form-control" name="supplier_id" id="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" @if($supplier->id == $supplier_id) selected @endif>{{$supplier->name}}</option>
                                    @endforeach
                            
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <div class="form-group">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from" id="from" class="form-control" value="" placeholder="Enter date">
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to" id="to" class="form-control" value="" placeholder="Enter date">
                            </div>
                        </div>
                        <div class="col-sm-2">
                        <div class="form-group">
                                    <label for="" class="form-label">&nbsp;</label>
                                    <button type="submit"  id="search" class="form-control btn btn-info" ><i class="fadeIn animated bx bx-search"></i> Search</button>
                                </div>
                        </div>
                    </form>
                       <div class="col-sm-1">
                        <div class="form-group">
                                    <label for="" class="form-label">&nbsp;</label>
                                    <button type="button"  id="payButton"  class="form-control btn btn-info">Pay</button>
                                </div>
                        </div>
                </div>
                   
                    <hr>
                    <div id="alertPlaceholder" class="container mt-3"></div>
                          
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                      <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Supplier Name</th>
                                                <th>PO No</th>
                                                <th>Date</th>
                                                <th>previous Balance</th>
                                                <th>Total Bill</th>
                                                <th>Payment</th>
                                                <th>Remaining</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if (isset($flag))
                                        @php($i = 1)
                                            @foreach ($supplierLedgerArray  as $SupplierLedger)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $SupplierLedger->supplier->name }}</td>
                                                <td>{{ $SupplierLedger->purchaseOrder->po_no??'' }}</td>
                                                <td>{{ $SupplierLedger->date}}  </td>
                                               
                                                <td> {{ $SupplierLedger->previous_balance}}</td>
                                                <td> {{ $SupplierLedger->total_bill}}</td>
                                                <td> {{ $SupplierLedger->payment}}</td>
                                                <td> {{ $SupplierLedger->remaining}}</td>
                                                <td> {{ $SupplierLedger->description}}</td>
                                            </tr>
                                            @php($i++)
                                            @endforeach
                                       
                                        </tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total Remaining</td>
                                            <td></td>
                                            <td>{{$remaining}}</td>
                                        </tr>
                                        @endif
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
            $('#supplier_id').select2({
                placeholder: "{{ __('Select supplier') }}",
                allowClear: true
            });
    });
    $(document).ready(function() {
    $('#payButton').click(function() { // Attach the click event directly to the Pay button
        var supplierId = $('#supplier_id').val();  // Get the selected supplier's ID

        if (supplierId) {  // Check if a supplier ID is selected
            var url = "supplier-payable?id=" + supplierId;  // Create the URL
            window.location.href = url;  // Redirect to the URL
        } else {
            alert('Please select a supplier first.');  // Alert if no supplier is selected
        }
    });
});
    </script>

    
@endsection