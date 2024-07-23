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
                        <li class="breadcrumb-item active" aria-current="page">Employees Advance List</li>
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
                            <form method="GET"  action="{{ url('getEmployeeAdvanceFilter') }}" class="d-flex flex-wrap gap-2">
                            @csrf
                                <div class="form-group">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">Select employee</option>
                                         @foreach($employees as $employee)
                                         <option value="{{ $employee->id }}" {{ old('employee_id', $oldEmployeeId) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', $oldDateFrom) }}">
                                </div>
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to', $oldDateTo) }}">
                                </div>
                                <div class="form-group align-self-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                            <div class="ms-auto">
                                <a href="#" class="btn btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal"><i class="bx bxs-plus-square"></i> Add Employee Advance</a>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example2"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Employee Name</th>
                                                <th>Advance Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Remaining Amount</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($employeesAdvance  as $advance)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $advance->employee->name }}</td>
                                                <td>
                                              
                                                   {{ $advance->advance_amount}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $advance->paid_amount}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $advance->remaining}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $advance->date}}
                                                  
                                                  </td>
                                                <td>
                                                   {{ $advance->description}}

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
                            <h5 class="modal-title" id="addSupplierModalLabel">Add Employee Advance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">
                            <div id="alertPlaceholderReq"></div>
                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Select Employee<span
                                                    class="text-danger"> *</span></label>
                               <select name="employee_id_save" id="employee_id_save" required class="form-control">
                                   <option value="">Select employee</option>
                                @foreach($employees as $employee)
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
    });
    function saveEmployeeAdvance() {
        const employee_id = $('#employee_id_save').val();
        const advance_amount = $('#advance_amount').val();
        const description = $('#description').val();
        const date = $('#advance_date').val();
        const csrf_token = '{{ csrf_token() }}';
        if (!employee_id || !advance_amount || !description || !date) {
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
                date: date,
                description: description,
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