@extends('layouts.app')
@section('style')
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section('wrapper')
    <style>
        .filter-form .form-select,
        .filter-form .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            height: calc(1.5em + 0.5rem + 2px);
        }

        .filter-form .form-label {
            font-size: 0.75rem;
        }
    </style>
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
                        <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show"
                            id="dismiss">
                            <div class="text-white">{{ Session::get('message') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

                            {{-- Driver filter + action buttons --}}
                            <div class="row g-2 mb-3 align-items-end filter-form">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
                                        <div class="col-md-6">
                                            <label class="form-label mb-0">Filter by Driver</label>
                                            <select name="driver_id" class="form-select" onchange="this.form.submit()">
                                                <option value="">-- All Drivers --</option>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}"
                                                        {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                                        {{ $driver->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">Reset</a>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('reminders.print') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            Print PDF
                                        </a>
                                        <a href="#" id="clearAllBtn" class="btn btn-outline-info btn-sm">
                                            Clear All
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:99%">
                                            <thead>
                                                <tr>
                                                    <th>Sr #</th>
                                                    <th>Customer Name</th>
                                                    <th>Reminder date</th>
                                                    <th>Message</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($i = 1)
                                                @forelse ($reminders as $reminder)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $reminder->customer?->name ?? '—' }}</td>
                                                        <td>{{ $reminder->reminder_date }}</td>
                                                        <td>{{ $reminder->message }}</td>
                                                        <td>
                                                            {{ $reminder->status == 0 ? 'Pending' : 'Sent' }}
                                                        </td>
                                                    </tr>
                                                    @php($i++)
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            No reminders found.
                                                        </td>
                                                    </tr>
                                                @endforelse
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
@section('script')
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
