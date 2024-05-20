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
            <div class="breadcrumb-title pe-3">Employee</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Payroll List</li>
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
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                            <th>Sr #</th>
                                                                <th>Employee Name</th>
                                                                 <th>Salary</th>
                                                                <th>Advance</th>
                                                                <th>Paid in Advance</th>
                                                                <th>Over time</th>
                                                                <th>Current Month Salary</th>
                                                                <th>Date</th>
                                                                <th>Description</th>
                                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($payrolls  as $payroll)
                                            <tr>
                                            <td>{{ $i }}</td>
                                                                <td>{{ $payroll->employee->name }}</td>
                                                                <td>{{ $payroll->salary }}</td>
                                                                <td>{{ $payroll->advance }}</td>
                                                                <td>{{ $payroll->paid_in_advance }}</td>
                                                                <td>{{ $payroll->overtime }}</td>
                                                                <td>{{ $payroll->total_salary_to_be_paid }}</td>
                                                                <td>{{ $payroll->date }}</td>
                                                                <td>{{ $payroll->description}}</td>
                                                                <td>
                                                                <div class="d-flex order-actions">
                                                                <a href="view-payroll/{{ $payroll->id }}" class="ms-3"  target="_blank"><i class='bx bxs-show  text-info'></i></a>
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
    
    </script>

    
@endsection