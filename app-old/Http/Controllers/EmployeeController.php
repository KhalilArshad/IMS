<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeAdvance;
use App\Models\Setting;
use App\Models\ShopLedger;
use Carbon\Carbon;
use Mpdf\Mpdf;

class EmployeeController extends Controller
{

    /**
     * Displaying the listing of users called by ajax for pagination... For user
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedTeamAjax(Request $req)
    {
        // if (User::isAuthenticatedRequest($req->user_id, $req->level) == 0) {
        //     return 'Unauthenticated Request';
        // }
        $referedBy = $req->referedBy;
        $users = User::sortable()->with('referedBy')
        ->where('reference_id',$referedBy)
        ->paginate(10);
        $level = $req->level;
        return view('user.ajax.paginated_users_ajax',['users'=>$users,'level'=>$level]);
    }


    public function getEmployees()
    {
      
        $getEmployees = Employee::orderBy('id','desc')->paginate(10);
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('employees.index',compact('getEmployees','system_date'));
    }

    public function addEmployee()
    {
        //  return 1;
        // $cities = City::get();
        return view('employees.create');
    }

    public function saveEmployee(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|max:255',
            'cnic_no' => 'required|unique:employees|min:10',
            'designation' => 'required',
            'salary' => 'required|numeric',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        DB::beginTransaction();
        try {

            $createById = Session::get('user_id');
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone_no = $request->phone_no;
            $employee->cnic_no = $request->cnic_no;
            $employee->designation = $request->designation;
            $employee->date_of_joining = $request->date_of_joining;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->advance = $request->advance_amount;
            $employee->remaining = $request->remaining_amount;
            $employee->save();
            $employee_id =$employee->id;
            
            if($request->advance_amount !=0 || $request->remaining_amount !=0){
            $date=date('Y-m-d');
            $description = 'employee added';
            $employee = new EmployeeAdvance();
            $employee->employee_id   = $employee_id;
            $employee->advance_amount     = $request->advance_amount ??0;
            $employee->paid_amount         = 0;
            $employee->remaining         = $request->remaining_amount ??0;
            $employee->date  = $date;
            $employee->description  = $description;
            $employee->save();
            }
            DB::commit();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Employee stored successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function viewEmployees(Request $request)
    {
        $employee = Employee::find($request->id);
        // $totalAdvanceAmount = Payroll::where('user_id',$request->id)->sum('advance_amount');
         $totalAdvanceAmount =0;

        return view('employees.employeeView', compact('employee','totalAdvanceAmount'));
    }

    public function updateEmployee(Request $request)
    {


        // Validation rules
        $rules = [
            'id' => 'required',
            'name' => 'required|max:255',
            'cnic_no' => 'required|unique:employees,cnic_no,' . $request->id . '|min:10',
            'salary' => 'required|numeric',
            'designation' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }


        try {

            $employee = Employee::find($request->id);
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone_no = $request->phone_no;
            $employee->cnic_no = $request->cnic_no;
            $employee->designation = $request->designation;
            $employee->date_of_joining = $request->date_of_joining;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->save();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Employee Updated successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }



    public function deleteEmployee(Request $request)
    {


        // Validation rules
        $rules = [
            'id' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }


        try {

            $employee = Employee::find($request->id);
            $employee->delete();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Employee deleted successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function AddEmployeeAdvance(Request $request){

        $query = EmployeeAdvance::query();
        if ($request->has('employee_id') && !empty($request->employee_id)) {
            $query->where('employee_id', $request->employee_id);
        }
    
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('date', '>=', $request->date_from);
        }
    
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        $query->orderBy('id', 'desc');
        $employeesAdvance = $query->get();
        // $employeesAdvance= EmployeeAdvance::with('employee')->orderBy('id','desc')->get();
        $employees= Employee::get();
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('employees.addEmployeeAdvance',compact('employeesAdvance','employees','system_date'))->with('oldEmployeeId', $request->employee_id)
        ->with('oldDateFrom', $request->date_from)
        ->with('oldDateTo', $request->date_to);
    }
    public function saveEmployeeAdvance(Request $request){

                $employee= Employee::where('id',$request->employee_id)->select('id','advance','remaining')->first();
                $employeeAdvance =$employee->advance;
                $employeeRemaining =$employee->remaining;
                if($employeeRemaining > 0){
                    if($request->advance_amount == $employeeRemaining ){
                        // $updateAdvance =  0;
                        $updateRemaining=  0;
                        Employee::where('id',$request->employee_id)
                        ->update(['remaining'=>$updateRemaining]);
                    }elseif($request->advance_amount < $employeeRemaining){
                        // $updateAdvance =  0;
                        $updateRemaining=  $employeeRemaining - $request->advance_amount;
                        Employee::where('id',$request->employee_id)
                        ->update(['remaining'=>$updateRemaining]);
                    }elseif($request->advance_amount > $employeeRemaining){
                        $updateAdvance =  $request->advance_amount -$employeeRemaining;
                        $updateRemaining= 0;
                        $updateEmployeeAdvance=$employeeAdvance+ $updateAdvance;
                        Employee::where('id',$request->employee_id)
                        ->update(['advance'=>$updateEmployeeAdvance,'remaining'=>$updateRemaining]);
                    }
                    $employee = new EmployeeAdvance();
                    $employee->employee_id   = $request->employee_id;
                    $employee->advance_amount         = $request->advance_amount;
                    $employee->paid_amount         = 0;
                    $employee->remaining         = $updateRemaining;
                    $employee->description  = $request->description;
                    $employee->date  = $request->date;
                    $employee->save();
                }else{
                    $updateAdvance =  $employeeAdvance + $request->advance_amount;
                    Employee::where('id',$request->employee_id)
                    ->update(['advance'=>$updateAdvance]);

                    $employee = new EmployeeAdvance();
                    $employee->employee_id   = $request->employee_id;
                    $employee->advance_amount         = $request->advance_amount;
                    $employee->paid_amount         = 0;
                    $employee->remaining         = 0;
                    $employee->description  = $request->description;
                    $employee->date  = $request->date;
                    $employee->save();
                }

            
        return response()->json(['success' => 'employee advance saved successfully']);
    }
    public function searchEmployeeByajax(Request $req)
    {
        $name = $req->name;
        $email = $req->email;
        $phone_no = $req->phone_no;
        $cnic_no = $req->cnic_no;

        $is_banned = $req->is_banned;
        $records = $req->records;

        $getEmployees = User::where('role_id',3)
        ->when($name,function($qu) use($name){
            $qu->where('name',$name);
        })
        ->when($email,function($qu) use($email){
            $qu->where('email',$email);
        })
        ->when($phone_no,function($qu) use($phone_no){
            $qu->where('phone_no',$phone_no);
        })
        ->when($cnic_no,function($qu) use($cnic_no){
            $qu->where('cnic_no',$cnic_no);
        })


        ->orderBy('id','desc')->paginate($records);
        return view('admin.employees.ajax.ajax-index', compact('getEmployees'));
    }

    public function createPayroll(Request $request){
        $employee = Employee::findOrFail($request->id);
        $systemDate = Setting::select('system_date')->first();
        $defaultYear = $systemDate && $systemDate->system_date
            ? Carbon::parse($systemDate->system_date)->year
            : now()->year;

        $selectedYear = (int) ($request->year ?: $defaultYear);

        $payrollRows = Payroll::where('employee_id', $employee->id)
            ->whereYear('date', $selectedYear)
            ->orderBy('date')
            ->get()
            ->map(function ($payroll) {
                return [
                    'id' => $payroll->id,
                    'month' => $payroll->month,
                    'salary' => (float) $payroll->salary,
                    'advance' => (float) $payroll->advance,
                    'old_remaining' => (float) ($payroll->add_in_remaining ?? 0),
                    'paid_in_advance' => (float) $payroll->paid_in_advance,
                    'overtime' => (float) $payroll->overtime,
                    'current_paid' => (float) $payroll->total_salary_to_be_paid,
                    'remaining' => (float) $payroll->remaining,
                ];
            });

        if ($payrollRows->isEmpty()) {
            $payrollRows = collect([[
                'id' => null,
                'month' => '',
                'salary' => (float) ($employee->salary ?? 0),
                'advance' => (float) ($employee->advance ?? 0),
                'old_remaining' => (float) ($employee->remaining ?? 0),
                'paid_in_advance' => 0,
                'overtime' => 0,
                'current_paid' => 0,
                'remaining' => (float) ($employee->remaining ?? 0),
            ]]);
        }

        $lastMonth = optional(
            Payroll::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->first()
        )->month;

        $yearOptions = Payroll::where('employee_id', $employee->id)
            ->selectRaw('YEAR(date) as payroll_year')
            ->distinct()
            ->orderBy('payroll_year', 'desc')
            ->pluck('payroll_year')
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->values()
            ->all();

        $yearOptions[] = $defaultYear;
        $yearOptions[] = $defaultYear - 1;
        $yearOptions[] = $defaultYear + 1;
        $yearOptions = collect($yearOptions)->unique()->sortDesc()->values()->all();

        return view('employees.generatePayroll', compact('employee', 'lastMonth', 'selectedYear', 'payrollRows', 'yearOptions'));
    }

    public function savePayroll(Request $request){
        $rules = array(
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer|min:2000|max:2100',
            'rows' => 'required|array|min:1',
            'rows.*.month' => 'required',
            'rows.*.salary' => 'nullable|numeric|min:0',
            'rows.*.advance' => 'nullable|numeric|min:0',
            'rows.*.old_remaining' => 'nullable|numeric|min:0',
            'rows.*.paid_in_advance' => 'nullable|numeric|min:0',
            'rows.*.overtime' => 'nullable|numeric|min:0',
            'rows.*.current_paid' => 'nullable|numeric|min:0',
            'rows.*.remaining' => 'nullable|numeric|min:0',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }

        $submittedMonths = collect($request->rows)
            ->pluck('month')
            ->filter()
            ->map(fn ($month) => strtolower(trim($month)));

        if ($submittedMonths->count() !== $submittedMonths->unique()->count()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => 'Each month can only be added once in the selected year.']);
        }

        try {
            DB::transaction(function () use ($request) {
                $monthOrder = $this->getPayrollMonthOrder();
                $selectedYear = (int) $request->year;
                $existingPayrollIds = Payroll::where('employee_id', $request->employee_id)
                    ->whereYear('date', $selectedYear)
                    ->pluck('id')
                    ->all();

                $keptPayrollIds = [];
                $finalRow = null;

                $rows = collect($request->rows)
                    ->sortBy(function ($row) use ($monthOrder) {
                        return $monthOrder[$row['month']] ?? 99;
                    })
                    ->values();

                foreach ($rows as $row) {
                    $monthName = $row['month'];
                    $monthNumber = $monthOrder[$monthName] ?? null;
                    if (!$monthNumber) {
                        throw new Exception('Invalid month selected.');
                    }

                    $date = Carbon::create($selectedYear, $monthNumber, 1)->format('Y-m-d');
                    $description = 'Payroll saved for ' . $monthName . ' ' . $selectedYear;

                    $payroll = !empty($row['id']) && in_array((int) $row['id'], $existingPayrollIds)
                        ? Payroll::find($row['id'])
                        : new Payroll();

                    $payroll->employee_id = $request->employee_id;
                    $payroll->date = $date;
                    $payroll->salary = $row['salary'] ?? 0;
                    $payroll->advance = $row['advance'] ?? 0;
                    $payroll->add_in_remaining = $row['old_remaining'] ?? 0;
                    $payroll->paid_in_advance = $row['paid_in_advance'] ?? 0;
                    $payroll->remaining = $row['remaining'] ?? 0;
                    $payroll->overtime = $row['overtime'] ?? 0;
                    $payroll->total_salary_to_be_paid = $row['current_paid'] ?? 0;
                    $payroll->description = $description;
                    $payroll->month = $monthName;
                    $payroll->save();

                    $keptPayrollIds[] = $payroll->id;
                    $finalRow = $row;
                }

                Payroll::where('employee_id', $request->employee_id)
                    ->whereYear('date', $selectedYear)
                    ->whereNotIn('id', $keptPayrollIds)
                    ->delete();

                if ($finalRow) {
                    Employee::where('id', $request->employee_id)
                        ->update([
                            'advance' => $finalRow['advance'] ?? 0,
                            'remaining' => $finalRow['remaining'] ?? 0,
                        ]);
                }
            });
            return redirect()->route('get-payroll')->with(['status' => 'success', 'message' => 'Year payroll saved successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }
    public function getPayroll(){
        $payrolls = Payroll::with('employee')
            ->orderBy('date')
            ->get()
            ->groupBy(function ($payroll) {
                return $payroll->employee_id . '-' . Carbon::parse($payroll->date)->year;
            })
            ->map(function ($records) {
                $first = $records->first();
                $last = $records->sortBy('date')->last();

                return (object) [
                    'employee_id' => $first->employee_id,
                    'employee_name' => optional($first->employee)->name,
                    'designation' => optional($first->employee)->designation,
                    'payroll_year' => Carbon::parse($first->date)->year,
                    'total_months' => $records->count(),
                    'total_salary' => $records->sum('salary'),
                    'total_advance' => $records->sum('advance'),
                    'total_paid_old' => $records->sum('paid_in_advance'),
                    'total_paid_salary' => $records->sum('total_salary_to_be_paid'),
                    'latest_remaining' => $last->remaining ?? 0,
                    'latest_date' => $last->date ?? null,
                ];
            })
            ->sortBy(function ($item) {
                return sprintf('%04d-%s', 9999 - (int) $item->payroll_year, strtolower($item->employee_name ?? ''));
            })
            ->values();

        return view('employees.payrollList', compact('payrolls'));
    }

    public function viewPayroll($employee, $year){
        $employee = Employee::findOrFail($employee);
        $payrolls = Payroll::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        abort_if($payrolls->isEmpty(), 404);

        $summary = [
            'total_salary' => $payrolls->sum('salary'),
            'total_advance' => $payrolls->sum('advance'),
            'total_old_remaining' => $payrolls->sum('add_in_remaining'),
            'total_paid_old' => $payrolls->sum('paid_in_advance'),
            'total_overtime' => $payrolls->sum('overtime'),
            'total_paid_salary' => $payrolls->sum('total_salary_to_be_paid'),
            'current_remaining' => optional($payrolls->last())->remaining ?? 0,
        ];

        $html = view('employees.employeePayrollViewPdf', [
            'employee' => $employee,
            'payrolls' => $payrolls,
            'year' => $year,
            'summary' => $summary,
        ])->render();
        $mpdf = new Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A4', 
            'autoScriptToLang' => true, 
            'autoLangToFont' => true
        ]);
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('payroll-' . $employee->id . '-year-' . $year . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    
    public function getEmployeeSalary(Request $request)
    {
        $id=$request->id;
        $salary=Employee::select('id','salary')->where('id',$id)->first();
        return $salary;
    }

    public function saveEmployeeSalary(Request $request)
    {
        $employee= Employee::where('id',$request->employee_id)->select('id','remaining','salary')->first();
        $employeeRemaining =$employee->remaining ?? 0;
        $employeeSalary =$employee->salary ?? 0;
        $updateRemaining =  $employeeRemaining + $request->basic_salary;
        // Employee::where('id',$request->employee_id)
        // ->update(['remaining'=>$updateAdvance]);
        
        $employee = Employee::find($request->employee_id);
        $employee->remaining = $updateRemaining;
        $employee->save();

        $date=date('Y-m-d');
        $description = 'employee salary ('.$employeeSalary .') added for the month of: '.$request->month;
        $employee = new EmployeeAdvance();
        $employee->employee_id   = $request->employee_id;
        $employee->advance_amount     = 0;
        $employee->paid_amount         = 0;
        $employee->remaining         = $updateRemaining;
        $employee->date  = $date;
        $employee->description  = $description;
        $employee->month  = $request->month;
        $employee->save();
    return response()->json(['success' => 'employee salary saved successfully']);
    }

    private function getPayrollMonthOrder(): array
    {
        return [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        ];
    }
}
