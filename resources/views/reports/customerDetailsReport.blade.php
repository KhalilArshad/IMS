@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<style>
/* Custom CSS for Select2 to match the form input styles */
.select2-container .select2-selection--single {
    height: 38px; 
}
.select2-container--default .select2-selection--single {
    border: 1px solid #ccc; 
    border-radius: 4px; 
    background-color: #fff;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px; 
    padding-left: 8px; 
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px; 
    position: absolute;
    top: 1px; 
    right: 1px; 
    width: 20px; 
}

</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
       

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Details Report</li>
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
                    <div class="row  mb-4">
                          <?php
                            $currentDate = date('Y-m-d');
                            ?>
                            <form method="GET"  action="{{ url('getCustomerDetailsReport') }}" class="d-flex flex-wrap gap-2">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="driver">Driver</label>
                                    <select name="driver_id" id="driver_id" class="form-control" onchange="getDriverCustomer(this.value)">
                                        <option value="">Select driver</option>
                                         @foreach($drivers as $driver)
                                         <option value="{{ $driver->id }}" {{ old('driver', $oldDriverId) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            <div class="col-sm-2">
                                   <div class="form-group">
                                        <label for="name">Customer Name</label>
                                             <select class="form-control selectric lang" name="customer_id" id="customer_id">
                                            <option value="">{{ __('Select customer') }}</option>
                                                        
                                              </select>
                                                   
                                    </div>
                                </div>
                              
                                <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', $oldDateFrom ?? $currentDate) }}">
                                </div>
                                </div>
                                <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to', $oldDateTo ?? $currentDate) }}">
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
                              
                                    <div class="container border rounded">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                <th>Sr #</th>
                                                <th>Customer</th>
                                                <th>Today Bill</th>
                                                <th>Today Remaining</th>
                                                <th>Old Remaining</th>
                                                <th>Old Received</th>
                                                <th>Net Remaining</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php($i = 1)
                            
                                            @foreach ($invoices  as $child)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $child->customer->name }}</td>
                                                <td>{{ $child->today_bill}}</td>
                                                <td>{{ $child->today_remaining}}</td>
                                                <td>{{ $child->old_remaining}}</td>
                                                <td>{{ $child->old_received}}</td>
                                                <td>{{ $child->net_remaining}}</td>
                                                <td>{{ $child->date}}</td>
                                                <td>{{ $child->description}}</td>
                                            </tr>
                                            @php($i++)
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                            </div>
                         </div>
                         
                        <button id="printReportBtn" class="btn btn-primary mt-2" style="float: right;">Print</button>

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
  $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: true,
            'searching': true,
            'paging': true,
            'sorting': false,
            'info': false,
            buttons: ['pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
   

        $('#printReportBtn').click(function() {
            var driverId = $('#driver_id').val();
            var customerId = $('#customer_id').val();
            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();
            var baseUrl = "{{ url('/') }}";

            // Construct the URL for the print route
            var printUrl = `${baseUrl}/customerDetailsReportPrint?driver_id=${driverId}&customer_id=${customerId}&date_from=${dateFrom}&date_to=${dateTo}`;

            // Open the print route URL in a new tab
            window.open(printUrl, '_blank');
        });

        if ($('#driver_id').val()) {
        getDriverCustomer($('#driver_id').val());
        }
        $('#customer_id').select2({
            placeholder: "{{ __('Select customer') }}",
            allowClear: true
        });
  });
  var oldCustomerId = '{{ old('customer_id', $oldCustomerId) }}';
 
  function getDriverCustomer(driver_id) {
            if (driver_id) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('getDriverCustomer') }}",
                    method: 'POST',
                    data: {
                        _token: _token,
                        driver_id: driver_id
                    },
                    success: function(result) {
              
                        var existingOptions = $('#customer_id').children().clone();
                        // Clear current options and add the default
                        $('#customer_id').empty().append('<option value="">{{ __("Select customer") }}</option>');
                        // Append new options from the AJAX response at the top (after the default option)
                        result.customers.forEach(function(customer) {
                            var selected = (customer.customer_id.toString() === oldCustomerId) ? 'selected' : '';
                            $('#customer_id').append(`<option value="${customer.customer_id}" ${selected}>${customer.customer_name}</option>`);
                        });
                        
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.log("Error fetching customer data: " + error);
                        alert('Failed to retrieve customer data. Please try again.');
                        $('#customer_id').empty().append('<option value="">{{ __("Failed to load customers") }}</option>');
                    }

                });
            } else {
                // alert('Select Category');
                $('#customer_id').html('');
            }

        }
    </script>

    
@endsection