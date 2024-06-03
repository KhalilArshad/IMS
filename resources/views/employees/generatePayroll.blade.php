
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
                                <h4>{{__('Generate Payroll For The Month Of:')}} {{$monthName}}</h4>
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
                                                <label for="salary" class="form-label">Salary<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" readonly name="salary" value="{{$employee->salary}}" required class="form-control" id="salary" placeholder="Enter Hourly Salary">
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
                                                <label for="remaining_amount" class="form-label">Remaining Amount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" readonly name='remaining_amount' value="{{$employee->remaining??0}}"  id="remaining_amount" placeholder="Enter Email">

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
                                                <label for="add_in_remaining" class="form-label" style="color: red;">Add In Remaining</label>
                                                <input type="number"  name="add_in_remaining"  class="form-control" id="add_in_remaining" value="0"  placeholder="Enter amount" required onchange="calculateRemaining()" onkeyup="calculateRemaining()">
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
                                                <label for="current_salary" class="form-label">Remaining Current Salary<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" readonly name='current_salary'   id="current_salary" placeholder="Remaining Current Salary">

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
    console.log("jQuery is ready");
    calculateRemaining();
});
              function calculateRemaining() {
                        var salary = parseFloat($('#salary').val());
                        var paid_in_advance = parseFloat($('#paid_in_advance').val());
                        var advance = parseFloat($('#advance').val());
                        var add_in_remaining = parseFloat($('#add_in_remaining').val());
                        var current_salary = salary -paid_in_advance - add_in_remaining;
                       
                        if (add_in_remaining > salary) {
                            alert("Add in Remaining cannot be greater than the Salary.");
                            $('#add_in_remaining').val(0);
                            // paid_in_advance = advance;
                            current_salary= salary; 
                        }
                        if (paid_in_advance > advance) {
                            alert("Paid in advance cannot be greater than the advance amount.");
                            $('#paid_in_advance').val(advance);
                            paid_in_advance = advance;
                            current_salary= salary; 
                        }
                        var checkPaidInAdvance_and_addInRemaining =add_in_remaining+paid_in_advance;
                        if (checkPaidInAdvance_and_addInRemaining > salary) {
                            alert("Paid in advance And Add in Remaining cannot be greater than the Salary.");
                            $('#paid_in_advance').val(0);
                            $('#add_in_remaining').val(0);
                            current_salary= salary; 
                        }
                        var overtime = parseFloat($('#overtime').val());
                        current_salary = overtime + current_salary;
                        if (!isNaN(current_salary)) {
                            $('#current_salary').val(current_salary.toFixed(2));
                        } else {
                            $('#current_salary').val('');
                        }
                    }

    </script>

@endsection
