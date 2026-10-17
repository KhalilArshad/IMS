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
            <div class="breadcrumb-title pe-3">Stock</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Stock List</li>
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
                          
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                      <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Item Name</th>
                                                <th>Unit</th>
                                                <th>Alert Quantity</th>
                                                <th>Quantity</th>
                                                <th>Purchase Price</th>
                                                <th>Sale Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                        @php($totalUnitPriceQuantity = 0)
                                        @php($totalSalePriceQuantity = 0)
                                            @foreach ($stocks  as $stock)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $stock->item->name }}</td>
                                                <td>
                                                   {{ $stock->item->unit->name }}
                                                  </td>
                                                <td>
                                                   {{ $stock->item->alert_quantity }}
                                                  </td>
                                                <td>
                                                   {{ $stock->quantity }}
                                                  </td>
                                                <td>
                                                   {{ $stock->unit_price}}
                                                  </td>
                                                <td>
                                                   {{ $stock->sale_price}}
                                                  </td>
                                            </tr>
                                            @php($totalUnitPriceQuantity += $stock->unit_price * $stock->quantity)
                                            @php($totalSalePriceQuantity += $stock->sale_price * $stock->quantity)
                                            @php($i++)
                                            @endforeach
                                        </tbody>
                                          <tfoot>
                                          <tr>
                                            <td></td>
                                            <td></td=>
                                            <td></td>
                                            <td></td>
                                            <td>Total:</td>
                                            <td>{{ $totalUnitPriceQuantity }}</td>
                                            <td>{{ $totalSalePriceQuantity }}</td>
                                        </tr>
                                          </tfoot>
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
                            <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-3 p-4 rounded borderRmv">

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Name</label>
                                <input type="text" name="name" required class="form-control" id="name" placeholder="Enter Name">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Phone Number</label>
                                <input type="text" name="phone_no" class="form-control" id="phone_no" placeholder="Enter Phone No">
                                </div>

                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email">
                                </div>
                                <div class="mb-3">
                                <label for="inputProductTitle" class="form-label">Opening Balance</label>
                                <input type="number" name="opening_balance" class="form-control" id="opening_balance" placeholder="Enter Opening Balance">
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" onclick="saveSupplier();">Save changes</button>
                          
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
    function saveSupplier() {
        const name = $('#name').val();
        const phone_no = $('#phone_no').val();
        const email = $('#email').val();
        const opening_balance = $('#opening_balance').val();
        const csrf_token = '{{ csrf_token() }}';
            $.ajax({
            type: "POST",
            url: '{{ route("supplier-save") }}',
            data: {
                _token: csrf_token, 
                name: name,
                phone_no: phone_no,
                email: email,
                opening_balance: opening_balance,
            },
            success: function(res) {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                myModal.hide();
                $('#alertPlaceholder').html('<div class="alert alert-success border-0 bg-success alert-dismissible fade show" role="alert"><div class="text-white">Supplier Saved Successfully</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

            // Clear form fields
            $('#name').val('');
            $('#phone_no').val('');
            $('#email').val('');
            $('#opening_balance').val('');
            // Optionally, hide the message after a delay
            setTimeout(function() {
                window.location.reload();
            }, 1000); // Adjust time as needed
            },
        
            });
        }
    </script>

    
@endsection