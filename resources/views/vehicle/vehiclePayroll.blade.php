@extends('layouts.app')

@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
                <div class="breadcrumb-title pe-3">Vehicles</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Vehicles List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Year Payroll Editor</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if (Session::has('status'))
                <div
                    class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show">
                    <div class="text-white">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ Session::forget('status') }}
                    {{ Session::forget('message') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div>
                            <p class="text-uppercase text-muted mb-1" style="letter-spacing: 0.12em;">Payroll Workspace</p>
                            <h3 class="mb-1">{{ $vehicle->name }}</h3>
                            <p class="mb-0 text-muted">
                                {{ $vehicle->type ?? 'Vehicle' }} |
                                Last saved month: <strong>{{ $lastMonth ?? 'No payroll saved yet' }}</strong>
                            </p>
                        </div>
                        <form method="get" action="{{ url('vehicle-payroll') }}"
                            class="d-flex flex-wrap align-items-end gap-2">
                            <input type="hidden" name="id" value="{{ $vehicle->id }}">
                            <div>
                                <label for="year" class="form-label mb-1">Payroll Year</label>
                                <select name="year" id="year" class="form-select">
                                    @foreach ($yearOptions as $y)
                                        <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">Open Year</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-primary bg-opacity-10 h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Base Amount</p>
                            <h4 class="mb-0">{{ number_format($payrollRows->first()['salary'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-warning bg-opacity-10 h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Current Advance</p>
                            <h4 class="mb-0">{{ number_format($payrollRows->first()['advance'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-danger bg-opacity-10 h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Current Remaining</p>
                            <h4 class="mb-0">{{ number_format($payrollRows->last()['remaining'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success bg-opacity-10 h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Editing Year</p>
                            <h4 class="mb-0">{{ $selectedYear }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h4 class="mb-1">Year-Wise Vehicle Installment</h4>
                            <p class="text-muted mb-0">Use the plus button to add the next month row, edit any saved month,
                                and save the whole year together.</p>
                        </div>
                        <button type="button" class="btn btn-primary" id="add-row-btn">
                            <i class="bx bx-plus"></i> Add Month Row
                        </button>
                    </div>

                    <form method="post" action="{{ route('save-vehicle-payroll') }}" autocomplete="off"
                        id="year-payroll-form">
                        @csrf
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="payroll-rows-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 140px;">Month</th>
                                        <th style="min-width: 130px;">Total Amount</th>
                                        <th style="min-width: 130px;">Advance Amount</th>
                                        <th style="min-width: 130px;">Old Remaining</th>
                                        <th style="min-width: 130px;">Paid In Old</th>
                                        <th style="min-width: 120px;">Overtime</th>
                                        <th style="min-width: 130px;">Current Paid</th>
                                        <th style="min-width: 140px;">Overall Remaining</th>
                                        <th style="min-width: 110px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="payroll-rows-body">
                                    @foreach ($payrollRows as $i => $r)
                                        <tr class="payroll-row">
                                            <td>
                                                <input type="hidden" name="rows[{{ $i }}][id]"
                                                    value="{{ $r['id'] }}">
                                                <select name="rows[{{ $i }}][month]"
                                                    class="form-select month-input">
                                                    <option value="">Select Month</option>
                                                    @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                                        <option value="{{ $m }}"
                                                            {{ $r['month'] == $m ? 'selected' : '' }}>
                                                            {{ $m }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" step="0.01" min="0"
                                                    name="rows[{{ $i }}][salary]"
                                                    class="form-control salary-input" value="{{ $r['salary'] }}"></td>
                                            <td><input type="number" step="0.01" min="0"
                                                    name="rows[{{ $i }}][advance]"
                                                    class="form-control advance-input" value="{{ $r['advance'] }}"></td>
                                            <td><input type="number" step="0.01" min="0"
                                                    name="rows[{{ $i }}][old_remaining]"
                                                    class="form-control old-remaining-input"
                                                    value="{{ $r['old_remaining'] }}"></td>
                                            <td><input type="number" step="0.01" min="0"
                                                    name="rows[{{ $i }}][paid_in_advance]"
                                                    class="form-control paid-old-input"
                                                    value="{{ $r['paid_in_advance'] }}"></td>
                                            <td><input type="number" step="0.01" min="0"
                                                    name="rows[{{ $i }}][overtime]"
                                                    class="form-control overtime-input" value="{{ $r['overtime'] }}">
                                            </td>
                                            <td><input type="number" step="0.01" min="0"
                                                    name="rows[{{ $i }}][current_paid]"
                                                    class="form-control current-paid-input"
                                                    value="{{ $r['current_paid'] }}"></td>
                                            <td><input type="number" step="0.01" min="0"
                                                    class="form-control remaining-input" value="{{ $r['remaining'] }}"
                                                    readonly></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary add-row-inline">
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-row">
                                                        <i class="bx bx-minus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-1">Months Added</p>
                                    <h5 class="mb-0" id="summary-total-months">0</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-1">Year Amount Total</p>
                                    <h5 class="mb-0" id="summary-total-salary">0.00</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-1">Latest Overall Remaining</p>
                                    <h5 class="mb-0" id="summary-final-remaining">0.00</h5>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success px-4">Save Year Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const payrollMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
        ];
        const defaultSalary = {{ (float) $payrollRows->first()['salary'] }};
        const defaultAdvance = {{ (float) $payrollRows->first()['advance'] }};
        const defaultRemaining = {{ (float) $payrollRows->last()['remaining'] }};
        let payrollRowIndex = {{ count($payrollRows) }};

        function parseNumber(value) {
            const number = parseFloat(value);
            return isNaN(number) ? 0 : number;
        }

        function recalculateRow(row) {
            const salary = parseNumber(row.querySelector('.salary-input').value);
            const oldRemaining = parseNumber(row.querySelector('.old-remaining-input').value);
            const paidOld = parseNumber(row.querySelector('.paid-old-input').value);
            const overtime = parseNumber(row.querySelector('.overtime-input').value);
            const currentPaid = parseNumber(row.querySelector('.current-paid-input').value);
            const totalRemaining = oldRemaining + salary + overtime - paidOld - currentPaid;

            row.querySelector('.remaining-input').value = Math.max(totalRemaining, 0).toFixed(2);
        }

        function recalculateSummary() {
            const rows = document.querySelectorAll('.payroll-row');
            let monthCount = 0;
            let salaryTotal = 0;
            let finalRemaining = 0;

            rows.forEach((row) => {
                const month = row.querySelector('.month-input').value;
                if (month) {
                    monthCount++;
                }

                salaryTotal += parseNumber(row.querySelector('.salary-input').value);
                finalRemaining = parseNumber(row.querySelector('.remaining-input').value);
            });

            document.getElementById('summary-total-months').textContent = monthCount;
            document.getElementById('summary-total-salary').textContent = salaryTotal.toFixed(2);
            document.getElementById('summary-final-remaining').textContent = finalRemaining.toFixed(2);
        }

        function bindRowEvents(row) {
            row.querySelectorAll('input, select').forEach((input) => {
                input.addEventListener('input', () => {
                    recalculateRow(row);
                    recalculateSummary();
                });

                input.addEventListener('change', () => {
                    recalculateRow(row);
                    recalculateSummary();
                });
            });

            row.querySelector('.add-row-inline').addEventListener('click', addPayrollRow);
            row.querySelector('.remove-row').addEventListener('click', () => removePayrollRow(row));
            recalculateRow(row);
        }

        function buildMonthOptions() {
            return payrollMonths.map((month) => `<option value="${month}">${month}</option>`).join('');
        }

        function addPayrollRow() {
            const tbody = document.getElementById('payroll-rows-body');
            const row = document.createElement('tr');
            row.className = 'payroll-row';
            row.innerHTML = `
            <td>
                <input type="hidden" name="rows[${payrollRowIndex}][id]" value="">
                <select name="rows[${payrollRowIndex}][month]" class="form-select month-input">
                    <option value="">Select Month</option>
                    ${buildMonthOptions()}
                </select>
            </td>
            <td><input type="number" step="0.01" min="0" name="rows[${payrollRowIndex}][salary]" class="form-control salary-input" value="${defaultSalary}"></td>
            <td><input type="number" step="0.01" min="0" name="rows[${payrollRowIndex}][advance]" class="form-control advance-input" value="${defaultAdvance}"></td>
            <td><input type="number" step="0.01" min="0" name="rows[${payrollRowIndex}][old_remaining]" class="form-control old-remaining-input" value="${defaultRemaining}"></td>
            <td><input type="number" step="0.01" min="0" name="rows[${payrollRowIndex}][paid_in_advance]" class="form-control paid-old-input" value="0"></td>
            <td><input type="number" step="0.01" min="0" name="rows[${payrollRowIndex}][overtime]" class="form-control overtime-input" value="0"></td>
            <td><input type="number" step="0.01" min="0" name="rows[${payrollRowIndex}][current_paid]" class="form-control current-paid-input" value="0"></td>
            <td><input type="number" step="0.01" min="0" class="form-control remaining-input" value="${defaultRemaining}" readonly></td>
            <td>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary add-row-inline"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bx bx-minus"></i></button>
                </div>
            </td>
        `;

            tbody.appendChild(row);
            payrollRowIndex++;
            bindRowEvents(row);
            recalculateSummary();
        }

        function removePayrollRow(row) {
            const rows = document.querySelectorAll('.payroll-row');
            if (rows.length === 1) {
                row.querySelectorAll('input').forEach((input) => {
                    if (input.type !== 'hidden') {
                        input.value = input.classList.contains('remaining-input') ? '0.00' : 0;
                    }
                });
                row.querySelector('.month-input').value = '';
                recalculateRow(row);
                recalculateSummary();
                return;
            }

            row.remove();
            recalculateSummary();
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.payroll-row').forEach(bindRowEvents);
            document.getElementById('add-row-btn').addEventListener('click', addPayrollRow);
            recalculateSummary();
        });
    </script>
@endsection
