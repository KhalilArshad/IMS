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
            <div class="breadcrumb-title pe-3">Trilla</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Trilla Record</li>
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

                        <form action="{{ route('trilla.records.update',$record->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label class="form-label">Trilla <span class="text-danger">*</span></label>
                                    <select class="form-control selectric lang" name="trilla_id" required>
                                        <option value="">Select Trilla</option>
                                       @foreach ($trillas as $trilla)

                                        <option value="{{ $trilla->id }}"
                                        {{ $record->trilla_id == $trilla->id ? 'selected':'' }}>

                                        {{ $trilla->name }} ({{ $trilla->driver->name }})

                                        </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 col-6">
                                    <label class="form-label">Month <span class="text-danger">*</span></label>
                                    <input type="month" name="month" value="{{ $record->month }}" required class="form-control">
                                </div>
                            </div>

                            <hr>

                            <h5>Trips</h5>
                            <!-- Header Row -->
                            <div class="row fw-bold mb-2">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-md-2">Date</div>
                                        <div class="col-md-2">From Location</div>
                                        <div class="col-md-2">To Location</div>
                                        <div class="col-md-1">Trip Amount</div>
                                        <div class="col-md-1">Diesel</div>
                                        <div class="col-md-1">D Trip</div>
                                        <div class="col-md-1">Maintenance</div>
                                        <div class="col-md-1">Cash Received</div>
                                        <div class="col-md-1">Bill No</div>
                                    </div>
                                </div>
                                <div class="col-1 text-center">Action</div>
                            </div>
                            <div id="trip-container">
                              @foreach($record->trillaRecordChild as $trip)
                                <!-- First Row -->
                                <div class="row trip-row mb-2">
                                    <div class="col-11">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input type="date" name="date[]" value="{{ $trip->date }}" class="form-control" required>
                                            </div>

                                            <div class="col-md-2">
                                                <input type="text" name="from_location[]" value="{{ $trip->from_location }}" class="form-control" placeholder="From">
                                            </div>

                                            <div class="col-md-2">
                                                <input type="text" name="to_location[]" value="{{ $trip->to_location }}" class="form-control" placeholder="To">
                                            </div>

                                            <div class="col-md-1">
                                                <input type="number" name="trip_amount[]" value="{{ $trip->trip_amount }}" class="form-control" placeholder="Trip">
                                            </div>

                                            <div class="col-md-1">
                                                <input type="number" name="diesel[]" value="{{ $trip->diesel }}" class="form-control" placeholder="Diesel">
                                            </div>

                                            <div class="col-md-1">
                                                <input type="number" name="d_trip[]" value="{{ $trip->d_trip }}" class="form-control" placeholder="D Trip">
                                            </div>

                                            <div class="col-md-1">
                                                <input type="number" name="maintenance[]" value="{{ $trip->maintenance }}" class="form-control" placeholder="Maint">
                                            </div>

                                            <div class="col-md-1">
                                                <input type="number" name="cash_received[]" value="{{ $trip->cash_received }}" class="form-control" placeholder="Cash">
                                            </div>

                                            <div class="col-md-1">
                                                <input type="text" name="bill_no[]" value="{{ $trip->bill_no }}" class="form-control" placeholder="Bill">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-success addRow">+</button>
                                        <button type="button" class="btn btn-danger removeRow ms-1">-</button>
                                    </div>
                                </div>

                            @endforeach
                            </div>

                            <!-- Total Row -->
                            <div class="row mt-3">
                                <div class="col-11">
                                    <div class="row">

                                        <div class="col-md-2">
                                            <input type="text" class="form-control fw-bold" value="Total" readonly>
                                        </div>

                                        <div class="col-md-2"></div>
                                        <div class="col-md-2"></div>

                                        <div class="col-md-1">
                                            <input type="number" id="total_trip_amount" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-1">
                                            <input type="number" id="total_diesel" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-1">
                                            <input type="number" id="total_d_trip" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-1">
                                            <input type="number" id="total_maintenance" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-1">
                                            <input type="number" id="total_cash_received" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-1"></div>

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="driver_salary" class="form-label">Driver Salary <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" value="{{ $record->driver_salary }}" name="driver_salary" id="driver_salary" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="total_expenses" class="form-label">Total Expenses <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" value="" name="total_expenses" id="total_expenses" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="profit" class="form-label">Profit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" value="" name="profit" id="profit" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="other_expense" class="form-label">Other Expense <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" value="{{ $record->other_expense }}" min="0" name="other_expense" id="other_expense">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="net_profit" class="form-label">Net Profit</label>
                                    <input type="number" class="form-control" name="net_profit" id="net_profit" readonly>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary">Save</button>
                            </div>

                        </form>

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
        calculateTotals();
    });
    </script>
    <script>

    document.addEventListener("click", function(e) {

        // Add Row
        if (e.target.classList.contains("addRow")) {

            let row = document.querySelector(".trip-row").cloneNode(true);

            row.querySelectorAll("input").forEach(input => {
                input.value = "";
            });

            document.getElementById("trip-container").appendChild(row);
        }

        // Remove Row
        if (e.target.classList.contains("removeRow")) {

            let rows = document.querySelectorAll(".trip-row");

            if(rows.length > 1){
                e.target.closest(".trip-row").remove();
                 calculateTotals();
            }

        }

    });

    </script>

    <script>

        function calculateTotals()
        {
            let trip = 0;
            let diesel = 0;
            let dtrip = 0;
            let maintenance = 0;
            let cash = 0;

            document.querySelectorAll('input[name="trip_amount[]"]').forEach(el=>{
                trip += parseFloat(el.value) || 0;
            });

            document.querySelectorAll('input[name="diesel[]"]').forEach(el=>{
                diesel += parseFloat(el.value) || 0;
            });

            document.querySelectorAll('input[name="d_trip[]"]').forEach(el=>{
                dtrip += parseFloat(el.value) || 0;
            });

            document.querySelectorAll('input[name="maintenance[]"]').forEach(el=>{
                maintenance += parseFloat(el.value) || 0;
            });

            document.querySelectorAll('input[name="cash_received[]"]').forEach(el=>{
                cash += parseFloat(el.value) || 0;
            });

            document.getElementById('total_trip_amount').value = trip;
            document.getElementById('total_diesel').value = diesel;
            document.getElementById('total_d_trip').value = dtrip;
            document.getElementById('total_maintenance').value = maintenance;
            document.getElementById('total_cash_received').value = cash;
            document.getElementById('driver_salary').addEventListener('input', calculateTotals);
            document.getElementById('other_expense').addEventListener('input', calculateTotals);
             // Driver Salary
            let driverSalary = parseFloat(document.getElementById('driver_salary').value) || 0;

             // Total Expenses
            let totalExpenses = diesel + dtrip + maintenance + driverSalary;
            document.getElementById('total_expenses').value = totalExpenses;

            // Profit
            let profit = trip - totalExpenses;
            document.getElementById('profit').value = profit;

            // Other Expense
            let otherExpense = parseFloat(document.getElementById('other_expense').value) || 0;

            // Net Profit
            let netProfit = profit - otherExpense;
            document.getElementById('net_profit').value = netProfit;
        }

        document.addEventListener('input', function(e) {
            if (
                e.target.name == "trip_amount[]" ||
                e.target.name == "diesel[]" ||
                e.target.name == "d_trip[]" ||
                e.target.name == "maintenance[]" ||
                e.target.name == "cash_received[]"
            ) {
                calculateTotals();
            }
        });

        </script>
@endsection