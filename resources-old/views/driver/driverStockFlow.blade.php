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
            <div class="breadcrumb-title pe-3">Sale</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Driver Stock Flow</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">
                   
                    <form class="form-horizontal" action="{{ route('driverStockFlow') }}" method="get">
                <div class="row  mb-4">
                        @csrf
                        <div class="col-sm-3">
                        <div class="form-group">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from" id="from" class="form-control" value="{{ $from }}"placeholder="Enter date">
                                <input type="hidden" name="id" value="{{ $driver_id }}">
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to" id="to" class="form-control" value="{{ $to }}" placeholder="Enter date">
                            </div>
                        </div>
                        <div class="col-sm-2">
                        <div class="form-group">
                                    <label for="" class="form-label">&nbsp;</label>
                                    <button type="submit"  id="search" class="form-control btn btn-info" ><i class="fadeIn animated bx bx-search"></i> Search</button>
                                </div>
                        </div>
                    </form>
                    <div class="col-sm-1">
                        <div class="form-group">
                                    <label for="" class="form-label">&nbsp;</label>
                                  
                                    <a href="{{ route('driverStock-history') }}" class="btn btn-info">Back</a>
                                    
                                </div>
                        </div>
                </div>
                    <hr>
                    <div class="row p-2 justify-content-center">
                        <div class="col-6 text-center"><h5><u>Driver Name: {{$driverName}}</u></h5></div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <h5 class="text-center">Received Stock</h5>
                            <div class="container border rounded">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($driverReceiveStock as $receivedItem)
                                    <tr>
                                        <td>{{$receivedItem->item->name}}</td>
                                        <td>{{$receivedItem->current_stock}}</td>
                                        <td>{{$receivedItem->date}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!-- Container for sold stock -->
                        <div class="col-6">
                            <h5 class="text-center">Sold Stock</h5>
                            <div class="container border rounded">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Customer</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <!-- <tbody>
                                    @foreach($driverSoldStock as $soldItem)
                                    <tr>
                                        <td>{{$soldItem->items->name}}</td>
                                        <td>{{$soldItem->invoice->customer->name}}</td>
                                        <td>{{$soldItem->quantity}}</td>
                                        <td>{{$soldItem->invoice->date}}</td>
                                    </tr>
                                    @endforeach
                                </tbody> -->
                            </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                    <div class="col-12">
                            <h5 class="text-center">Driver Sale Report</h5>
                            <div class="container border rounded">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Customer Name</th>
                                        <th>Invoice No</th>
                                        <th>Total Bill</th>
                                        <th>Discount</th>
                                        <th>Total After Discount</th>
                                        <th>Current Payment</th>
                                        <th>Remaining</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 0;
                                        $total_after_discount = 0;
                                        $paid_amount = 0;
                                        $remaining = 0;
                                    @endphp
                                    @foreach($driveTotalInvoices as $invoice)
                                    @php
                                        $count++;
                                        $total_after_discount += $invoice->total_after_discount;
                                        $paid_amount += $invoice->paid_amount;
                                        $remaining += $invoice->remaining;
                                    @endphp
                                    <tr>
                                        <td>{{$count}}</td>
                                        <td>{{$invoice->customer->name}}</td>
                                        <td><a href="{{ route('view-invoice', ['id' => $invoice->id]) }}" target="_blank">{{$invoice->invoice_no}}</a></td>
                                        <td>{{$invoice->total_bill}}</td>
                                        <td>{{$invoice->discount}}</td>
                                        <td>{{$invoice->total_after_discount}}</td>
                                        <td>{{$invoice->paid_amount}}</td>
                                        <td>{{$invoice->remaining}}</td>
                                        <td>{{$invoice->date}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                               
                            </table>
                             <!-- can show here -->
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <strong>Total After Discount: <u>{{$total_after_discount}}</u></strong> | &nbsp;&nbsp;
                            <strong>Total Paid Amount: <u>{{$paid_amount}}</u></strong> | &nbsp;&nbsp;
                            <strong>Total Remaining to Customer: <u>{{$remaining}}</u></strong>
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