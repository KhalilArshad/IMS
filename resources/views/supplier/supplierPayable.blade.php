
@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<style>
    .custom-input {
    width: 90px;
    /* height: 20px; */
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('create-purchase-order') }}">Payment Against Supplier</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">

                    @if (Session::has('status'))
                    <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show" id="dismiss">
                        <div class="text-white">{{ Session::get('message')}}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                            {{ Session::forget('status') }}
                            {{ Session::forget('message') }}
                    </div>
                    @endif

                    <div class="card-body">
                        <div class="card">
                            <div class="card-body card-body-paddding">

                                <form method="post" action="{{ url('SaveSupplierPayable') }}" autocomplete="off">
                                    @csrf

                                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Supplier Name<span
                                                    class="text-danger"> *</span></label>
                                              <input type="text" class="form-control" name='name' value="{{$supplier->name}}" id="name" readonly placeholder="Enter name" required>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date<span
                                                    class="text-danger"> *</span></label>
                                                <input type="date"  name="date" required class="form-control" value="{{$system_date}}" placeholder="Enter date">
                                            </div>
                                        </div>
                                    
                                    </div>
                                  
                                    <!-- <button type="button" class="btn btn-bordered btn-primary" style="float: right;" onclick="addItem()">Add New Item</button> -->
                                    <div class="row mt-4">
                                    <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="previous_balance" class="form-label">Previous Balance<span
                                                    class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" name='previous_balance' value="{{$supplier->previous_balance}}" readonly id="previous_balance" placeholder="Enter Email" required>

                                            </div>
                                        </div>
                                     
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="paid_amount" class="form-label">Paid Amount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" name="paid_amount" required class="form-control" id="paid_amount" placeholder="Enter Paid Amount" value="0" onchange="calculateRemaining()" onkeyup="calculateRemaining()">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="remaining" class="form-label">Remaining<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='remaining' id="remaining" placeholder="Remaining" readonly required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3 d-flex justify-content-between">
                                        <a href="{{ route('supplier-list') }}" class="btn btn-secondary">Back</a>
                                        <button type="submit" class="btn btn-info">Save</button>
                                    </div>
                                </div>



                                    </div>
                                    </div>


                                </div><!--end row-->
                            </form>
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
   $(document).ready(function () {
    $('#example').DataTable();
    console.log("jQuery is ready");

   });
              
         function calculateRemaining() {
                   var previous_balance = parseFloat($('#previous_balance').val());
                   var paidAmount = parseFloat($('#paid_amount').val());
                   var remaining = previous_balance - paidAmount;

                   if (paidAmount > previous_balance) {
                            // Show error message
                            alert('paid Amount must be less than or equal to previous balance');
                            $('#paid_amount').val(''); 
                            return;
                        }

                   if (!isNaN(remaining)) {
                     $('#remaining').val(remaining.toFixed(2));
                   } else {
                     $('#remaining').val('');
                   }
                 }

    </script>

@endsection
