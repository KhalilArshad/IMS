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
            <div class="breadcrumb-title pe-3">List</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Products List</li>
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

                                                <a href="{{ url('addProducts') }}" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add New Product</a>

                                            </div>
                                        </div>
                                        <div class="row  mb-4">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Product Name | Product Price</label>
                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Searh Product Name | Product Price">
                                                </div>
                                            </div>

                                        </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example2"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Category Name</th>
                                                <th>Date</th>

                                            </tr>
                                        </thead>
                                        <tbody id="ajaxData">





                                            </tr>
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
    <!-- Add this in your head section -->







<!-- Add this at the end of your HTML, before the closing </body> tag -->
<!-- Add this in your head section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add this at the end of your HTML, before the closing </body> tag -->
<script>
    $(document).ready(function () {
        var delayTimer;
      fetchAllProducts();
        // Add an event listener to the search input
        $('#name').on('input', function () {
            clearTimeout(delayTimer);

            var inputValue = $(this).val();

            // Search only if the string is at least 3 characters long
            if (inputValue.length >= 3) {
                // Set a delay before triggering the search
                delayTimer = setTimeout(function () {
                    searchProducts(inputValue);
                }, 500); // Adjust the delay as needed
            }
            else
            {
                fetchAllProducts()
            }
        });

        // Function to search products and update the table
        function searchProducts(searchValue) {
            $.ajax({
                url: '{{ route("searchProducts") }}', // Replace with your Laravel route
                method: 'GET',
                data: { search: searchValue },
                success: function (data) {
                    // Update the table with the filtered results

                    $("#ajaxData").html(data);
                }
            });
        }

        // Function to fetch all products
        function fetchAllProducts() {
            $.ajax({
                url: '{{ route("searchProducts") }}', // Replace with your Laravel route
                method: 'GET',
                success: function (data) {
                    // Update the table with all products
                    $("#ajaxData").html(data);
                }
            });
        }
    });
</script>




@endsection
