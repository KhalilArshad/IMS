@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<!-- Select2 CSS -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.1.0/select2-bootstrap-5-theme.min.css" rel="stylesheet">

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
                        <li class="breadcrumb-item active" aria-current="page">Driver List</li>
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

                                    <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal"><i class="bx bxs-plus-square"></i> Add New Customer</a>
                                     <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#assignCustomerToModal"><i class="bx bxs-plus-square"></i>Assign Customer To Driver</a>
                                     <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="prepareAddNewSupplier();"><i class="bx bxs-plus-square"></i> Add New Driver</a>

                                    </div>
                            </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Driver Name</th>
                                                <th>Phone No</th>
                                                <th>Email</th>
                                                <th>Customer List</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($drivers  as $driver)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $driver->name }}</td>
                                                <td>
                                              
                                                   {{ $driver->phone_no}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $driver->email}}
                                                  
                                                  </td>
                                                <td>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#getDriverCustomers" onclick="getDriverCustomers('{{$driver->id}}')" class="ms-3"><i class='bx bxs-show  text-info'></i></a>
                                                  
                                                  </td>
                                            
                                                <td>
                                                
                                                 <div class="d-flex order-actions">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="getDriverData('{{$driver->id}}')" class="ms-3"><i class='bx bxs-edit text-info'></i></a>
                                                    <a href="driver-delete/{{ $driver->id }}" class="ms-3" onclick="return confirm('Are you sure you want to delete this record?');"><i class='bx bxs-trash text-danger'></i></a>

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
                            <h5 class="modal-title" id="addSupplierModalLabel">Add New Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                            <div id="alertPlaceholderReq"></div>
                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Name<span
                                                    class="text-danger"> *</span></label>
                                <input type="text" name="name" required class="form-control" id="name" placeholder="Enter Name">
                                <input type="hidden" name="update_driver_id" required class="form-control" id="update_driver_id" placeholder="Enter Name">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Phone Number</label>
                                <input type="text" name="phone_no" class="form-control" id="phone_no" placeholder="Enter Phone No">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email">
                                </div>
                              

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveSupplier();">Save changes</button>
                          
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="getDriverCustomers" tabindex="-1" aria-labelledby="getDriverCustomersLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="getDriverCustomersLabel">Driver Customer List</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="row p-2">
                            <h5 class="text-center" id="driverName"></h5>
                            <div class="col-12">
                                <div class="container border rounded">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th>Customer Name</th>
                                            <th>Customer Email</th>
                                        </tr>
                                    </thead>
                                    <tbody id="customerTableBody">
                                    
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="assignCustomerToModal" tabindex="-1" aria-labelledby="assignCustomerToModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignCustomerToModalLabel">Assign Customer To Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                            <div id="alertPlaceholderReqForAssignCustomer"></div>
                                 <div class="mb-3">
                                    <label for="name" class="form-label">Driver<span
                                         class="text-danger"> *</span></label>
                                          <select class="form-control selectric lang" name="driver_id" id="driver_id" required>
                                             <option value="">{{ __('Select Driver') }}</option>
                                                 @foreach ($drivers as $driver)
                                             <option value="{{ $driver->id }}"> {{ $driver->name }}</option>
                                                 @endforeach
                                          </select>
                                 </div>
                                 <div class="mb-3">
                                 <label for="customer_id" class="form-label">Customer<span class="text-danger"> *</span></label>
                                    <select class="form-control selectric lang multiple-select" name="customer_id[]" id="customer_id" multiple data-placeholder="Select Multiple Customers" required>
                                        <option value="">Select Multiple Customers</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                 </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveAssignCustomerToDriver();">Save changes</button>
                          
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                            <div id="alertPlaceholderReqForCustomer"></div>
                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Name<span
                                                    class="text-danger"> *</span></label>
                                <input type="text" name="name" required class="form-control" id="name_cu" placeholder="Enter Name">
                                <input type="hidden" name="update_customer_id" required class="form-control" id="update_customer_id" placeholder="Enter Name">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Phone Number<span
                                                    class="text-danger"> *</span></label>
                                <input type="text" name="phone_no" required class="form-control" id="phone_no_cu" placeholder="Enter Phone No">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email_cu" placeholder="Enter Email">
                                </div>
                                <div class="mb-3" id="hide_field">
                                <label for="inputProductTitle" class="form-label">Opening Balance</label>
                                <input type="number" name="opening_balance" class="form-control" id="opening_balance" placeholder="Enter Opening Balance">
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveCustomer();">Save changes</button>
                          
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

            $('.multiple-select').select2({
            width: '100%',
            allowClear: true
        });
    });
        function saveSupplier() {
            const name = $('#name').val();
            const phone_no = $('#phone_no').val();
            const email = $('#email').val();
            const update_driver_id = $('#update_driver_id').val();
            const csrf_token = '{{ csrf_token() }}';
            if (!name) {
            // Display error message for required fields
            $('#alertPlaceholderReq').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
            return; // Stop further execution
            }
                $.ajax({
                type: "POST",
                url: '{{ route("save-driver") }}',
                data: {
                    _token: csrf_token, 
                    name: name,
                    phone_no: phone_no,
                    email: email,
                    update_driver_id: update_driver_id,
                },
                success: function(res) {
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                    myModal.hide();
                    $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Driver Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

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

        function getDriverData(id) {
            document.getElementById('addSupplierModalLabel').textContent = 'Update Driver';
            const csrf_token = '{{ csrf_token() }}';
                $.ajax({
                type: "POST",
                url: '{{ route("get-driver-data") }}',
                data: {
                    _token: csrf_token, 
                    id: id,
                },
                success: function(res) {
                    $('#name').val(res.name);
                    $('#phone_no').val(res.phone_no);
                    $('#email').val(res.email);
                    $('#update_driver_id').val(id);

                },
            
                });
        }
        function prepareAddNewSupplier() {
            document.getElementById('addSupplierModalLabel').textContent = 'Add New Driver'; // Set the modal title 
            $('#name').val('');
            $('#phone_no').val('');
            $('#email').val('');
            $('#update_driver_id').val(''); // Clear any stored ID because it's an add operation
        }
        function getDriverCustomers(id) {
            document.getElementById('addSupplierModalLabel').textContent = 'Update Driver';
            const csrf_token = '{{ csrf_token() }}';
                $.ajax({
                type: "POST",
                url: '{{ route("getDriverCustomers") }}',
                data: {
                    _token: csrf_token, 
                    id: id,
                },
                success: function(res) {
                    $('#driverName').text('Driver Name: ' +res.driverName);
                        // Clear any existing rows in the table body
                        $('#customerTableBody').empty();

                        // Populate the table with customer data
                        res.customers.forEach(function(customer,index) {
                            const email = customer.customer.email ? customer.customer.email : ' ';
                            $('#customerTableBody').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${customer.customer.name}</td>
                                    <td>${email}</td>
                                </tr>
                            `);
                        });

                },
            
                });
        }


        function saveAssignCustomerToDriver() {
            const driver_id = $('#driver_id').val();
            const customer_ids = $('#customer_id').val();
            console.log("Selected Customer IDs: ", customer_ids); 
            const csrf_token = '{{ csrf_token() }}';
            if (!driver_id || !customer_ids) {
            // Display error message for required fields
            $('#alertPlaceholderReqForAssignCustomer').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
            return; // Stop further execution
            }
                $.ajax({
                type: "POST",
                url: '{{ route("saveAssignCustomerToDriver") }}',
                data: {
                    _token: csrf_token, 
                    driver_id: driver_id,
                    customer_ids: customer_ids,
                },
                success: function(res) {
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('assignCustomerToModal'));
                    myModal.hide();
                    $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Customers Assign To Driver Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                // Clear form fields
                $('#driver_id').val('');
                $('#customer_id').val('');
                // Optionally, hide the message after a delay
                setTimeout(function() {
                    window.location.reload();
                }, 1000); // Adjust time as needed
                },
            
                });
        }

        function saveCustomer() {
        const name = $('#name_cu').val();
        const phone_no = $('#phone_no_cu').val();
        const email = $('#email_cu').val();
        const opening_balance = $('#opening_balance').val();
        const csrf_token = '{{ csrf_token() }}';
        if (!name || !phone_no) {
        // Display error message for required fields
        $('#alertPlaceholderReqForCustomer').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
        return; // Stop further execution
        }
            $.ajax({
            type: "POST",
            url: '{{ route("customer-save") }}',
            data: {
                _token: csrf_token, 
                name: name,
                phone_no: phone_no,
                email: email,
                opening_balance: opening_balance,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Customer Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

            // Clear form fields
            $('#name_cu').val('');
            $('#phone_no_cu').val('');
            $('#email_cu').val('');
            $('#opening_balance').val('');
            // Optionally, hide the message after a delay
            setTimeout(function() {
                window.location.reload();
            }, 1000); // Adjust time as needed
            },
        
            });
        }
    </script>

    
@endsection