@extends("layouts.app")

@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">Employee</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Year Payroll List</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (Session::has('status'))
            <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show">
                <div class="text-white">{{ Session::get('message') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ Session::forget('status') }}
                {{ Session::forget('message') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">Employee Payroll Records</h4>
                        <p class="text-muted mb-0">Each row is one employee's full year record. Edit reopens the whole year so you can add the next month row.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Year</th>
                                <th>Months</th>
                                <th>Total Salary</th>
                                <th>Total Advance</th>
                                <th>Paid In Old</th>
                                <th>Total Paid</th>
                                <th>Latest Remaining</th>
                                <th>Last Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payrolls as $index => $payroll)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payroll->employee_name }}</td>
                                    <td>{{ $payroll->designation }}</td>
                                    <td>{{ $payroll->payroll_year }}</td>
                                    <td>{{ $payroll->total_months }}</td>
                                    <td>{{ number_format($payroll->total_salary ?? 0, 2) }}</td>
                                    <td>{{ number_format($payroll->total_advance ?? 0, 2) }}</td>
                                    <td>{{ number_format($payroll->total_paid_old ?? 0, 2) }}</td>
                                    <td>{{ number_format($payroll->total_paid_salary ?? 0, 2) }}</td>
                                    <td>{{ number_format($payroll->latest_remaining ?? 0, 2) }}</td>
                                    <td>{{ $payroll->latest_date }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('createPayroll', ['id' => $payroll->employee_id, 'year' => $payroll->payroll_year]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class='bx bxs-edit'></i>
                                            </a>
                                            <a href="{{ route('view-payroll', ['employee' => $payroll->employee_id, 'year' => $payroll->payroll_year]) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                <i class='bx bxs-show'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
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
