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
            <div class="breadcrumb-title pe-3">Customer</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Ledger</li>
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
                        <form class="form-horizontal" action="{{ url('getCustomerLedger') }}" method="post">
                    <div class="row  mb-4">
                        @csrf
                        <div class="col-sm-3">
                        <div class="form-group">
                            <label for="" class="form-label">Customer Name</label>
                                <select class="select2-single form-control" name="customer_id" id="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" @if($customer->id == $customer_id) selected @endif>{{$customer->name}}</option>
                                    @endforeach
                            
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <div class="form-group">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from" id="from" class="form-control" value="" placeholder="Enter date">
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to" id="to" class="form-control" value="" placeholder="Enter date">
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
                                    <button type="button"  id="payButton"  class="form-control btn btn-info">Pay</button>
                                </div>
                        </div>
                </div>
                    <hr>
                    <div id="alertPlaceholder" class="container mt-3"></div>
                          
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                      <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Customer Name</th>
                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if (isset($flag))
                                        @php($i = 1)
                                            @foreach ($customerLedgerArray  as $customerLedger)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $customerLedger->customer->name }}</td>
                                                <td>{{ $customerLedger->invoice->invoice_no??'' }}</td>
                                                <td>{{ $customerLedger->date}}  </td>
                                                <td> {{ $customerLedger->debit}}</td>
                                                <td> {{ $customerLedger->credit}}</td>
                                                <td> {{ $customerLedger->balance}}</td>
                                                <td> {{ $customerLedger->description}}</td>
                                            </tr>
                                            @php($i++)
                                            @endforeach
                                       
                                        </tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total Remaining</td>
                                            <td></td>
                                            <td>{{$remaining}}</td>
                                        </tr>
                                        @endif
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
    $(document).ready(function() {
        $('#payButton').click(function() { // Attach the click event directly to the Pay button
            var customer_id = $('#customer_id').val();  // Get the selected supplier's ID

            if (customer_id) {  // Check if a supplier ID is selected
                var url = "customer-receivable?id=" + customer_id;  // Create the URL
                window.location.href = url;  // Redirect to the URL
            } else {
                alert('Please select a Customer first.');  // Alert if no supplier is selected
            }
        });
    });
    </script>

    
@endsection