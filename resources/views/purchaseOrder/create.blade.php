
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('create-purchase-order') }}">Add Purchase Order</a></li>
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
                            <!-- <div class="card-header input-title">
                                <h4>{{__('Add New Employee')}}</h4>
                            </div> -->
                            <div class="card-body card-body-paddding">

                                <form method="post" action="{{ url('savePurchaseOrder') }}" autocomplete="off">
                                    @csrf
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Supplier Name<span
                                                    class="text-danger"> *</span></label>
                                                    <select class="form-control selectric lang" name="supplier_id" required>
                                                        <option value="">{{ __('Select supplier') }}</option>
                                                        @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"> {{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="po_no" class="form-label">Po Number<span
                                                    class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" name='po_no' id="po_no" placeholder="Enter Email" readonly required>

                                            </div>
                                        </div>
                                    </div>
                                    {{--table row --}}
                                    <div class="row" style="overflow-x:auto;">
                                        <table class="table table-togglable table-hover">
                                            <thead class="bg-success text-white">
                                            <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Unit</th>
                                            <th scope="col">Purchase Price</th>
                                            <th scope="col">Selling Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Vat in %</th>
                                            <th scope="col">Vat Total</th>
                                            <th scope="col">Total Price</th>
                                            <th scope="col">Remove</th>
                                            </tr>
                                        </thead> 
                                                <tbody id="tbody">
                                                
                                                
                                                </tbody>
                                            </table>

                                    </div>
                                    {{--/table row --}}
                                    <button type="button" class="btn btn-bordered btn-primary" style="float: right;" onclick="addItem()">Add New Item</button>
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Order Date<span
                                                    class="text-danger"> *</span></label>
                                                <input type="date" name="date" required class="form-control" id="date" placeholder="Enter date">
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
                                                <input type="number" name="paid_amount" required class="form-control" id="paid_amount" placeholder="Enter Paid Amount" value="0" onchange="calculateRemaining()" onkeyup="calculateRemaining()">
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
                                    <div class="mb-3 mt-3">
                                        <div class="d-grid">
                                            <button  type="submit" class="btn btn-info">Save</button>
                                        </div>
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

        $.ajax({
            url: '{{ route("get-po-no") }}',
            method: 'GET',
            success: function (response) {
                console.log(response);
                $("#po_no").val(response);
                
            },
            error: function (xhr, status, error) {
                console.error(error); 
            }
        });
   
});
var items=0 ;

var total_rows = 0;
function addItem()
        {

            total_rows++;
            items++;
            var html = "<tr>" +items+ "</tr>";
            html += '<td><select required name="itemid[]" style=" width:190px;" id="item_list_'+items+'"     onchange="select_Item(this.value,'+items+')"  class="form-control"> <option value="">Select Item</option>@foreach($items as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>';
                html += "<td><input required type='text'  min='0.00' step='0.01' autocomplete='off'  id='unit_"+items+"' name='unit[]' placeholder='Unit' disabled class='form-control custom-input'></td>";
                html += "<td><input required type='number' min='0.00' step='0.01' autocomplete='off'  id='unitprice_"+items+"'    onchange='calculatetotal("+items+")' onkeyup='calculatetotal("+items+")' name='unit_price[]' placeholder='Unit Price' class='form-control custom-input'></td>";
                html += "<td><input required type='number' min='0.00' step='0.01' autocomplete='off'  id='selling_price_"+items+"'  name='selling_price[]' placeholder='Selling Price' class='form-control custom-input'></td>";
                html += "<td> <input required type='number' min='0' autocomplete='off' id='quantity_"+items+"' onchange='calculatetotal("+items+")' onkeyup='calculatetotal("+items+")' name='quantity[]' placeholder='Quantity' class='form-control custom-input'></td>";

                html += '<td><select required name="vat_in_per[]" style=" width:100px;" id="vatInPer_' + items + '" onchange="calculatetotal(' + items + ')" class="form-control">';
                html += '<option value="0">No VAT</option>';
                html += '<option value="10">10%</option>';
                html += '<option value="15" selected>15%</option>';
                html += '<option value="20">20%</option>';
                html += '</select></td>';
                html += "<td> <input required type='number' min='0.00' step='0.01' autocomplete='off' class='total_vat form-control  custom-input' id='total_vat_"+items+"' name='total_vat[]' placeholder='Total Vat' readonly '></td>";

                html += "<td> <input required type='number' min='0.00' step='0.01' autocomplete='off' class='total_price form-control  custom-input' id='total_price_"+items+"' name='total[]' placeholder='Total' readonly '></td>";
                html += '<td> <button  style=" margin-left:10px;"  name="remove"  class="btn btn-danger btn-sm remove"> X </button></td></div>';
                html += "</tr>";
                
                
                document.getElementById("tbody").insertRow().innerHTML =html;
                $("#total_count").val(total_rows);
             
           
        }

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
                                // $('#unit_' + rowno).val(result.toString());
                                $('#unit_' + rowno).val(result.unit_name);
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
                        var vatRate = parseFloat($('#vatInPer_' + rowno).val());

                        // Calculate total price before VAT
                        var totalExcludingVAT = unitprice * quantity;
                        if (isNaN(totalExcludingVAT)) {
                            $('#total_price_' + rowno).val('');
                            $('#total_vat_' + rowno).val('');
                            return;
                        }

                        // Calculate VAT
                        var totalVAT = totalExcludingVAT * (vatRate / 100);
                        $('#total_vat_' + rowno).val(totalVAT.toFixed(2));

                        // Calculate total price including VAT
                        var totalPriceIncludingVAT = totalExcludingVAT + totalVAT;
                        $('#total_price_' + rowno).val(totalPriceIncludingVAT.toFixed(2));

                        // var total = unitprice * quantity;
                        // if (!isNaN(total)) {
                        //     $('#total_price_' + rowno).val(total.toFixed(2));
                        // } else {
                        //     $('#total_price_' + rowno).val('');
                        // }
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
