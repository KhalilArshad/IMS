
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
            <div class="breadcrumb-title pe-3">Employees</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('getEmployees') }}">Employees List</a></li>
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
                        <!-- <div class="card"> -->
                            <div class="card-header input-title">
                                <h4>{{__('Generate Payroll Last month: ')}} {{$lastMonth}}</h4>
                            </div>
                            <!-- <div class="card-body"> -->

                                <form method="post" action="{{ url('savePayroll') }}" autocomplete="off">
                                    @csrf
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Employee Name<span
                                                    class="text-danger"> *</span></label>
                                                <input type="text" disabled name="name" required class="form-control" id="name" value="{{$employee->name}}" placeholder="Enter Name">
                                                <input type="hidden" name="employee_id" required class="form-control" id="employee_id" value="{{$employee->id}}" placeholder="Enter Name">
                                               
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="remaining_amount" class="form-label">Remaining Amount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" readonly name='remaining_amount' value="{{$employee->remaining??0}}"  id="remaining_amount" placeholder="Enter Email">

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="advance" class="form-label">Advance Amount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" readonly name='advance' value="{{$employee->advance??0}}"  id="advance" placeholder="Enter Email">

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Month<span
                                                            class="text-danger"> *</span></label>
                                                            <select name="month[]" id="month" required class="form-control" multiple>
                                                                <option value="">Select Month</option>
                                                                <option value="January">January</option>
                                                                <option value="February">February</option>
                                                                <option value="March">March</option>
                                                                <option value="April">April</option>
                                                                <option value="May">May</option>
                                                                <option value="June">June</option>
                                                                <option value="July">July</option>
                                                                <option value="August">August</option>
                                                                <option value="September">September</option>
                                                                <option value="October">October</option>
                                                                <option value="November">November</option>
                                                                <option value="December">December</option>
                                                            </select>

                                        </div>
                                        </div>
                                      
                                        
                                       </div>
                                       <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="paid_in_advance" class="form-label" style="color: red;">Paid In Advance</label>
                                                <input type="number"  name="paid_in_advance"  class="form-control" id="paid_in_advance" value="0"  placeholder="Enter amount" required onchange="calculateRemaining()" onkeyup="calculateRemaining()">
                                            </div>
                                        </div>
                                  
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="overtime" class="form-label">OverTime</label>
                                                <input type="number"  name="overtime" value="0" class="form-control" id="overtime" placeholder="Enter overtime" onchange="calculateRemaining()" onkeyup="calculateRemaining()">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="current_paid" class="form-label">Current Paid<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control"  name='current_paid'   id="current_paid" placeholder="Current paid Salary" value="0" onchange="calculateRemaining()" onkeyup="calculateRemaining()">

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="total_remaining" class="form-label">Total Remaining<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" readonly name='total_remaining'   id="total_remaining" placeholder="Total Remaining">

                                            </div>
                                        </div>
                                       </div>

                                 

                                    <div class="mb-3 mt-3">
                                        <div class="d-grid">
                                            <button  type="submit" class="btn btn-info">Save</button>
                                        </div>
                                </div>
                                </div>



                                    <!-- </div> -->
                                    <!-- </div> -->


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
    $('#month').select2({
        placeholder: "Select Month",
        allowClear: true
    });
    console.log("jQuery is ready");
    calculateRemaining();
});
              function calculateRemaining() {
                        var remaining_amount = parseFloat($('#remaining_amount').val());
                        var paid_in_advance = parseFloat($('#paid_in_advance').val());
                        var advance = parseFloat($('#advance').val());
                        var current_paid = parseFloat($('#current_paid').val());
                        var overtime = parseFloat($('#overtime').val());
                        var total_remaining1 = remaining_amount-paid_in_advance  + overtime;
                        if (current_paid > total_remaining1) {
                            alert("Current Paid  cannot be greater than the total remaining.");
                            $('#current_paid').val(0);
                            current_paid = 0;
                            total_remaining = total_remaining1 - current_paid;
                        }else{

                            total_remaining = total_remaining1 - current_paid;
                        }
                        if (paid_in_advance > advance) {
                            alert("Paid in advance cannot be greater than the advance amount.");
                            $('#paid_in_advance').val(advance);
                            paid_in_advance = advance;
                            total_remaining = total_remaining1;
                        }
                     
                        
                        // total_remaining = overtime + total_remaining;
                        if (!isNaN(total_remaining)) {
                            $('#total_remaining').val(total_remaining.toFixed(2));
                        } else {
                            $('#total_remaining').val('');
                        }
                    }

    </script>

@endsection
