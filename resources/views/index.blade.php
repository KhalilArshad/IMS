@extends("layouts.app")
@section("style")
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
    <link href="assets/plugins/smart-wizard/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

		<link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
		<link rel="stylesheet" href="css/style.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection
<script src="assets/countdown/countdown.js" type="text/javascript"></script>

@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
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
           
            <div class="card">
            <form method="post" action="{{ url('get-dashboard-data') }}" autocomplete="off">
                @csrf
                <div class="row p-3">
                    <div class="col-md-7"></div>
                    <div class="col-md-3">
                        <select class="form-control selectric lang" name="date_filter" id="date_filter" required>
                            <option value="Today" {{ $date == 'Today' ? 'selected' : '' }}>Today</option>
                            <option value="This Week" {{ $date == 'This Week' ? 'selected' : '' }}>This Week</option>
                            <option value="This Month" {{ $date == 'This Month' ? 'selected' : '' }}>This Month</option>
                            <option value="This Year" {{ $date == 'This Year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" id="search" class="form-control btn btn-info"><i class="fadeIn animated bx bx-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


           
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">

                            <a href="">
                                <div class="p-3 border radius-10">
                                    <h4 class="text-info text-center">TOTAL SALE</h4>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary"> <strong>Total</strong> </p>
                                            <h5 class="my-1 text-info"></h5>
                                        </div>
                                        <div class=" ms-auto">
                                            <p class="mb-0 text-white badge bg-info rounded-pill"><strong><span style="font-weight: bold; font-size: 15px;">{{$totalSales}} AED</span></strong></p>
                                            <h5 class="my-1 text-info"></h5>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">

                            <a href="">
                                <div class="p-3 border radius-10">
                                    <h4 class="text-danger text-center">  <strong>TOTAL PURCHASE</strong></h4>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary"><strong>Total</strong>  </p>
                                            <h5 class="my-1 text-danger"></h5>
                                        </div>
                                        <div class=" ms-auto">
                                        <p class="mb-0 text-white badge bg-info rounded-pill"><strong><span style="font-weight: bold; font-size: 15px;">{{$totalPurchase}} AED</span></strong></p>
                                            <h5 class="my-1 text-danger"></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">

                            <a href="">
                                <div class="p-3 border radius-10">
                                    <h4 class="text-success text-center"><strong> Vehicle EXPENSE</strong></h4>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Total </p>
                                            <h5 class="my-1 text-success"></h5>
                                        </div>
                                        <div class=" ms-auto">
                                        <p class="mb-0 text-white badge bg-info rounded-pill"><strong><span style="font-weight: bold; font-size: 15px;">{{$vehicleExpense}} AED</span></strong></p>
                                            <h5 class="my-1 text-success"></h5>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">

                            <a href="">
                                <div class="p-3 border radius-10">
                                    <h4 class="text-primary text-center">TOTAL PROFIT</h4>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary"> <strong>Total</strong> </p>
                                            <h5 class="my-1 text-primary"></h5>
                                        </div>
                                        <div class=" ms-auto">
                                            <p class="mb-0 text-white badge bg-primary rounded-pill"><strong><span style="font-weight: bold; font-size: 15px;">{{$totalProfit - $vehicleExpense}} AED</span></strong></p>
                                            <h5 class="my-1 text-primary"></h5>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">

                            <a href="">
                                <div class="p-3 border radius-10">
                                    <h4 class="text-danger text-center">  <strong>Supplier Remaining</strong></h4>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary"><strong>Total</strong>  </p>
                                            <h5 class="my-1 text-danger"></h5>
                                        </div>
                                        <div class=" ms-auto">
                                        <p class="mb-0 text-white badge bg-info rounded-pill"><strong><span style="font-weight: bold; font-size: 15px;">{{$supplierRemaining}} AED</span></strong></p>
                                            <h5 class="my-1 text-danger"></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">

                            <a href="">
                                <div class="p-3 border radius-10">
                                    <h4 class="text-success text-center"><strong>Customer Payable</strong></h4>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Total </p>
                                            <h5 class="my-1 text-success"></h5>
                                        </div>
                                        <div class=" ms-auto">
                                        <p class="mb-0 text-white badge bg-info rounded-pill"><strong><span style="font-weight: bold; font-size: 15px;">{{$customerPayable}} AED</span></strong></p>
                                            <h5 class="my-1 text-success"></h5>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
          



                </div><!--end row-->






        </div>
    </div>
@endsection

@section("script")
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
    <script src="assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
    <script src="assets/js/index.js"></script>

    <script src="assets/plugins/chartjs/js/apexcharts.min.js"></script>
    <script src="assets/plugins/chartjs/js/chart.js.2.8.0.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#table5').DataTable( {
                    lengthChange: false,
                    'searching': false,
                    'paging': false,
                    'sorting': false,
                } );

                table.buttons().container()
                    .appendTo( '#table_wrapper .col-md-6:eq(0)' );
        });
                                    

    // $('#date_filter').on('change', function() {

    //     var date_filter = $(this).val();

    //     console.log('property_selling_status: '+date_filter);
    //     const csrf_token = '{{ csrf_token() }}';
    //     $.ajax({
    //             type: "POST",
    //             url: '{{ route("get-dashboard-data") }}',
    //             data: {
    //                 _token: csrf_token, 
    //                 date_filter: date_filter,
    //             },
    //             success: function(res) {
    //                 console.log(res)
                

    //             },
            
    //         });
        
    //     });
    </script>




@endsection
