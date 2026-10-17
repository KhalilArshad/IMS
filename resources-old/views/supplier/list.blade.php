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
                        <li class="breadcrumb-item active" aria-current="page">Suppliers List</li>
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
                                     <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="prepareAddNewSupplier();"><i class="bx bxs-plus-square"></i> Add New Supplier</a>

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
                                                <th>Phone No</th>
                                                <th>Email</th>
                                                <th>Previous Balance</th>
                                                <th>Action</th>
                                                <th>Pay</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($suppliers  as $supplier)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $supplier->name }}</td>
                                                <td>
                                              
                                                   {{ $supplier->phone_no}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $supplier->email}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $supplier->previous_balance}}
                                                  
                                                  </td>
                                                <td>
                                                
                                                 <div class="d-flex order-actions">
                                                 <a href="#" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="getCustomerData('{{$supplier->id}}')" class="ms-3"><i class='bx bxs-edit text-info'></i></a>
                                                    <a href="supplier-delete/{{ $supplier->id }}" class="ms-3" onclick="return confirm('Are you sure you want to delete this record?');"><i class='bx bxs-trash text-danger'></i></a>

                                                    </div>
                                                </td>
                                                  <td>
                                                  <a href="supplier-payable?id={{ $supplier->id }}" class="ms-3" style="font-size: 20px;"><i class='lni lni-customer'></i></a>
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
                            <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Name</label>
                                <input type="text" name="name" required class="form-control" id="name" placeholder="Enter Name">
                                <input type="hidden" name="update_supplier_id" required class="form-control" id="update_supplier_id" placeholder="Enter Name">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Phone Number</label>
                                <input type="text" name="phone_no" class="form-control" id="phone_no" placeholder="Enter Phone No">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email">
                                </div>
                                <div class="mb-3" id="hide_field">
                                <label for="inputProductTitle" class="form-label">Opening Balance</label>
                                <input type="number" name="opening_balance" class="form-control" id="opening_balance" placeholder="Enter Opening Balance">
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
    function saveSupplier() {
        const name = $('#name').val();
        const phone_no = $('#phone_no').val();
        const email = $('#email').val();
        const opening_balance = $('#opening_balance').val();
        const update_supplier_id = $('#update_supplier_id').val();
        const csrf_token = '{{ csrf_token() }}';
            $.ajax({
            type: "POST",
            url: '{{ route("supplier-save") }}',
            data: {
                _token: csrf_token, 
                name: name,
                phone_no: phone_no,
                email: email,
                opening_balance: opening_balance,
                update_supplier_id: update_supplier_id,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Supplier Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

            // Clear form fields
            $('#name').val('');
            $('#phone_no').val('');
            $('#email').val('');
            $('#opening_balance').val('');
            // Optionally, hide the message after a delay
            setTimeout(function() {
                window.location.reload();
            }, 1000); // Adjust time as needed
            },
        
            });
        }

        function getCustomerData(id) {
            console.log(id)
            document.getElementById("hide_field").style.display = "none";
            document.getElementById('addSupplierModalLabel').textContent = 'Update Supplier';
            const csrf_token = '{{ csrf_token() }}';
                $.ajax({
                type: "POST",
                url: '{{ route("get-supplier-data") }}',
                data: {
                    _token: csrf_token, 
                    id: id,
                },
                success: function(res) {
                    console.log(res)
                    $('#name').val(res.name);
                    $('#phone_no').val(res.phone_no);
                    $('#email').val(res.email);
                    $('#update_supplier_id').val(id);

                },
            
                });
        }

        function prepareAddNewSupplier() {
            document.getElementById('addSupplierModalLabel').textContent = 'Add New Supplier'; // Set the modal title for adding
            document.getElementById('hide_field').style.display = "block"; // Ensure all fields are visible

            // Clear all input fields
            $('#name').val('');
            $('#phone_no').val('');
            $('#email').val('');
            $('#opening_balance').val('');
            $('#update_supplier_id').val(''); // Clear any stored ID because it's an add operation
        }
    </script>

    
@endsection