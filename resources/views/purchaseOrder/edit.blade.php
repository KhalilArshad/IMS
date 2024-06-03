
@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<style>
    .custom-input {
    width: 90px;
    /* height: 20px; */
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('create-purchase-order') }}">Edit Purchase Order</a></li>
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
                        <div class="card">
                            <div class="card-body card-body-paddding">

                                <form method="post" action="{{ url('updatePurchaseOrder') }}" autocomplete="off">
                                    @csrf

                                    <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Supplier Name<span
                                                    class="text-danger"> *</span></label>
                                                    <select class="form-control selectric lang" name="supplier_id" required>
                                                        <option value="">{{ __('Select supplier') }}</option>
                                                        @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}" @if($supplier->id == $purchaseOrder->supplier_id) selected @endif> {{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="po_no" class="form-label">Po Number<span
                                                    class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" name='po_no' value="{{$purchaseOrder->po_no}}" id="po_no" placeholder="Enter Email" required>

                                            </div>
                                        </div>
                                    </div>
                                    {{--table row --}}
                                    <div class="row" style="overflow-x:auto;">
                                        <table class="table table-togglable table-hover">
                                            <thead class="bg-success text-white">
                                            <tr>
                                            <!-- <th scope="col"></th> -->
                                            <th scope="col">Item</th>
                                            <th scope="col">Unit</th>
                                            <th scope="col">Purchase Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total Price</th>
                                            <th scope="col">Remove</th>
                                            </tr>
                                        </thead> 
                                                <tbody id="tbody">
                                                @php($rowno = 0)
                                                    @foreach ($purchaseOrderChild as $record)
                                                        @php($rowno++)
                                                        <tr>
                                                            <input type="hidden" name="purchase_order_child_id[]"
                                                                id="purchase_order_child_id_{{ $rowno }}"
                                                                style="width:120px; margin-left:5px;"
                                                                value="{{ $record->id }}" class="form-control" />
                                                            <td><input type="text" name="" id=""
                                                                    style="width:160px; margin-left:5px;"
                                                                    value="{{ $record->items->name }}" readonly
                                                                    class="form-control" /></td>
                                                            <td><input type="text" name="" id=""
                                                                    style="width:100px; margin-left:5px;"
                                                                    value="{{ $record->items->unit->name }}" readonly
                                                                    class="form-control" /></td>
                                                         
                                                            <td><input type="text" name="unit_price[]"
                                                                    id="unitprice_{{ $rowno }}"
                                                                    onkeyup="calculatetotal({{ $rowno }})"
                                                                    oninput="calculatetotal({{ $rowno }})"
                                                                    style="width:100px; margin-left:10px;"
                                                                    value="{{ $record->unit_price }}"
                                                                    class="form-control unitprice" /></td>
                                                            <td><input type="text" name="quantity[]"
                                                                    id="quantity_{{ $rowno }}"
                                                                    onkeyup="calculatetotal({{ $rowno }})"
                                                                    oninput="calculatetotal({{ $rowno }})"
                                                                    style="width:100px;  margin-left:10px;"
                                                                    value="{{ $record->quantity }}"
                                                                    class="form-control quantity" /></td>
                                                            <td><input type="text" name="total[]"
                                                                    id="total_price_{{ $rowno }}"
                                                                    onkeyup="calculatetotal({{ $rowno }})"
                                                                    class="total_price form-control"
                                                                    oninput="calculatetotal({{ $rowno }})"
                                                                    style="width:100px;  margin-left:10px;"
                                                                    value="{{ $record->total }}" /></td>
                                                      
                                                            <td>
                                                                <a href="{{ url('delete-po-item/' . $record->id) }}"
                                                                    class="btn btn-danger">Delete</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                
                                                </tbody>
                                            </table>

                                    </div>
                                    {{--/table row --}}
                                    <!-- <button type="button" class="btn btn-bordered btn-primary" style="float: right;" onclick="addItem()">Add New Item</button> -->
                                    <div class="row mt-4">
                                        <?php
                                        $date = date('Y-m-d', strtotime($purchaseOrder->date));
                                        ?>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Order Date<span
                                                    class="text-danger"> *</span></label>
                                                <input type="date" value="{{$date}}" name="date" required class="form-control" id="date" placeholder="Enter date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="total_bill" class="form-label">Total Bill<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='total_bill' id="total_bill" placeholder="Total Bill" readonly required>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="paid_amount" class="form-label">Paid Amount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" name="paid_amount" required class="form-control" id="paid_amount" placeholder="Enter Paid Amount" value="{{ $purchaseOrder->current_payment??0 }}" onchange="calculateRemaining()" onkeyup="calculateRemaining()">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="remaining" class="form-label">Remaining<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='remaining' id="remaining" placeholder="Remaining" readonly required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3 d-flex justify-content-between">
                                        <a href="{{ route('purchase-order-list') }}" class="btn btn-secondary">Back</a>
                                        <button type="submit" class="btn btn-info">Update</button>
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

        $(document).on('click','.remove',function(){
                    $(this).closest('tr').remove();
                    total_rows=total_rows-1;
                    $("#total_count").val(total_rows);
                    final_amount();    
                });

                function select_Item(item_id,rowno)
                        {
                            var _token = $('input[name="_token"]').val();
                            $.ajax({
                            url: "{{ route('getItemUnit')}}",
                            method:'POST',
                            data:{ _token:_token,item_id:item_id},
                            success:function(result)
                            {
                                $('#unit_' + rowno).val(result.toString());
                            // $('#unit_'+rowno+'').html(result);
                            // $('#item_list_'+rowno+'').html('');
                            // $('#quantity_'+rowno+'').val(0);
                            // $('#total_price_'+items+'').val(0);
                            }
                            
                            }); 
                    }
                    function calculatetotal(rowno) {
                        var unitprice = parseFloat($('#unitprice_' + rowno).val());
                        var quantity = parseInt($('#quantity_' + rowno).val());
                        var total = unitprice * quantity;
                        if (!isNaN(total)) {
                            $('#total_price_' + rowno).val(total.toFixed(2));
                        } else {
                            $('#total_price_' + rowno).val('');
                        }
                        updateTotalBill();
                    }

                    function updateTotalBill() {
                        var total = 0;
                        $('.total_price').each(function() {
                            var val = parseFloat($(this).val());
                            if (!isNaN(val)) {
                                total += val;
                            }
                        });
                        $('#total_bill').val(total.toFixed(2));
                        calculateRemaining();
                    }
                    updateTotalBill();
                    function calculateRemaining() {
                        var totalBill = parseFloat($('#total_bill').val());
                        var paidAmount = parseFloat($('#paid_amount').val());
                        var remaining = totalBill - paidAmount;
                        if (!isNaN(remaining)) {
                            $('#remaining').val(remaining.toFixed(2));
                        } else {
                            $('#remaining').val('');
                        }
                    }

                    var today = new Date();
    
    // Format the date as YYYY-MM-DD for input type date
    var formattedDate = today.toISOString().substr(0, 10);
    
    // Set the value of the input field to today's date
    document.getElementById('date').value = formattedDate;
    </script>

@endsection
