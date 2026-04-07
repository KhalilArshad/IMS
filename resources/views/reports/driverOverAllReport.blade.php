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
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            All Driver Report
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="row">
            <div class="col-12">
                @if (Session::has('status'))
                <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show" id="dismiss">
                    <div class="text-white">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <div id="alertPlaceholder" class="mb-4"></div>
                           <?php
                             $system_date = App\Models\Setting::select('id','system_date')->first();
                             $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
                            ?>
                        <!-- Search Form -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <form method="GET" action="{{ url('getdriverOverAllReport') }}" class="row">
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
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="date_from">Date From</label>
                                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', $oldDateFrom ?? $system_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="date_to">Date To</label>
                                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to', $oldDateTo ?? $system_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive border rounded">
                             @php
                                use Carbon\Carbon;

                                /**
                                 * Convert "YYYY-WW" to week start & week end (Mon–Sun)
                                 */
                                function weekRange($weekKey) {
                                    [$year, $week] = explode('-', $weekKey);

                                    // Carbon week start + end
                                    $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
                                    $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();

                                    return [
                                        'start' => $start->format('D d M Y'),
                                        'end'   => $end->format('D d M Y'),
                                        'display' => $start->format('D d M') . ' - ' . $end->format('D d M'),
                                    ];
                                }
                                @endphp

                                @foreach($invoices as $customerId => $weeks)
                                    @php $customerName = $weeks->first()->customer->name; @endphp

                                    <h4 class="mt-4">{{ $customerName }}</h4>

                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 25%">Week</th>
                                                <th style="width: 15%">Last Entry Date</th>
                                                <th style="width: 20%">Net Remaining</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($weeks as $weekKey => $entry)
                                                @php $range = weekRange($weekKey); @endphp

                                                <tr>
                                                    <td>{{ $range['display'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                                                    <td>{{ number_format($entry->net_remaining, 2) }}</td>
                                                    <td>{{ $entry->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Print Button -->
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                 <button id="printReportBtn" class="btn btn-primary">Print</button>
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
            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();
            var baseUrl = "{{ url('/') }}";

            // Construct the URL for the print route
            var printUrl = `${baseUrl}/getdriverOverAllReportPrint?driver_id=${driverId}&date_from=${dateFrom}&date_to=${dateTo}`;

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

 

    </script>

    
@endsection