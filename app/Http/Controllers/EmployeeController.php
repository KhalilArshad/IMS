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
        return view('employees.index',compact('getEmployees'));
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
            $employee->save();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Employee stored successfully']);
        } catch (Exception $e) {
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

    public function AddEmployeeAdvance(){
        $employeesAdvance= EmployeeAdvance::with('employee')->get();
        $employees= Employee::get();
        return view('employees.addEmployeeAdvance',compact('employeesAdvance','employees'));
    }
    public function saveEmployeeAdvance(Request $request){

                $employee= Employee::where('id',$request->employee_id)->select('id','advance')->first();
                $employeeAdvance =$employee->advance;
                $updateAdvance =  $employeeAdvance + $request->advance_amount;
                Employee::where('id',$request->employee_id)
               ->update(['advance'=>$updateAdvance]);

                $employee = new EmployeeAdvance();
                $employee->employee_id   = $request->employee_id;
                $employee->advance_amount         = $request->advance_amount;
                $employee->paid_amount         = 0;
                $employee->description  = $request->description;
                $employee->date  = date('Y-m-d');
                $employee->save();
        return response()->json(['success' => 'employee saved successfully']);
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
        $date = date('Y-m-d');
        list($year, $month, $day) = explode('-', $date); 
        $monthName = date("F", mktime(0, 0, 0, $month, 1)).' '.$year;
        $checkCurrentMonthPayroll = Payroll::where('employee_id', $request->id)
            ->whereYear('date', $year) 
            ->whereMonth('date', $month) 
            ->first();
            if($checkCurrentMonthPayroll){
                return redirect()->back()->with(['status' => 'danger', 'message' => 'Employee Payroll for this month already generated']);
            }else{
                
                $employee= Employee::where('id',$request->id)->first();
                return view('employees.generatePayroll',compact('employee','monthName'));
            }
       
    }

    public function savePayroll(Request $request){
       
        $rules = array(
            'employee_id' => 'required',
            'current_salary' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('employees-list')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        // return $request;
        try {
            DB::transaction(function () use ($request) {
                $employee= Employee::where('id',$request->employee_id)->select('id','advance')->first();
                $employeeAdvance =$employee->advance;
                $updateAdvance =  $employeeAdvance - $request->paid_in_advance;
                Employee::where('id',$request->employee_id)
               ->update(['advance'=>$updateAdvance]);
              
                $date = date('Y-m-d');
                list($year, $month, $day) = explode('-', $date); 
                $monthName = date("F", mktime(0, 0, 0, $month, 1)).' '.$year;
                $description = 'Payroll Generated for the month '.$monthName;
                $payroll = new Payroll();
                $payroll->employee_id= $request->employee_id;
                $payroll->date= $date;
                $payroll->salary= $request->salary;
                $payroll->advance= $request->advance;
                $payroll->paid_in_advance= $request->paid_in_advance;
                $payroll->overtime=  $request->overtime;
                $payroll->total_salary_to_be_paid= $request->current_salary;
                $payroll->description= $description;
                $payroll->save();

                

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
}
