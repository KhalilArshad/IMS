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
                        <li class="breadcrumb-item active" aria-current="page">Trilla Record List</li>
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
                                     <a href="{{ route('trilla-record.create') }}" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add Trilla Record</a>

                                    </div>
                            </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Trilla</th>
                                                <th>Month</th>
                                                <th>Driver Salary</th>
                                                <th>Total Expenses</th>
                                                <th>Profit</th>
                                                <th>Other Expenses</th>
                                                <th>Net Profit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($trilla_records  as $trilla)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $trilla->trilla->name . ' (' . $trilla->trilla->driver->name . ')' }}</td>
                                                <td>
                                              
                                                   {{ $trilla->month}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $trilla->driver_salary}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $trilla->total_expenses}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $trilla->profit}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $trilla->other_expense}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $trilla->net_profit}}
                                                  
                                                  </td>
                                                <td>
                                                
                                                 <div class="d-flex order-actions">
                                                    <a href="{{ route('trilla.records.edit',['id'=>$trilla->id]) }}" class="ms-3">
                                                    <i class='bx bxs-edit text-info'></i>
                                                    </a>
                                                    <a href="{{ route('trilla-record.print',$trilla->id) }}"
                                                        target="_blank"
                                                        class="ms-3">
                                                        <i class='bx bx-printer text-success'></i>
                                                    </a>
                                                    <a href="#"
                                                    class="ms-3 viewChildren"
                                                    data-children='@json($trilla->trillaRecordChild)'
                                                    data-trilla="{{ $trilla->trilla->name }} ({{ $trilla->trilla->driver->name }})"
                                                    data-month="{{ $trilla->month }}"
                                                    data-driver_salary="{{ $trilla->driver_salary }}"
                                                    data-total_expenses="{{ $trilla->total_expenses }}"
                                                    data-profit="{{ $trilla->profit }}"
                                                    data-other_expense="{{ $trilla->other_expense }}"
                                                    data-net_profit="{{ $trilla->net_profit }}"
                                                    >
                                                    <i class='bx bxs-show text-primary'></i>
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
                    </div>

                    
                    <div class="modal fade" id="childrenModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Trilla Trips</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Trip</th>
                                                <th>Diesel</th>
                                                <th>D Trip</th>
                                                <th>Maintenance</th>
                                                <th>Cash</th>
                                                <th>Bill</th>
                                            </tr>
                                        </thead>

                                        <tbody id="childrenBody">

                                        </tbody>

                                    </table>
                                   <div class="row mb-top-4">

                                        <div class="col-md-3">
                                            <label><strong>Trilla</strong></label>
                                            <p id="modal_trilla"></p>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>Month</strong></label>
                                            <p id="modal_month"></p>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>Driver Salary</strong></label>
                                            <p id="modal_driver_salary"></p>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>Total Expenses</strong></label>
                                            <p id="modal_total_expenses"></p>
                                        </div>

                                        <div class="col-md-1">
                                            <label><strong>Profit</strong></label>
                                            <p id="modal_profit"></p>
                                        </div>

                                        <div class="col-md-1">
                                            <label><strong>Other Expenses</strong></label>
                                            <p id="modal_other_expense"></p>
                                        </div>

                                        <div class="col-md-1">
                                            <label><strong>Net Profit</strong></label>
                                            <p id="modal_net_profit"></p>
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

    <script>
    $(document).ready(function () {
            $('#example').DataTable();

            $('.multiple-select').select2({
            width: '100%',
            allowClear: true
        });
    });
      

        function getTrillaData(id) {
            document.getElementById('addSupplierModalLabel').textContent = 'Update Trilla';
            const csrf_token = '{{ csrf_token() }}';
                $.ajax({
                type: "POST",
                url: '{{ route("get-trilla-data") }}',
                data: {
                    _token: csrf_token, 
                    id: id,
                },
                success: function(res) {
                    $('#name').val(res.name);
                    $('#driver_id').val(res.driver_id);
                    $('#reg_no').val(res.reg_no);
                    $('#modal').val(res.modal);
                    $('#update_trilla_id').val(id);

                },
            
                });
        }
 
        document.addEventListener("click", function(e){

            if(e.target.closest('.viewChildren')){

                let button = e.target.closest('.viewChildren');

                // Parent Data
                document.getElementById("modal_trilla").innerText = button.dataset.trilla;
                document.getElementById("modal_month").innerText = button.dataset.month;
                document.getElementById("modal_driver_salary").innerText = button.dataset.driver_salary;
                document.getElementById("modal_total_expenses").innerText = button.dataset.total_expenses;
                document.getElementById("modal_profit").innerText = button.dataset.profit;
                document.getElementById("modal_other_expense").innerText = button.dataset.other_expense;
                document.getElementById("modal_net_profit").innerText = button.dataset.net_profit;

                // Child Data
                let children = JSON.parse(button.dataset.children);

                let body = document.getElementById("childrenBody");
                body.innerHTML = "";

                children.forEach(child => {

                    body.innerHTML += `
                        <tr>
                            <td>${child.date ?? ''}</td>
                            <td>${child.from_location ?? ''}</td>
                            <td>${child.to_location ?? ''}</td>
                            <td>${child.trip_amount ?? 0}</td>
                            <td>${child.diesel ?? 0}</td>
                            <td>${child.d_trip ?? 0}</td>
                            <td>${child.maintenance ?? 0}</td>
                            <td>${child.cash_received ?? 0}</td>
                            <td>${child.bill_no ?? ''}</td>
                        </tr>
                    `;
                });

                new bootstrap.Modal(document.getElementById('childrenModal')).show();
            }

        });
    </script>

    
@endsection