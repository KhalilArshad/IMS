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
                        <li class="breadcrumb-item active" aria-current="page">Vehicle Installment List</li>
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
                                     <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal"><i class="bx bxs-plus-square"></i> Add Vehicle Installment</a>

                                    </div>
                            </div>

                            <div class="row  mb-4">
                          <?php
                            $currentDate = date('Y-m-d');
                            ?>
                            <form method="GET"  action="{{ url('add-vehicle-installment') }}" class="d-flex flex-wrap gap-2">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="vehicle">Vehicle</label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-control">
                                        <option value="">Select vehicle</option>
                                         @foreach($vehicles as $vehicle)
                                         <option value="{{ $vehicle->id }}" {{ old('vehicle', $oldVehicleId) == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_to"></label>
                                <div class="form-group align-self-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                                </div>
                            </form>
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
                                                <th>Installment Amount</th>
                                                <th>Remaining</th>
                                                <th>Description</th>
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($vehicleInstallments  as $vehicleInstallment)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $vehicleInstallment->vehicle->name }}</td>
                                                <td>
                                              
                                                    {{ $vehicleInstallment->date}}
                                                    
                                                </td>
                                                <td>
                                                    
                                                    {{ $vehicleInstallment->amount}}
                                                    
                                                </td>
                                                <td>
                                                    {{ $vehicleInstallment->remaining}}
                                              
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $vehicleInstallment->description}}
                                                  
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
                            <h5 class="modal-title" id="addSupplierModalLabel">Add Vehicle Installment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                                 <div class="row">
                                 <div id="alertPlaceholderReq"></div>
                                 <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Vehicle<span
                                                    class="text-danger"> *</span></label>
                                      <select class="form-control selectric lang" name="vehicle_id_save" id="vehicle_id_save" required>
                                        <option value="">{{ __('Select Vehicle') }}</option>
                                                        @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"> {{ $vehicle->name }} - {{    $vehicle->registration_no }}</option>
                                                        @endforeach
                                      </select>
                                    </div>
                                    
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Total Remaining<span
                                                    class="text-danger"> *</span></label>
                                    <input type="number" name="remaining"  class="form-control" id="remaining" placeholder="Total Remaining" readonly>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Installment Amount<span
                                                    class="text-danger"> *</span></label>
                                    <input type="text" name="amount"  class="form-control" id="amount" placeholder="Enter amount" required oninput="validateInstallmentAmount()">
                                    <!-- <input type="hidden" name="update_vehicleExpense_id"  class="form-control" id="update_vehicleExpense_id" placeholder="Enter amount" required > -->
                                    </div>
                                    <?php
                                            $currentDate = date('Y-m-d');
                                            ?>
                                    <div class="mb-3 col-6">
                                    <label for="inputProductTitle" class="form-label">Date<span
                                                    class="text-danger"> *</span></label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{$currentDate}}"placeholder="Enter description">
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
                            <button type="button" class="btn btn-info"  id="saveButton"  onclick="saveVehicleInstallment();">Save changes</button>
                          
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
            // $('#example').DataTable();
            var table = $('#example').DataTable({
            lengthChange: true,
            'searching': true,
            'paging': true,
            'sorting': false,
            'info': false,
            buttons: ['pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
    });
    function saveVehicleInstallment() {
        const vehicle_id = $('#vehicle_id_save').val();
        const remaining = $('#remaining').val();
        const amount = $('#amount').val();
        const date = $('#date').val();
        const description = $('#description').val();
        // const update_vehicleExpense_id = $('#update_vehicleExpense_id').val();
        if (!vehicle_id || !vehicle_id || !amount) {
        // Display error message for required fields
        $('#alertPlaceholderReq').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
        return; // Stop further execution
        }
        const csrf_token = '{{ csrf_token() }}';
            $.ajax({
            type: "POST",
            url: '{{ route("save-vehicle-installment") }}',
            data: {
                _token: csrf_token, 
                vehicle_id: vehicle_id,
                remaining: remaining,
                amount: amount,
                date: date,
                description: description,
                // update_vehicleExpense_id: update_vehicleExpense_id,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Vehicle Installment Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

            // Clear form fields
            $('#vehicle_id_save').val('');
            $('#remaining').val('');
            $('#amount').val('');
            $('#description').val('');
            // Optionally, hide the message after a delay
            setTimeout(function() {
                window.location.reload();
            }, 1000); // Adjust time as needed
            },
        
            });
        }


        $(document).ready(function() {
            $('#vehicle_id_save').change(function() {
                var vehicleId = $(this).val();
                console.log(vehicleId)
                if (vehicleId) {
                    const csrf_token = '{{ csrf_token() }}';
                    $.ajax({
                    type: "POST",
                    url: '{{ route("get-vehicle-remaining") }}',
                    data: {
                        _token: csrf_token, 
                        id: vehicleId,
                    },
                    success: function(res) {
                        console.log(res)
                        $('#remaining').val(res.remaining);

                    },
                
                    });
                } else {
                    $('#remaining').val('');
                }
            });
        });

        function validateInstallmentAmount() {
        var remaining = parseFloat($('#remaining').val());
        var amount = parseFloat($('#amount').val());

        if (isNaN(amount) || amount <= remaining) {
            $('#alertPlaceholderReq').html('');
            $('#amount').removeClass('is-invalid');
            $('#saveButton').prop('disabled', false);
        } else {
            $('#alertPlaceholderReq').html('<div class="alert alert-danger" role="alert">Installment amount cannot be greater than the remaining amount.</div>');
            $('#amount').addClass('is-invalid');
            $('#saveButton').prop('disabled', true);
        }
    }
    </script>

    
@endsection