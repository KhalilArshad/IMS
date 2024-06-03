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
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Cash Flow</li>
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
                    <div class="row  mb-4">
                          <?php
                            $currentDate = date('Y-m-d');
                            ?>
                            <form method="GET"  action="{{ url('cash-flow') }}" class="d-flex flex-wrap gap-2">
                            @csrf
                                <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', $oldDateFrom ?? $currentDate) }}">
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to', $oldDateTo ?? $currentDate) }}">
                                </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_to"></label>
                                <div class="form-group align-self-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                                </div>
                            </form>
                        </div>
                        <div class="row p-2">
                            <div class="col-12">
                              
                                    <div class="container border rounded">
                                        <table id="example2" class="table">
                                            <thead>
                                                <tr>
                                                <th>Sr #</th>
                                                <th>date</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                                <th>Balance</th>
                                                <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php($i = 1)
                                            @foreach ($cashFlow  as $child)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $child->date }}</td>
                                                <td>{{ $child->credit }}</td>
                                                <td> {{ $child->debit }}</td>
                                                <td>{{ $child->balance }}</td>
                                                <td>{{ $child->description}}</td>
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

    </script>

    
@endsection