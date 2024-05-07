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
