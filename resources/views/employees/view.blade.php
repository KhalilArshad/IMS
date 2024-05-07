@extends('layouts.app')
@section('style')
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
@endsection

@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
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
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-4 border rounded">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="assets/images/avatars/avatar-101.png" alt="Admin"
                                        class="rounded-circle p-1 bg-primary" width="110">
                                    <div class="mt-3">
                                        <h4>{{ $employee->name }}</h4>
                                        <b>
                                            <p class="text-secondary mb-1">Joined At</p>
                                        </b>
                                        <p class="text-muted font-size-sm">
                                            {{ date('d-F-Y', strtotime($employee->date_of_joining)) }}</p>

                                        {{-- @if ($user->is_free_user == 0)
                                            <div
                                                class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                                <i class="bx bxs-circle align-middle me-1"></i>Paid</div>
                                        @else
                                            <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                                <i class="bx bxs-circle align-middle me-1"></i>Free</div>
                                        @endif --}}
                                        <br>
                                        <br>
                                        <h4>Status</h4>
                                        @if ($employee->is_banned == 1)
                                            <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Active</span>
                                            </td>
                                        @else
                                            <span class="badge bg-gradient-bloody text-white shadow-sm w-100">In
                                                Active</span>
                                        @endif

                                    </div>
                                </div>



                                <hr class="my-4" />
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="text-secondary">Paid Balance</span>

                                        <h6 class="mb-0">{{ number_format($totalPaidAmount, 0) }} </h6>
                                    </li>


                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="text-secondary">UnPaid Balance</span>
                                        <h6 class="mb-0">{{ number_format($totalUnPaidAmount, 0) }} </h6>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="text-secondary">Team Lead name</span>
                                        <h6 class="mb-0">{{$employee->teamLead->name}} </h6>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                                    </li>



                                </ul>
                                <br>
                                <hr class="my-4" />
                                <button type="button" class=" form-control btn btn-outline-info" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-bs-whatever="@mdo" >  Change Status</button>
                                <br>
                                <br>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="text-secondary"><b>Note : </b>{{ $employee->reference_note }}</span>

                                </li>


                                    <hr class="my-" />
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="form-body">
                                    <form  class="row g-3 needs-validation" novalidate method="POST"
                                        action="{{ url('updateEmployee') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $employee->id }}">
                                        <div class="row">
                                            <div class="col-4 col-sm-5 "><hr></div>
                                            <div class="col-4 col-sm-2  text-center"> <span class="text-info">Personal Info</span> </div>
                                            <div class="col-4 col-sm-5 "><hr></div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="name" class="form-label">Name <span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $employee->name }}" placeholder="Name" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Your Name</div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="name" class="form-label">Email <span class="text-danger">
                                                    *</span></label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{ $employee->email }}" placeholder="email" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Your Email</div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="name" class="form-label">Password <span class="text-danger">
                                                    </span></label>
                                            <input type="text" class="form-control" id="password" name="password"
                                                value="" placeholder="Password">
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Password</div>
                                        </div>



                                        <div class="col-sm-4">
                                            <label for="cnic_no" class="form-label">Cnic No
                                                <span class="text-danger"> *</span></label>
                                            <input type="number" class="form-control" id="cnic_no" name="cnic_no"
                                                value="{{ $employee->cnic_no }}" placeholder="Enter Your Cnic No"
                                                minlength="5" manlength="13">
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Your Cnic No</div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="designation" class="form-label">Designation
                                                <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="designation" name="designation"
                                                value="{{ $employee->designation }}" placeholder="Enter Your Designation"
                                                minlength="5" manlength="13">
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Your Designation</div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="date_of_joining" class="form-label">Date of Joining<span
                                                    class="text-danger"> *</span></label>
                                            <div class="input-group" id="date_of_joining">
                                                <input type="date" class="form-control" id="date_of_joining"
                                                    name="date_of_joining" placeholder="Enter Date of Joining"
                                                    value="{{ $employee->date_of_joining }}">
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please Enter Your Date of Joining</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="hourly_salary" class="form-label">Hourly Salary<span
                                                    class="text-danger"> *</span></label>
                                            <div class="input-group" id="db">
                                                <input type="number" class="form-control" id="hourly_salary"
                                                    name="hourly_salary" placeholder="Enter Hourly Salary"
                                                    value="{{ $employee->hourly_salary }}">
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please Enter Hourly Salary</div>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <label for="phone_no" class="form-label">Phone No
                                                <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="phone_no" name="phone_no"
                                                placeholder="Phone No"  value="{{ $employee->phone_no }}">
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Your Phone No</div>
                                        </div>

                                        <div class="col-sm-4 mt-4">
                                            <label class="form-control-label font-weight-bold">City<span
                                                class="text-danger"> *</span> </label>
                                            <select class="form-control" name="city_id" id="city_id">
                                                <option value="">{{ __('Select City') }}</option>
                                                @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" {{ $city->id == $employee->city_id ? 'selected' : '' }}> {{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-12">
                                            <label for="inputAddressDescription" class="form-label">Address<span
                                                    class="text-danger"> *</span></label>
                                            <textarea class="form-control"  id="address" name="address" rows="2" placeholder="Address">{{ $employee->address }}</textarea>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please Enter Your Address</div>
                                        </div>



                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" id="submit-form" class="btn btn-info"><i
                                                        class='bx bx-user'></i>Save</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row  mt-5">
                                        <div class="col-4 col-sm-5 "><hr></div>
                                        <div class="col-4 col-sm-2  text-center"> <span class="text-info">Employees Info</span> </div>
                                        <div class="col-4 col-sm-5 "><hr></div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-sm-4">
                                            <div class="card radius-10 border-start border-0 border-3 border-info">
                                                <div class="card-body">

                                                    <a href="">
                                                        <div class="p-3 border radius-10">
                                                            <h4 class="text-info text-center">Food Amount</h4>
                                                            <hr>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <p class="mb-0 text-secondary">Total Food Amount</p>
                                                                    <h5 class="my-1 text-info"></h5>
                                                                </div>
                                                                <div class=" ms-auto">
                                                                    <p class="mb-0 text-white badge bg-info rounded-pill"> {{ $totalFoodAmount }}</p>
                                                                    <h5 class="my-1 text-info"></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="card radius-10 border-start border-0 border-3 border-success">
                                                <div class="card-body">

                                                    <a href="#">
                                                        <div class="p-3 border radius-10">
                                                            <h4 class="text-success text-center">Advance</h4>
                                                            <hr>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <p class="mb-0 text-secondary">Total Advance</p>
                                                                    <h5 class="my-1 text-success"></h5>
                                                                </div>
                                                                <div class=" ms-auto">
                                                                    <p class="mb-0 text-white badge bg-success rounded-pill"> {{ $totalAdvanceAmount }}</p>
                                                                    <h5 class="my-1 text-success"></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="card radius-10 border-start border-0 border-3 border-danger">
                                                <div class="card-body">

                                                    <a href="#">
                                                        <div class="p-3 border radius-10">
                                                            <h4 class="text-danger text-center">Working Days</h4>
                                                            <hr>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <p class="mb-0 text-secondary">Total Working Days</p>
                                                                    <h5 class="my-1 text-danger"></h5>
                                                                </div>
                                                                <div class=" ms-auto">
                                                                    <p class="mb-0 text-white badge bg-danger rounded-pill"> {{ $totalWorkingDays }}</p>
                                                                    <h5 class="my-1 text-danger"></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
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

            <div class="row  mt-3">
                <div class="col-4 col-sm-5 "><hr></div>
                <div class="col-4 col-sm-2  text-center"> <span class="text-info">Employee Attandance Info</span> </div>
                <div class="col-4 col-sm-5 "><hr></div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                               <div class="d-lg-flex align-items-center mb-4 gap-3">
                                                <div class="ms-auto">

                                                    {{-- <a href="{{ url('createAttandances') }}" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add Attandance</a> --}}

                                                </div>
                                            </div>

                                            <hr>
                                 <div class="row  mb-4">

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Date From</label>
                                                        <input type="date" name="from" id="from" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Date To</label>
                                                        <input type="date" name="to" id="to" class="form-control">
                                                        <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{ $employee->id }}">
                                                    </div>
                                                </div>


                                                <div class="col-sm-3 ">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">&nbsp;</label>
                                                                <button onclick="validate()" type="button" name="search" id="search" class="form-control btn btn-info" ><i class="fadeIn animated bx bx-search"></i> Search</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">&nbsp;</label>
                                                                <button onclick="reset()" type="button" name="reset" id="reset" class="form-control btn btn-danger"><i class="fadeIn animated bx bx-reset"></i>Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="row p-2">
                                                <div class="col-12">
                                                    <div class="row">
                                                    <div class="col-5 col-sm-1 mb-2">
                                                        <div class="form-group">
                                                            <select name="records" id="records" class="form-select" onchange="validate()">
                                                                <option value="10">10</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                                <option value="500">500</option>
                                                                <option value="All">All</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <div class="table-responsive" id="responsiveDiv">
                                                        <table id="example2"  class="table table-striped table-bordered" style="width:99%">

                                                            <thead>
                                                                <tr>
                                                                    <th>Sr #</th>
                                                                    <th>Name</th>
                                                                    <th>Designation</th>
                                                                    <th>Date</th>
                                                                    <th>Status</th>
                                                                    <th>Wage Rate</th>
                                                                    <th>Rate Per Hour</th>
                                                                    <th>Check In</th>
                                                                    <th>Check Out</th>
                                                                    <th>Total Time</th>
                                                                    <th>Perday Salary</th>
                                                                    <th>Payment Status</th>

                                                                    <th>Action</th>

                                                                </tr>
                                                            </thead>
                                                            {{-- <tbody id="ajaxData"> --}}
                                                                <tbody >
                                                                    @php($srno = ($attendances->perPage() * ($attendances->currentPage() - 1)))
                                                                @foreach ($attendances as $attendance)
                                                                @php($srno++)

                                                                <tr>

                                                                    <td>{{ $srno }}</td>
                                                                    <td>{{ $attendance->user->name }}</td>
                                                                    <td>{{ $attendance->user->designation }}</td>
                                                                    <td>{{ date('d-M-Y', strtotime($attendance->date)) }}</td>

                                                                    <td>
                                                                        <span class="badge bg-gradient-quepal text-white shadow-sm w-100">{{ $attendance->status  }}</span>

                                                                    </td>
                                                                    <td>{{ $attendance->hourly_salary * 8 }}</td>
                                                                    <td>{{ $attendance->hourly_salary }}</td>
                                                                    <td>
                                                                        {{ $attendance->check_in }}

                                                                    </td>
                                                                    <td>
                                                                        {{ $attendance->check_out }}

                                                                    </td>


                                                                        <td>{{ calculateTotalTime($attendance->check_in, $attendance->check_out) }}</td>


                                                                    <td>
                                                                        {{ $attendance->perday_salary }}
                                                                        {{-- {{ formatNumberWithoutDecimals($attendance->perday_salary) }} --}}

                                                                    </td>


                                                                    <td>
                                                                        @if($attendance->payment_status != 'Paid')
                                                                        <form method="POST" action="{{ url('clickToPaid') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="id" value="{{ $attendance->id }}">
                                                                            <button type="submit" class="btn btn-info px-3"><i class="bx bxs-plus-square"></i> Click To Paid</button>
                                                                        </form>
                                                                        @else
                                                                        <span class="btn btn-success px-3"><i class="bx bxs-check-square"></i>{{ $attendance->payment_status }}</span>
                                                                        @endif
                                                                        </td>

                                                                    <td>
                                                                        <div class="d-flex order-actions">
                                                                            @if($attendance->payment_status != 'Paid')
                                                                            <a href="editAttandances?id={{ $attendance->id }}" class="text-info">
                                                                                <i class="fadeIn animated bx bx-edit-alt"></i>
                                                                            </a>
                                                                            @else <a href="editAttandances?id={{ $attendance->id }}" class="text-info" style="color: #999; /* Change text color to gray */
                                                                                pointer-events: none;">
                                                                                <i class="fadeIn animated bx bx-edit-alt"></i>
                                                                            @endif
                                                                            {{-- &nbsp;&nbsp;
                                                                            <a href="viewEmployees?id={{ $attendance->id }}" class="text-warning">
                                                                                <i class="fadeIn animated bx bx-show-alt"></i>
                                                                            </a> --}}
                                                                            {{-- &nbsp;&nbsp;
                                                                            <a href="deleteEmployee?id={{ $attendance->id }}" class="text-danger" onclick="return confirm('Are you sure you want to delete this team lead?')">
                                                                                <i class="fadeIn animated bx bx-trash-alt"></i>
                                                                            </a> --}}
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>

                                                        </table>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                Showing {{($attendances->currentpage()-1)*$attendances->perpage()+1}} to {{$attendances->currentpage()*$attendances->perpage()}} of  {{$attendances->total()}} entries
                                                            </div>
                                                            <div class="col-md-6">
                                                                <span class="pagination-span" >
                                                                    {{ $attendances->onEachSide(1)->links() }}
                                                                </span>
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


        <!--end row-->
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Employee Status')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('employeeStatusChange') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $employee->id }}">
                        <div class="mb-3">
                            <label class="form-control-label font-weight-bold">@lang('User Status') <span
                                    class="text-danger"> *</span></label>
                            <select class="form-control" name="is_banned">
                                <option >Select Status</option>
                                <option value="1" {{ $employee->is_banned == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $employee->is_banned == '0' ? 'selected' : '' }}>In Active
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-label font-weight-bold">@lang('Reference Note')<span
                                    class="text-danger"> *</span> </label>
                            <textarea rows="2" class="form-control" name="reference_note" placeholder="Reference Note" required></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')


    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });


    </script>
    <!--app JS-->

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

