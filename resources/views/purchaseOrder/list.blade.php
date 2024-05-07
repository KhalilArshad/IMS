@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<style>
    .disabled-link {
    pointer-events: none; /* Disables click events */
    opacity: 0.5; /* Makes the links appear visually disabled */
    cursor: not-allowed; /* Changes the cursor to indicate not allowed */
}

.disabled-icon {
    pointer-events: none; /* Disables click events for the icon */
}
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
       

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Purchase Order</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Purchase Order List</li>
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
                                                <th>Supplier Name</th>
                                                <th>PO No</th>
                                                <th>Date</th>
                                                <th>Total Bill</th>
                                                <th>Current Payment</th>
                                                <th>Remaining</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($purchaseOrders  as $purchaseOrder)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $purchaseOrder->supplier->name }}</td>
                                                <td>
                                              
                                                   {{ $purchaseOrder->po_no}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $purchaseOrder->date}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $purchaseOrder->total_bill}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $purchaseOrder->current_payment}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $purchaseOrder->remaining}}
                                                  
                                                  </td>
                                                  <td>
                                                    @if($purchaseOrder->status =="Inprogress")
                                                    <button onclick="window.location.href='{{ route('receivePo', $purchaseOrder->id) }}'" class="btn btn-sm btn-outline-primary">
                                                        {{ $purchaseOrder->status }}
                                                    </button>
                                                    @else
                                                    <button onclick="window.location.href='{{ route('receivePo', $purchaseOrder->id) }}'" class="btn btn-sm btn-outline-success" disabled>
                                                        {{ $purchaseOrder->status }}
                                                    </button>
                                                    @endif
                                                </td>
                                                <td>
                                                
                                                 <div class="d-flex order-actions">
                                                    <a href="view-purchaseOrder/{{ $purchaseOrder->id }}" class="ms-3"  target="_blank"><i class='bx bxs-show  text-info'></i></a>
                                                    @if($purchaseOrder->status =="Inprogress")
                                                    <a href="purchase-order-edit?id={{ $purchaseOrder->id }}" class="ms-3"><i class='bx bxs-edit text-info'></i></a>
                                                    <a href="purchase-order-delete/{{ $purchaseOrder->id }}" class="ms-3"  onclick="return confirm('Are you sure you want to delete this purchase order?')"><i class='bx bxs-trash text-danger'></i></a>
                                                    @else
                                                    <a href="purchase-order-edit?id={{ $purchaseOrder->id }}" class="ms-3 disabled-link"><span class="disabled-icon"><i class='bx bxs-edit text-info'></i></span></a>
                                                    <a href="" class="ms-3 disabled-link"><span class="disabled-icon"><i class='bx bxs-trash text-danger'></i></span></a>
                                                    @endif
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
   
    </script>

    
@endsection