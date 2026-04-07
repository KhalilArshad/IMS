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
                        <li class="breadcrumb-item active" aria-current="page">Customers Reminders</li>
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
                           <div class="d-lg-flex align-items-center mb-4 gap-3">
                                    <div class="ms-auto">
                                    <a href="{{ route('reminders.print') }}"
                                    class="btn btn-outline-danger px-3"
                                    target="_blank">
                                    Print PDF
                                    </a>
                                     <!-- <a href="" class="btn btn-outline-info px-3"><i class="bx bxs-plus-square"></i> Add New Supplier</a> -->
                                    <a href="#" id="clearAllBtn" class="btn btn-outline-info px-3">
                                        Clear All
                                    </a>

                                    </div>
                            </div>
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                      <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Customer Name</th>
                                                <th>Invoice No</th>
                                                <th>Last Invoice Date</th>
                                                <th>Reminder date</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($i = 1)
                                            @foreach ($reminders  as $reminder)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                

                                                <td>{{ $reminder->customer->name }}</td>
                                                <td>
                                              
                                                   {{ $reminder->invoice->invoice_no}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $reminder->invoice->date}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $reminder->reminder_date}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $reminder->message}}
                                                  
                                                  </td>
                                                <td>
                                              
                                                   {{ $reminder->status == 0 ? 'Pending' : 'Sent' }}
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
<script>
$(document).on('click', '#clearAllBtn', function(e) {
    e.preventDefault();

    if (!confirm('Are you sure you want to clear all reminders?')) {
        return;
    }

    $.ajax({
        url: "{{ route('reminders.clearAll') }}",
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                location.reload(); // refresh table
            }
        },
        error: function() {
            alert('Something went wrong.');
        }
    });
});
</script>
@endsection