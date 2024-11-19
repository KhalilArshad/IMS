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
        // return $request;
        $checkPrevMonthPayroll = Payroll::where('employee_id', $request->id)
            ->select('month')
            ->orderBy('id', 'desc')
            ->first();
            $lastMonth= $checkPrevMonthPayroll->month??'';
             
            $employee= Employee::where('id',$request->id)->first();
            return view('employees.generatePayroll',compact('employee','lastMonth'));
       
    }

    public function savePayroll(Request $request){
        $rules = array(
            'employee_id' => 'required',
            'current_paid' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('employees-list')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            DB::transaction(function () use ($request) {
                $employee= Employee::where('id',$request->employee_id)->select('id','advance','remaining','salary')->first();
                $employeeAdvance =$employee->advance;
                $salary =$employee->salary;
                $updateAdvance =  $employeeAdvance - $request->paid_in_advance;
                Employee::where('id',$request->employee_id)
               ->update(['advance'=>$updateAdvance,'remaining'=>$request->total_remaining]);
              
               $system_date = Setting::select('id','system_date')->first();
                $date = $system_date->system_date;
                $months=  implode(',', $request->month);
                $description = 'Payroll Generated for the month of: '.$months;
                $payroll = new Payroll();
                $payroll->employee_id= $request->employee_id; 
                $payroll->date= $date;
                $payroll->salary= $salary;
                $payroll->advance= $request->advance;
                $payroll->paid_in_advance= $request->paid_in_advance;
                $payroll->remaining= $request->total_remaining;
                $payroll->overtime=  $request->overtime;
                $payroll->total_salary_to_be_paid= $request->current_paid;
                $payroll->description= $description;
                $payroll->month= $months;
                $payroll->save();

                $employee = new EmployeeAdvance();
                $employee->employee_id   = $request->employee_id;
                $employee->advance_amount         = 0;
                $employee->paid_amount         = $request->paid_in_advance ??0;
                $employee->remaining         = $request->total_remaining;
                $employee->description  = $description;
                $employee->date  = $date;
                $employee->save();

                if ($request->current_paid > 0) {
                    $shopLedgerBalance = ShopLedger::select('balance')->orderBy('id', 'desc')->first();
                    if (!$shopLedgerBalance) {
                        $lastShopLedgerBalance = 0;
                    } else {
                        $lastShopLedgerBalance = $shopLedgerBalance->balance;
                    }
                    $date = date('Y-m-d');
                    $description = 'Paid Employee Salary for the month of: '.$months;
                    $debit = 0;
                    $credit = $request->current_paid; 
                    $shopBalance = $debit - $credit + $lastShopLedgerBalance;
                    $shopLedger = new ShopLedger();
                    $shopLedger->employee_id= $request->employee_id;
                    $shopLedger->credit= $credit;
                    $shopLedger->debit= $debit;
                    $shopLedger->balance= $shopBalance;
                    $shopLedger->date= $date;
                    $shopLedger->description= $description;
                    $shopLedger->save();
                
                }

            });
            return redirect('employees-list')->with(['status' => 'success', 'message' => 'Payroll Generated successfully']);
        } catch (Exception $e) {
            return redirect('employees-list')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }
    public function getPayroll(){
        $payrolls= Payroll::with('employee')->orderBy('id', 'desc')->get();
        return view('employees.payrollList',compact('payrolls'));
    }

    public function viewPayroll($id){
        $payroll = Payroll::with('employee')->where('id', $id)->first();
        // Convert your view into HTML
        $html = view('employees.employeePayrollViewPdf', [
            'payroll' => $payroll
        ])->render();
        // Create an instance of Mpdf
        $mpdf = new Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A4', 
            'autoScriptToLang' => true, 
            'autoLangToFont' => true
        ]);
        // Write HTML to the PDF
        $mpdf->WriteHTML($html);
        // Output the generated PDF to browser
        return response($mpdf->Output('payroll-' . $payroll->id . '-Date' . $payroll->date . '.pdf', 'I'))
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
}
