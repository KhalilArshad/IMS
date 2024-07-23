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
                        <li class="breadcrumb-item active" aria-current="page">Employee List</li>
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
                                <a href="{{ url('addEmployee') }}" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add New Employee</a>
                          
                               <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal" ><i class="bx bxs-plus-square"></i> Add Employee Advance</a>

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
                                                                 <th>Email</th>
                                                                <th>Phone No</th>
                                                                <th>Cnic No</th>
                                                                <th>Designation</th>
                                                                <th>Date Of Joining</th>
                                                                <th>Salary</th>
                                                                <th>Advance</th>
                                                                <th>Remaining</th>
                                                                <th>Generate Payroll</th>
                                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($getEmployees  as $getEmployee)
                                            <tr>
                                            <td>{{ $i }}</td>
                                                                <td>{{ $getEmployee->name }}</td>
                                                                <td>{{ $getEmployee->email }}</td>
                                                                <td>{{ $getEmployee->phone_no }}</td>
                                                                <td>{{ $getEmployee->cnic_no }}</td>
                                                                <td>{{ $getEmployee->designation }}</td>
                                                                <td>{{ $getEmployee->date_of_joining }}</td>
                                                                <td>{{ $getEmployee->salary }}</td>
                                                                <td>{{ $getEmployee->advance??0 }}</td>
                                                                <td>{{ $getEmployee->remaining??0 }}</td>
                                                                <td>
                                                                    <button onclick="window.location.href='{{ route('createPayroll', ['id' => $getEmployee->id]) }}'" class="btn btn-sm btn-primary">
                                                                    Generate Payroll
                                                                    </button>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex order-actions">
                                                                   
                                                                        <a href="viewEmployees?id={{ $getEmployee->id }}" class="text-warning">
                                                                            <i class="fadeIn animated bx bx-show-alt"></i>
                                                                        </a>
                                                                        &nbsp;&nbsp;
                                                                        <a href="deleteEmployee?id={{ $getEmployee->id }}" class="text-danger" onclick="return confirm('Are you sure you want to delete this Employee?')">
                                                                            <i class="fadeIn animated bx bx-trash-alt"></i>
                                                                        </a>
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

                                      <!-- Modal for adding a new supplier -->
            <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSupplierModalLabel">Add Employee Advance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                            <div id="alertPlaceholderReq"></div>
                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Select Employee<span
                                                    class="text-danger"> *</span></label>
                               <select name="employee_id" id="employee_id" required class="form-control">
                                   <option value="">Select employee</option>
                                @foreach($getEmployees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                               </select>
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Advance Amount<span
                                                    class="text-danger"> *</span></label>
                                <input type="number" name="advance_amount" required class="form-control" id="advance_amount" placeholder="Enter Advance Amount">
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date<span
                                                    class="text-danger"> *</span></label>
                                    <input type="date"  name="advance_date" id="advance_date" required class="form-control" value="{{$system_date}}" placeholder="Enter date">
                                </div>
                                <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                               
                                <textarea name="description" id="description" required class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveEmployeeAdvance();">Save changes</button>
                          
                        </div>
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
    function saveEmployeeAdvance() {
        const employee_id = $('#employee_id').val();
        const advance_amount = $('#advance_amount').val();
        const description = $('#description').val();
        const date = $('#date').val();
        const csrf_token = '{{ csrf_token() }}';
        if (!employee_id || !advance_amount || !description) {
        // Display error message for required fields
        $('#alertPlaceholderReq').html('<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" role="alert">All required fields must be filled.</div>');
        return; // Stop further execution
        }
            $.ajax({
            type: "POST",
            url: '{{ route("save-employee-advance") }}',
            data: {
                _token: csrf_token, 
                employee_id: employee_id,
                advance_amount: advance_amount,
                description: description,
                date: date,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Employee Advance Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

            // Clear form fields
            $('#employee_id').val('');
            $('#advance_amount').val('');
            $('#description').val('');
            // Optionally, hide the message after a delay
            setTimeout(function() {
                window.location.reload();
            }, 1000); // Adjust time as needed
            },
        
            });
        }
    </script>

    
@endsection