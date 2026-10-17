
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
            <div class="breadcrumb-title pe-3">Products</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Show Products</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">

                            <div id='alertContainer'>
                            </div>


                    <div class="card-body">
                        <div class="card">
                            <div class="card-header input-title">
                                <h4>{{__('Add New Products')}}</h4>
                            </div>
                            <div class="card-body card-body-paddding">
                            <form id="productForm" data-route="{{ route('saveProducts') }}">
                                @csrf
                                <div class="border border-3 p-4 rounded borderRmv">

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="product_name" required class="form-control" id="product_name" placeholder="Enter Product Name">
                                    </div>

                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" name='price' id="price" placeholder="Enter Price" required>

                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-control selectric lang" name="category" required>
                                        <option value="">{{ __('Select Root Category') }}</option>
                                         @foreach ($categories as $category)
                                          <option value="{{ $category->id }}" {{ $category->id == old('category') ? 'selected' : '' }}> {{ str_repeat('- ', $category->depth) }}{{ $category->name }}</option>
                                          @if ($category->children->isNotEmpty())
                                          @include('admin.categories.partials.subcategories', ['categories' => $category->children])
                                          @endif
                                        @endforeach
                                    </select>
                                    </div>




                                    <div class="mb-3">
                                            <div class="d-grid">
                                                <button  id="productSave" class="btn btn-info">Save</button>
                                            </div>
                                    </div>
                                </div>
                                </div>


                            </div><!--end row-->
                            </form>
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
   $(document).ready(function () {
    $('#example').DataTable();
    console.log("jQuery is ready");

    $('#productSave').click(function (event) {
        event.preventDefault();
        var form = $('#productForm');
        var formData = form.serialize();
        var route = form.data('route');
        console.log(route);

        $.ajax({
            url: route,
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);

                var status = response.status;
                var message = response.message;

                // Show dynamic alert based on the response
                var alertClass = status === 'success' ? 'success' : 'danger';
                var alertHTML = `
                    <div class="alert alert-${alertClass} alert-dismissible fade show">
                        <div class="text-white">${message}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Append the alert to a container (adjust the selector as needed)
                $('#alertContainer').html(alertHTML);
                form.trigger('reset'); // Clear the form fields

                // Automatically hide the alert after 1 second
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 4000);
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error); // Log the error to the console for debugging
            }
        });
    });
});


    </script>
@endsection
