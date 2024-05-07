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
            <div class="breadcrumb-title pe-3">Vehicle</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Vehicle List</li>
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
                           <div class="d-lg-flex align-items-center mb-4 gap-3">
                                    <div class="ms-auto">

                                     <!-- <a href="" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add New Supplier</a> -->
                                     <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal"><i class="bx bxs-plus-square"></i> Add New Vehicle</a>

                                    </div>
                            </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Name</th>
                                                <th>Registration No</th>
                                                <th>Modal</th>
                                                <th>Driver</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($vehicles  as $vehicle)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $vehicle->name }}</td>
                                                <td>
                                              
                                                   {{ $vehicle->registration_no}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $vehicle->modal}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $vehicle->driver->name??''}}
                                                  
                                                  </td>
                                            
                                                <td>
                                                
                                                 <div class="d-flex order-actions">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="getVehicleData('{{$vehicle->id}}')" class="ms-3"><i class='bx bxs-edit text-info'></i></a>
                                                    <a href="vehicle-delete/{{ $vehicle->id }}" class="ms-3" onclick="return confirm('Are you sure you want to delete this record?');"><i class='bx bxs-trash text-danger'></i></a>

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

                     <!-- Modal for adding a new supplier -->
            <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSupplierModalLabel">Add New Vehicle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                                 <div class="row">
                                 <div id="alertPlaceholderReq"></div>
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Name<span
                                                    class="text-danger"> *</span></label>
                                    <input type="text" name="name" required class="form-control" id="name" placeholder="Enter Name">
                                    <input type="hidden" name="update_vehicle_id" required class="form-control" id="update_vehicle_id" placeholder="Enter Name">
                                    </div>
                                    
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Registration No<span
                                                    class="text-danger"> *</span></label>
                                    <input type="text" name="reg_no" required class="form-control" id="reg_no" placeholder="Enter Phone No">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Modal</label>
                                    <input type="text" name="modal"  class="form-control" id="modal" placeholder="Enter Name">
                                    </div>

                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Driver<span
                                                    class="text-danger"> *</span></label>
                                      <select class="form-control selectric lang" name="driver_id" id="driver_id" required>
                                                        <option value="">{{ __('Select Driver') }}</option>
                                                        @foreach ($drivers as $driver)
                                                        <option value="{{ $driver->id }}"> {{ $driver->name }}</option>
                                                        @endforeach
                                        </select>
                                    </div>
                                 </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveVehicle();">Save changes</button>
                          
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
    function saveVehicle() {
        const name = $('#name').val();
        const reg_no = $('#reg_no').val();
        const driver_id = $('#driver_id').val();
        const modal = $('#modal').val();
        if (!name || !reg_no || !driver_id) {
        // Display error message for required fields
        $('#alertPlaceholderReq').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
        return; // Stop further execution
    }
        const csrf_token = '{{ csrf_token() }}';
            $.ajax({
            type: "POST",
            url: '{{ route("vehicle-save") }}',
            data: {
                _token: csrf_token, 
                name: name,
                reg_no: reg_no,
                driver_id: driver_id,
                modal: modal,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Vehicle Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

            // Clear form fields
            $('#name').val('');
            $('#phone_no').val('');
            $('#email').val('');
            // Optionally, hide the message after a delay
            setTimeout(function() {
                window.location.reload();
            }, 1000); // Adjust time as needed
            },
        
            });
        }
        function getVehicleData(id) {
            document.getElementById('addSupplierModalLabel').textContent = 'Update Vehicle';
            const csrf_token = '{{ csrf_token() }}';
                $.ajax({
                type: "POST",
                url: '{{ route("get-vehicle-data") }}',
                data: {
                    _token: csrf_token, 
                    id: id,
                },
                success: function(res) {
                    console.log(res)
                    $('#name').val(res.name);
                    $('#reg_no').val(res.registration_no);
                    $('#modal').val(res.modal);
                    $('#driver_id option').each(function() {
                        if ($(this).val() == res.driver_id) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#update_vehicle_id').val(id);

                },
            
                });
        }
    </script>

    
@endsection