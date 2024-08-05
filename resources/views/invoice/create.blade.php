
@extends("layouts.app")
@section("style")
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<style>
    .custom-input {
    width: 90px;
}
    .custom-input1 {
    width: 80px;
}
    .custom-input-date {
    width: 130px;
}

/* Custom CSS for Select2 to match the form input styles */
.select2-container .select2-selection--single {
    height: 38px; 
}
.select2-container--default .select2-selection--single {
    border: 1px solid #ccc; 
    border-radius: 4px; 
    background-color: #fff;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px; 
    padding-left: 8px; 
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px; 
    position: absolute;
    top: 1px; 
    right: 1px; 
    width: 20px; 
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('create-invoice') }}">Create Invoice</a></li>
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

                                <form method="post" action="{{ url('saveInvoice') }}" autocomplete="off">
                                    @csrf
                                    <div class="border border-3 p-4 rounded borderRmv">
                                       <div class="row">
                                       <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Driver Name<span
                                                    class="text-danger"> *</span></label>
                                                    <select class="form-control selectric lang" name="driver_id" id="driver_id" required  onchange="getDriverCustomer(this.value)">
                                                        <option value="">{{ __('Select driver') }}</option>
                                                        @foreach ($drivers as $driver)
                                                        <option value="{{ $driver->id }}"> {{ $driver->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Customer Name<span
                                                    class="text-danger"> *</span></label>
                                                    <select class="form-control selectric lang" name="customer_id" id="customer_id" required onchange="getItemRecord(this.value)">
                                                        <option value="">{{ __('Select customer') }}</option>
                                                        
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
                                                <input type="date" name="date" required class="form-control" value="{{$system_date}}"placeholder="Enter date">
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
                                            <th scope="col">Qty In Stock</th>
                                            <th scope="col">Purchase Price</th>
                                            <th scope="col">Last Selling Date</th>
                                            <th scope="col">Selling Price</th>
                                            <th scope="col">Quantity</th>
                                            <!-- <th scope="col">Vat in %</th> -->
                                            <!-- <th scope="col">Vat Total</th> -->
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
                                                <label for="total_bill" class="form-label">Total Bill<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='total_bill' id="total_bill" placeholder="Total Bill" readonly required>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="discount" class="form-label">Discount</label>
                                                <input type="number" value="0" class="form-control" name='discount' id="discount" placeholder="Discount" onchange="calculateRemaining()" onkeyup="calculateRemaining()">

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="total_after_discount" class="form-label">Total After Discount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='total_after_discount' id="total_after_discount" placeholder="Total After Discount" readonly required>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="paid_amount" class="form-label">Paid Amount<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" name="paid_amount" required class="form-control" id="paid_amount" placeholder="Enter Paid Amount" value="0" onchange="calculateRemaining()" onkeyup="calculateRemaining()">
                                            </div>
                                        </div>
                                     
                                    
                                    </div>
                                    <div class="row mt-4">
                                    <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="remaining" class="form-label">Remaining<span
                                                    class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name='remaining' id="remaining" placeholder="Remaining" readonly required>

                                            </div>
                                        </div>
                                       <div class="col-md-3">
                                           <div class="mb-3">
                                               <label for="old_remaining" class="form-label">Old Remaining<span
                                                   class="text-danger"> *</span></label>
                                               <input type="number" class="form-control" name='old_remaining' id="old_remaining" placeholder="Old Remaining" readonly required>

                                           </div>
                                       </div>
                                       
                                       <div class="col-md-3">
                                           <div class="mb-3">
                                               <label for="old_receive" class="form-label">Old Receive<span
                                                   class="text-danger"> *</span></label>
                                               <input type="number" class="form-control" name='old_receive' id="old_receive" value="0" placeholder="old Receive"  onchange="calculateRemaining()" onkeyup="calculateRemaining()"  required>

                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                           <div class="mb-3">
                                               <label for="net_remaining" class="form-label">Net Remaining<span
                                                   class="text-danger"> *</span></label>
                                               <input type="number" class="form-control" name='net_remaining' id="net_remaining" placeholder="Net Remaining" readonly required>

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
    $('#customer_id').select2({
        placeholder: "{{ __('Select customer') }}",
        allowClear: true
    });
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
var defaultItems = [1, 2, 3, 4];
function addItem(defaultItemId)
        {

            total_rows++;
            items++;
           
            var html = "<tr>" +items+ "</tr>";
            html += '<td><select required name="itemid[]" style="width:190px;" id="item_list_' + items + '" onchange="select_Item(this.value,' + items + ')" class="form-control">';
            html += '<option value="">Select Item</option>';
            @foreach($items as $item)
            html += '<option value="{{$item->id}}" ' + (defaultItemId && defaultItemId == {{$item->id}} ? 'selected' : '') + '>{{$item->name}}</option>';
            @endforeach
            html += '</select></td>';
                html += "<td><input required type='text'  min='0.00' step='0.01' autocomplete='off'  id='unit_"+items+"' name='unit[]' placeholder='Unit' disabled class='form-control custom-input1'></td>";
                html += "<td><input required type='text'  min='0.00' step='0.01' autocomplete='off'  id='qtyInStock"+items+"' name='qtyInStock[]' placeholder='quantity In Stock' disabled class='form-control custom-input1'></td>";
                html += "<td><input required type='number' min='0.00' step='0.01' autocomplete='off'  id='purchase_price_"+items+"' readonly name='purchase_price[]' placeholder='purchase Price' class='form-control custom-input1'></td>";
                html += "<td><input required type='date' min='0.00' step='0.01' autocomplete='off'  id='last_selling_date_"+items+"' readonly name='last_selling_date[]' placeholder='last selling date' class='form-control custom-input-date'></td>";
                html += "<td><input required type='number' min='0.00' step='0.01' autocomplete='off'  id='sale_price_"+items+"'    onchange='calculatetotal("+items+")' onkeyup='calculatetotal("+items+")' name='selling_price[]' placeholder='selling Price'  class='form-control custom-input'></td>";
                
                html += "<td> <input required type='number' min='0' autocomplete='off' id='quantity_"+items+"' onchange='calculatetotal("+items+")' onkeyup='calculatetotal("+items+")' name='quantity[]' placeholder='Quantity' class='form-control custom-input'></td>";

                // html += '<td><select required name="vat_in_per[]" style=" width:100px;" id="vatInPer_' + items + '" onchange="calculatetotal(' + items + ')" class="form-control">';
                // html += '<option value="0" selected>No VAT</option>';
                // html += '<option value="15">15%</option>';
                // html += '</select></td>';
                // html += "<td> <input required type='number' min='0.00' step='0.01' autocomplete='off' class='total_vat form-control  custom-input' id='total_vat_"+items+"' name='total_vat[]' placeholder='Total Vat' readonly '></td>";

                html += "<td> <input required type='number' min='0.00' step='0.01' autocomplete='off' class='total_price form-control  custom-input' id='total_price_"+items+"' name='total[]' placeholder='Total' readonly '></td>";
                html += '<td> <button  style=" margin-left:10px;"  name="remove"  class="btn btn-danger btn-sm remove"> X </button></td></div>';
                html += "</tr>";
                
                
                document.getElementById("tbody").insertRow().innerHTML =html;
                $("#total_count").val(total_rows);
             
           
        }
        document.addEventListener('DOMContentLoaded', function () {
            defaultItems.forEach(function(itemId) {
                addItem(itemId);
            });
        });
        function getItemRecord(value) {
            defaultItems.forEach(function(itemId, index) {
                select_Item(itemId, index + 1);
            });
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
                            const customer_id = $('#customer_id').val();
                            $.ajax({
                            url: "{{ route('getItemUnitForSale')}}",
                            method:'POST',
                            data:{ _token:_token,
                                item_id:item_id,
                                driver_id:driver_id,
                                customer_id:customer_id,
                            },
                            success:function(result)
                            {
                                // console.log(result)
                                $('#unit_' + rowno).val(result.unit_name);
                                $('#qtyInStock' + rowno).val(result.driverCurrentStock);
                                $('#purchase_price_' + rowno).val(result.purchase_price);
                                $('#sale_price_' + rowno).val(result.selling_price);
                                $('#last_selling_date_' + rowno).val(result.last_selling_date);
                            }
                            
                            }); 
                    }
                    function calculatetotal(rowno) {
                        var sale_price = parseFloat($('#sale_price_' + rowno).val());
                        var quantity = parseInt($('#quantity_' + rowno).val());
                        var qtyInStock = parseInt($('#qtyInStock' + rowno).val());
                        // var vatRate = parseFloat($('#vatInPer_' + rowno).val());

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
                        // var totalVAT = totalExcludingVAT * (vatRate / 100);
                        // $('#total_vat_' + rowno).val(totalVAT.toFixed(2));

                        // Calculate total price including VAT
                        // var totalPriceIncludingVAT = totalExcludingVAT + totalVAT;
                        $('#total_price_' + rowno).val(totalExcludingVAT.toFixed(2));
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
                        var discount = parseFloat($('#discount').val());
                        var totalAfterDis = totalBill -discount;
                        
                        if (!isNaN(totalAfterDis)) {
                            $('#total_after_discount').val(totalAfterDis.toFixed(2));
                        } else {
                            $('#total_after_discount').val('');
                        }
                        var totalAfterDiscount = parseFloat($('#total_after_discount').val());
                        var remaining = totalAfterDiscount - paidAmount;
                        if (paidAmount > totalAfterDiscount) {
                            alert('Paid amount cannot be greater than the total bill.');
                            document.getElementById('paid_amount').value = totalAfterDiscount;
                            remaining = 0;
                        }
                        if (!isNaN(remaining)) {
                            $('#remaining').val(remaining.toFixed(2));
                        } else {
                            $('#remaining').val('');
                        }
                        calculateNetRemaining();
                    }
                    function calculateNetRemaining() {
                        var old_receive = parseFloat($('#old_receive').val());
                        var old_remaining = parseFloat($('#old_remaining').val());
                        var net_remaining = old_remaining - old_receive;
                        
                        var totalBill = parseFloat($('#total_bill').val());
                        var paidAmount = parseFloat($('#paid_amount').val());
                        var bill_remaining = totalBill - paidAmount;
                         var total_remaining =net_remaining + bill_remaining;
                        if (old_receive > old_remaining) {
                            alert('Old Receive cannot be greater than the Old Remaining.');
                            document.getElementById('old_receive').value = old_remaining;
                            total_remaining = total_remaining;
                        }
                        if (!isNaN(total_remaining)) {
                            $('#net_remaining').val(total_remaining.toFixed(2));
                        } else {
                            $('#net_remaining').val('');
                        }
                    }
    function getDriverCustomer(driver_id) {
            if (driver_id) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('getDriverCustomer') }}",
                    method: 'POST',
                    data: {
                        _token: _token,
                        driver_id: driver_id
                    },
                    success: function(result) {
              
                        var existingOptions = $('#customer_id').children().clone();
                        // Clear current options and add the default
                        $('#customer_id').empty().append('<option value="">{{ __("Select customer") }}</option>');
                        // Append new options from the AJAX response at the top (after the default option)
                        result.customers.forEach(function(customer) {
                            $('#customer_id').append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
                        });
                        
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.log("Error fetching customer data: " + error);
                        alert('Failed to retrieve customer data. Please try again.');
                        $('#customer_id').empty().append('<option value="">{{ __("Failed to load customers") }}</option>');
                    }

                });
            } else {
                // alert('Select Category');
                $('#customer_id').html('');
            }

        }

        $(document).ready(function() {
            $('#customer_id').change(function() {
                var customer_id = $(this).val();
                console.log(customer_id)
                if (customer_id) {
                    const csrf_token = '{{ csrf_token() }}';
                    $.ajax({
                    type: "POST",
                    url: '{{ route("get-customer-remaining") }}',
                    data: {
                        _token: csrf_token, 
                        id: customer_id,
                    },
                    success: function(res) {
                        console.log(res)
                        $('#old_remaining').val(res.previous_balance);

                    },
                
                    });
                } else {
                    $('#old_remaining').val('');
                }
            });
        });
    </script>

@endsection
