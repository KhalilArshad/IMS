
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
            <div class="breadcrumb-title pe-3">Sales</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('create-invoice') }}">Stock Assign To Driver</a></li>
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

                                <form method="post" action="{{ url('saveStockAssignToDriver') }}" autocomplete="off">
                                    @csrf
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Driver Name<span
                                                    class="text-danger"> *</span></label>
                                                    <select class="form-control selectric lang" name="driver_id" id="driver_id" required>
                                                        <option value="">{{ __('Select driver') }}</option>
                                                        @foreach ($drivers as $driver)
                                                        <option value="{{ $driver->id }}"> {{ $driver->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                        <?php
                                            $currentDate = date('Y-m-d');
                                            ?>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date<span
                                                    class="text-danger"> *</span></label>
                                                <input type="date" name="date" required class="form-control"  value="{{$system_date}}" placeholder="Enter date">
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
                                            <th scope="col">Driver Prev Stock</th>
                                            <th scope="col">Current Purchase Stock</th>
                                            <!-- <th scope="col">Purchase Price</th> -->
                                            <!-- <th scope="col">Selling Price</th> -->
                                            <th scope="col">Quantity</th>
                                            <!-- <th scope="col">Vat in %</th> -->
                                            <!-- <th scope="col">Vat Total</th> -->
                                            <!-- <th scope="col">Total Price</th> -->
                                            <th scope="col">Remove</th>
                                            </tr>
                                        </thead> 
                                                <tbody id="tbody">
                                                
                                                
                                                </tbody>
                                            </table>

                                    </div>
                                    {{--/table row --}}
                               
                                    <div class="mt-4">
                                    <button type="button" class="btn btn-bordered btn-primary" style="float: right;" onclick="addItem()">Add New Item</button>
                                    </div>
                                    <!-- <div class="mt-4"></div> -->
                                    <!-- <div class="row mt-4">
                                       
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
                                    </div> -->
                                    <div class="mb-3 mt-3">
                                        <div class="">
                                            <button  type="submit" class="btn btn-info" style="width: 180px;">Save</button>
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
var items=0 ;

var total_rows = 0;
function addItem()
        {

            total_rows++;
            items++;
            var html = "<tr>" +items+ "</tr>";
            html += '<td><select required name="itemid[]" style=" width:200px;" id="item_list_'+items+'"     onchange="select_Item(this.value,'+items+')"  class="form-control"> <option value="">Select Item</option>@foreach($items as $item)<option value="{{$item->id}}">{{$item->name}}</option>@endforeach</select></td>';
                html += "<td><input required type='text'  min='0.00' step='0.01' autocomplete='off'  id='unit_"+items+"' name='unit[]' style='width:150px;' placeholder='Unit' disabled class='form-control custom-input'></td>";
                html += "<td><input required type='text'  min='0.00' step='0.01' autocomplete='off'  id='driverStock"+items+"' name='driverStock[]' style='width:150px;' placeholder='Driver Stock' disabled class='form-control custom-input'></td>";
                html += "<td><input required type='text'  min='0.00' step='0.01' autocomplete='off'  id='qtyInStock"+items+"' name='qtyInStock[]' style='width:150px;' placeholder='quantity In Stock' disabled class='form-control custom-input'></td>";
                html += "<td style='display:none;><input required type='hidden' min='0.00' step='0.01' autocomplete='off'  id='purchase_price_"+items+"'  name='purchase_price[]' placeholder='purchase Price' class='form-control custom-input'></td>";
                // html += "<td><input required type='number' min='0.00' step='0.01' autocomplete='off'  id='sale_price_"+items+"'    onchange='calculatetotal("+items+")' onkeyup='calculatetotal("+items+")' name='selling_price[]' placeholder='selling Price'  class='form-control custom-input'></td>";
                
                html += "<td> <input required type='number' min='0' autocomplete='off' id='quantity_"+items+"' onchange='calculatetotal("+items+")' onkeyup='calculatetotal("+items+")' name='quantity[]' placeholder='Quantity' class='form-control custom-input'></td>";

                // html += '<td><select required name="vat_in_per[]" style=" width:100px;" id="vatInPer_' + items + '" onchange="calculatetotal(' + items + ')" class="form-control">';
                // html += '<option value="0">No VAT</option>';
                // html += '<option value="10">10%</option>';
                // html += '<option value="15" selected>15%</option>';
                // html += '<option value="20">20%</option>';
                // html += '</select></td>';
                // html += "<td> <input required type='number' min='0.00' step='0.01' autocomplete='off' class='total_vat form-control  custom-input' id='total_vat_"+items+"' name='total_vat[]' placeholder='Total Vat' readonly '></td>";

                // html += "<td> <input required type='number' min='0.00' step='0.01' autocomplete='off' class='total_price form-control  custom-input' id='total_price_"+items+"' name='total[]' placeholder='Total' readonly '></td>";
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
                            const driver_id = $('#driver_id').val();
                            $.ajax({
                            url: "{{ route('getItemUnit')}}",
                            method:'POST',
                            data:{ _token:_token,item_id:item_id,driver_id:driver_id},
                            success:function(result)
                            {
                                console.log(result)
                                $('#unit_' + rowno).val(result.unit_name);
                                $('#qtyInStock' + rowno).val(result.total_stock);
                                $('#purchase_price_' + rowno).val(result.purchase_price);
                                $('#driverStock' + rowno).val(result.driverCurrentStock);
                            // $('#unit_'+rowno+'').html(result);
                            // $('#item_list_'+rowno+'').html('');
                            // $('#quantity_'+rowno+'').val(0);
                            // $('#total_price_'+items+'').val(0);
                            }
                            
                            }); 
                    }
                    function calculatetotal(rowno) {
                        var sale_price = parseFloat($('#sale_price_' + rowno).val());
                        var quantity = parseInt($('#quantity_' + rowno).val());
                        var qtyInStock = parseInt($('#qtyInStock' + rowno).val());
                        var vatRate = parseFloat($('#vatInPer_' + rowno).val());

                        if (quantity > qtyInStock) {
                            // Show error message
                            alert('Quantity must be less than or equal to quantity in stock');
                            $('#quantity_' + rowno).val(''); 
                            $('#total_price_' + rowno).val(''); 
                            $('#total_bill').val(''); 
                            $('#remaining').val(''); 
                            $('#total_vat_' + rowno).val('');
                            return;
                        }
                       
                        // Calculate total price before VAT
                        var totalExcludingVAT = sale_price * quantity;
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

                    // var today = new Date();
    
    // Format the date as YYYY-MM-DD for input type date
    // var formattedDate = today.toISOString().substr(0, 10);
    
    // Set the value of the input field to today's date
    // document.getElementById('date').value = formattedDate;
    </script>

@endsection