<script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!-- Add this in your head section -->







<!-- Add this at the end of your HTML, before the closing </body> tag -->
<!-- Add this in your head section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>


<script>
$(document).ready(function() {
    var table = $('#example2').DataTable({
        lengthChange: true,
        'searching': false,
        'paging': false,
        'sorting': false,
        'info': false,
        buttons: ['copy', 'excel', 'pdf', 'print']
    });

    table.buttons().container()
        .appendTo('#example2_wrapper .col-md-6:eq(0)');
});
</script>

<script>
    $(document).on('click', '.pagination-span a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('?')[1];
        searchByAjax(page);
    });

    function reset(){
        $('select').prop('selectedIndex',0);
        $("input[type=number]").val('');
        $("input[type=email]").val('');
        $("input[type=text]").val('');
        $("input[type=date]").val('');
        searchByAjax('page=1');
    }
    function validate(){
        searchByAjax('page=1');
    }

    function searchByAjax(page){
        let user_id = $("#user_id").val();
        let from = $("#from").val();
        let to = $("#to").val();
        let records = $("#records").val();


        if (records == 'All') {
            records = $("#total-records").val();
        }
        $.ajax({
                url: "{{route('search-searcSingleAttandances-ajax')}}?"+page,
                method: 'GET',
                data: {
                    user_id : user_id,
                    from : from,
                    to : to,
                    records : records,

                },
                success: function(result) {
                    $("#responsiveDiv").html(result);
                        var table = $('#example2').DataTable({
                        buttons: ['copy', 'excel', 'pdf', 'print'],
                        "info": false,
                        sort: false,
                        paging: false,
                    });

                    table.buttons().container()
                    .appendTo('#example2_wrapper .col-md-6:eq(0)');
                }
            });
        }

</script>




@endsection
