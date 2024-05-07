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
                        <li class="breadcrumb-item active" aria-current="page">Vehicle Expense List</li>
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
                                     <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal"><i class="bx bxs-plus-square"></i> Add Vehicle Expense</a>

                                    </div>
                            </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Vehicle</th>
                                                <th>Date</th>
                                                <th>Expense Type</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($vehicleExpenses  as $expense)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $expense->vehicle->name }}-{{ $expense->vehicle->registration_no }}</td>
                                                <td>
                                              
                                                   {{ $expense->date}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $expense->expense_type}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $expense->amount}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $expense->description}}
                                                  
                                                  </td>
                                            
                                                <td>
                                                
                                                 <div class="d-flex order-actions">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="getVehicleData('{{$expense->id}}')" class="ms-3"><i class='bx bxs-edit text-info'></i></a>
                                                    <a href="vehicle-expense-delete/{{ $expense->id }}" class="ms-3"><i class='bx bxs-trash text-danger'></i></a>

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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSupplierModalLabel">Add Vehicle Expense</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                                 <div class="row">
                                 <div id="alertPlaceholderReq"></div>
                                 <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Vehicle<span
                                                    class="text-danger"> *</span></label>
                                      <select class="form-control selectric lang" name="vehicle_id" id="vehicle_id" required>
                                        <option value="">{{ __('Select Vehicle') }}</option>
                                                        @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"> {{ $vehicle->name }} - {{    $vehicle->registration_no }}</option>
                                                        @endforeach
                                      </select>
                                    </div>
                                    
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Expense Type<span
                                                    class="text-danger"> *</span></label>
                                        <select class="form-control selectric lang" name="expense_type" id="expense_type" required>
                                        <option value="">{{ __('Select Expense Type') }}</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Fuel">Fuel</option>
                                        <option value="Repairing">Repairing</option>
                                        <option value="Other Expense">Other Expense</option>
                                                  
                                      </select>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Amount<span
                                                    class="text-danger"> *</span></label>
                                    <input type="text" name="amount"  class="form-control" id="amount" placeholder="Enter amount" required>
                                    <input type="hidden" name="update_vehicleExpense_id"  class="form-control" id="update_vehicleExpense_id" placeholder="Enter amount" required>
                                    </div>
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Date<span
                                                    class="text-danger"> *</span></label>
                                    <input type="date" name="date"  class="form-control" id="date" placeholder="Enter description">
                                    </div>

                                  
                                 </div>
                                 <div class="row">
                                 <div class="mb-3 col-12">
                                    <label for="inputProductTitle" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="description" placeholder="Enter description"></textarea>
                                </div>

                                  
                                 </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveVehicleExpense();">Save changes</button>
                          
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
    function saveVehicleExpense() {
        const vehicle_id = $('#vehicle_id').val();
        const expense_type = $('#expense_type').val();
        const amount = $('#amount').val();
        const date = $('#date').val();
        const description = $('#description').val();
        const update_vehicleExpense_id = $('#update_vehicleExpense_id').val();
        if (!vehicle_id || !vehicle_id || !expense_type || !description) {
        // Display error message for required fields
        $('#alertPlaceholderReq').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
        return; // Stop further execution
        }
        const csrf_token = '{{ csrf_token() }}';
            $.ajax({
            type: "POST",
            url: '{{ route("save-vehicle-expense") }}',
            data: {
                _token: csrf_token, 
                vehicle_id: vehicle_id,
                expense_type: expense_type,
                amount: amount,
                date: date,
                description: description,
                update_vehicleExpense_id: update_vehicleExpense_id,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Vehicle Expense Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

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

        var today = new Date();
        var formattedDate = today.toISOString().substr(0, 10);
        document.getElementById('date').value = formattedDate;

        function getVehicleData(id) {
            document.getElementById('addSupplierModalLabel').textContent = 'Update Vehicle Expense';
            const csrf_token = '{{ csrf_token() }}';
                $.ajax({
                type: "POST",
                url: '{{ route("get-vehicleExpense-data") }}',
                data: {
                    _token: csrf_token, 
                    id: id,
                },
                success: function(res) {
                    console.log(res)
                    $('#amount').val(res.amount);
                    $('#date').val(res.date);
                    $('#description').val(res.description);
                    $('#vehicle_id option').each(function() {
                        if ($(this).val() == res.vehicle_id) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#expense_type option').each(function() {
                        if ($(this).val() == res.expense_type) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#update_vehicleExpense_id').val(id);

                },
            
                });
        }
    </script>

    
@endsection