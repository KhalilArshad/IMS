@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
       
        <!--end breadcrumb-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                            @if(!empty($employee->date_of_joining))

                                                {{ date('d-F-Y', strtotime($employee->date_of_joining)) }}</p>
                                            
                                            @endif
                                        <br>
                                        <br>
                                        <h4>Status</h4>
                                      
                                            <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Active</span>
                                            </td>
                                    

                                    </div>
                                </div>
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
                                            <label for="salary" class="form-label">Salary<span
                                                    class="text-danger"> *</span></label>
                                            <div class="input-group" id="db">
                                                <input type="number" class="form-control" id="salary"
                                                    name="salary" placeholder="Enter Hourly Salary"
                                                    value="{{ $employee->salary }}">
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
                                        <div class="col-4 col-sm-2  text-center"> <span class="text-info">Employees Advance</span> </div>
                                        <div class="col-4 col-sm-5 "><hr></div>
                                    </div>

                                    <div class="row mt-5">
                                       

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
                                                                    <p class="mb-0 text-white badge bg-success rounded-pill"> {{ $employee->advance }}</p>
                                                                    <h5 class="my-1 text-success"></h5>
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
                                                            <h4 class="text-success text-center">Remaining</h4>
                                                            <hr>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <p class="mb-0 text-secondary">Total Remaining</p>
                                                                    <h5 class="my-1 text-success"></h5>
                                                                </div>
                                                                <div class=" ms-auto">
                                                                    <p class="mb-0 text-white badge bg-success rounded-pill"> {{ $employee->remaining }}</p>
                                                                    <h5 class="my-1 text-success"></h5>
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