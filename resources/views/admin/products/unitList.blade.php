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
            <div class="breadcrumb-title pe-3">products</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('add-unit') }}">Unit List</a></li>
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
                    <div class="card-header input-title">
                                <h4>{{__('Add New Unit')}}</h4>
                            </div>
                            <div class="card-body card-body-paddding">
                            <form method="post" action="{{ url('saveUnits') }}" >
                                @csrf
                                <div class="border border-3 p-4 rounded borderRmv">

                                <div class="mb-3">
                                        <label for="unit_name" class="form-label">Name<span
                                                class="text-danger"> *</span></label>
                                        <input type="text" name="unit_name" required class="form-control" id="unit_name" placeholder="Enter Unit Name">
                                        <input type="hidden" name="update_unit_id"  class="form-control" id="update_unit_id">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" name='description' id="description" placeholder="Enter description" >

                                    </div>

                                    <br>



                                    <div class="mb-3">
                                            <div class="d-grid">
                                                <button  type="submit" class="btn btn-info">Save</button>
                                            </div>
                                    </div>
                                </div>
                                </div>


                            </div><!--end row-->
                            </form>
                        <div class="row p-2">
                            <div class="col-12">
                            <div class="table-responsive" >
                                      <table id="example2"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ajaxData">

                                        @foreach ($units as $unit)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $unit->name }}</td>
                                                <td>{{ $unit->description }}</td>
                                                <td>
                                                    <div class="d-flex order-actions">
                                                        <!-- <a href="" class="text-info">
                                                            <i class="fadeIn animated bx bx-edit-alt"></i>
                                                        </a> -->
                                                        <a href="#"  onclick="getUnitData('{{ $unit->id }}', '{{ $unit->name }}', '{{ $unit->description }}')" class="ms-3"><i class='bx bxs-edit text-info'></i></a>
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
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: true,
            'searching': true,
            'paging': true,
            'sorting': false,
            'info': false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });

    function getUnitData(id, name, description) {
            
            $('#unit_name').val(name);
            $('#description').val(description);
            $('#update_unit_id').val(id);
        }
    </script>

    
@endsection