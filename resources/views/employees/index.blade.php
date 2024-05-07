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
            <div class="breadcrumb-title pe-3">Employees</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('getEmployees') }}">Show Employees</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (Session::has('status'))
                            <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show" id="dismiss">
                                <div class="text-white">{{ Session::get('message')}}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                    {{ Session::forget('status') }}
                                    {{ Session::forget('message') }}
                            </div>
                            @endif
                    <div class="card-body">
                           <div class="d-lg-flex align-items-center mb-4 gap-3">
                                            <div class="ms-auto">

                                                <a href="{{ url('addEmployee') }}" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add New Employee</a>

                                            </div>
                                        </div>

                                        <div class="row p-2">

                                            {{-- <h6 class="text-info">
                                                <span class='status-span'></span> Team Leads Listing
                                            </h6> --}}
                                            <hr>
                                            <div class="row  mb-4">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Name</label>
                                                        <input type="text" name="from" id="name" class="form-control" placeholder="Enter Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Email</label>
                                                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Phone No</label>
                                                        <input type="number" name="phone_no" id="phone_no" class="form-control" placeholder="Enter Phone No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Cnic No</label>
                                                        <input type="number" name="cnic_no" id="cnic_no" class="form-control" placeholder="Enter Cnic No">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 mt-3">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">&nbsp;</label>
                                                                <button onclick="validate()" type="button" name="search" id="search" class="form-control btn btn-info" ><i class="fadeIn animated bx bx-search"></i> Search</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">&nbsp;</label>
                                                                <button onclick="reset()" type="button" name="reset" id="reset" class="form-control btn btn-danger"><i class="fadeIn animated bx bx-reset"></i>Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-5 col-sm-1 mb-2">
                                                        <div class="form-group">
                                                            <select name="records" id="records" class="form-select" onchange="validate()">
                                                                <option value="10">10</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                                <option value="500">500</option>
                                                                <option value="All">All</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive" id="responsiveDiv">
                                                      <table id="example2"  class="table table-striped table-bordered" style="width:99%">

                                                        <thead>
                                                            <tr>
                                                                <th>Sr #</th>
                                                                <th>Name</th>
                                                                 <th>Email</th>
                                                                <th>Phone No</th>
                                                                <th>Cnic No</th>
                                                                <th>Designation</th>
                                                                <th>Date Of Joining</th>
                                                                <th>Hourly Salary</th>
                                                                <th>City</th>
                                                                <th>Team Lead Name</th>
                                                                <th>Action</th>

                                                            </tr>
                                                        </thead>
                                                        {{-- <tbody id="ajaxData"> --}}
                                                            <tbody >
                                                            @php($srno = ($getEmployees->perPage() * ($getEmployees->currentPage() - 1)))
                                                            @foreach ($getEmployees as $getEmployee)
                                                            @php($srno++)
                                                            <tr>

                                                                <td>{{ $srno }}</td>
                                                                <td>{{ $getEmployee->name }}</td>
                                                                <td>{{ $getEmployee->email }}</td>
                                                                <td>{{ $getEmployee->phone_no }}</td>
                                                                <td>{{ $getEmployee->cnic_no }}</td>
                                                                <td>{{ $getEmployee->designation }}</td>
                                                                <td>{{ $getEmployee->date_of_joining }}</td>
                                                                <td>{{ $getEmployee->hourly_salary }}</td>
                                                                <td>{{ $getEmployee->city->name }}</td>
                                                                <td>{{ $getEmployee->teamLead->name  }}</td>

                                                                <td>
                                                                    <div class="d-flex order-actions">
                                                                        {{-- <a href="editCity?id={{ $teamLead->id }}" class="text-info">
                                                                            <i class="fadeIn animated bx bx-edit-alt"></i>
                                                                        </a> --}}
                                                                        <a href="viewEmployees?id={{ $getEmployee->id }}" class="text-warning">
                                                                            <i class="fadeIn animated bx bx-show-alt"></i>
                                                                        </a>
                                                                        &nbsp;&nbsp;
                                                                        <a href="deleteEmployee?id={{ $getEmployee->id }}" class="text-danger" onclick="return confirm('Are you sure you want to delete this team lead?')">
                                                                            <i class="fadeIn animated bx bx-trash-alt"></i>
                                                                        </a>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            Showing {{($getEmployees->currentpage()-1)*$getEmployees->perpage()+1}} to {{$getEmployees->currentpage()*$getEmployees->perpage()}} of  {{$getEmployees->total()}} entries
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="pagination-span" >
                                                                {{ $getEmployees->onEachSide(1)->links() }}
                                                            </span>
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
</div>
<!--end page wrapper -->
@endsection

@section("script")
    <script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <!-- Add this in your head section -->







<!-- Add this at the end of your HTML, before the closing </body> tag -->
<!-- Add this in your head section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>


<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: true,
            'searching': false,
            'paging': false,
            'sorting': false,
            'info': false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

<script>
    $(document).on('click', '.pagination-span a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('?')[1];
        searchByAjax(page);
    });

    function reset(){
        $('select').prop('selectedIndex',0);
        $("input[type=number]").val('');
        $("input[type=email]").val('');
        $("input[type=text]").val('');
        $("input[type=date]").val('');
        searchByAjax('page=1');
    }
    function validate(){
        searchByAjax('page=1');
    }

    function searchByAjax(page){
        let name = $("#name").val();
        let email = $("#email").val();
        let phone_no = $("#phone_no").val();
        let cnic_no = $("#cnic_no").val();
        let records = $("#records").val();
        let is_banned = $("#is_banned").val();

        if (records == 'All') {
            records = $("#total-records").val();
        }
        $.ajax({
                url: "{{route('search-employee-ajax')}}?"+page,
                method: 'GET',
                data: {
                    name : name,
                    email : email,
                    phone_no : phone_no,
                    cnic_no : cnic_no,
                    records : records,
                    is_banned : is_banned,
                },
                success: function(result) {
                    $("#responsiveDiv").html(result);
                        var table = $('#example2').DataTable({
                        buttons: ['copy', 'excel', 'pdf', 'print'],
                        "info": false,
                        sort: false,
                        paging: false,
                    });

                    table.buttons().container()
                    .appendTo('#example2_wrapper .col-md-6:eq(0)');
                }
            });
        }

</script>




@endsection
