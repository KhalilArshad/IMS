
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('getEmployees') }}">Show Employees</a></li>
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
                            <div class="card-header input-title">
                                <h4>{{__('Add New Employee')}}</h4>
                            </div>
                            <div class="card-body card-body-paddding">

                                <form method="post" action="{{ url('saveEmployee') }}" autocomplete="off">
                                    @csrf
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name<span
                                                    class="text-danger"> *</span></label>
                                                <input type="text" name="name" required class="form-control" id="name" placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email<span
                                                    class="text-danger"> *</span></label>
                                                <input type="email" class="form-control" name='email' id="email" placeholder="Enter Email" required>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password<span
                                                    class="text-danger"> *</span></label>
                                                <input type="password" name="password" required class="form-control" id="password" autocomplete="new-password" placeholder="Enter Password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cnic_no" class="form-label">Cnic No<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='cnic_no' id="cnic_no" placeholder="Enter Cnic No" required>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label">Designation<span
                                                    class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" name='designation' id="designation" placeholder="Enter designation" required>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone_no" class="form-label">Phone No<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" name="phone_no" required class="form-control" id="phone_no" placeholder="Enter Phone No">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date_of_joining" class="form-label">Date Of Joining<span
                                                    class="text-danger"> *</span></label>
                                                <input type="date" name="date_of_joining" required class="form-control" id="date_of_joining" placeholder="Enter Date Of Joining">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hourly_salary" class="form-label">Salary<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" name="hourly_salary" required class="form-control" id="hourly_salary" placeholder="Enter Hourly Salary">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">

                                                <label for="inputAddressDescription" class="form-label">Address<span
                                                    class="text-danger"> *</span></label>
                                                 <textarea class="form-control"  id="address" name="address" rows="2" placeholder="Address"></textarea>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 mt-3">
                                        <div class="d-grid">
                                            <button  type="submit" class="btn btn-info">Save</button>
                                        </div>
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

    $('#productSave').click(function (event) {
        event.preventDefault();
        var form = $('#productForm');
        var formData = form.serialize();
        var route = form.data('route');
        console.log(route);

        $.ajax({
            url: route,
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);

                var status = response.status;
                var message = response.message;

                // Show dynamic alert based on the response
                var alertClass = status === 'success' ? 'success' : 'danger';
                var alertHTML = `
                    <div class="alert alert-${alertClass} alert-dismissible fade show">
                        <div class="text-white">${message}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Append the alert to a container (adjust the selector as needed)
                $('#alertContainer').html(alertHTML);
                form.trigger('reset'); // Clear the form fields

                // Automatically hide the alert after 1 second
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 4000);
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error); // Log the error to the console for debugging
            }
        });
    });
});


    </script>

@endsection
