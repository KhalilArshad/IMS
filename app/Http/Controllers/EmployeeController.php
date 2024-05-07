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
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::sortable()->with('referedBy')->where('role_id',2)->paginate(10);
        return view('admin.users_listing',['users'=>$users]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $user = new User();
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone_no = $request->phone_no;

            $user->company = $request->company;

            $user->role_id = 2;

            $user->save();
            return redirect()->back()->with(['status'=>'success','message'=>'Your account has been created successfully']);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req)
    {
        if (Session::get('role_id') == 1) {
            $user_id = $req->user_id;
        }else{
            $user_id = Session::get('user_id');
        }
        $user = User::find($user_id);
        return view('user.user-profile',['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'name' => 'required|max:255',
            'phone_no' => 'required|min:5|max:15',
            'user_id' => 'required|int'

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status'=>'danger','message'=>$validator->errors()->first()]);
        }
        try{
            if (Session::get('role_id') == 1) {
                $user_id = $request->user_id;
            }else{
                $user_id = Session::get('user_id');
            }
            $user = User::find($user_id);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->phone_no = $request->phone_no;
            $user->save();
            return back()->with(['status'=>'success','message'=>'Profile Info updated successfully']);
        }catch(Exception $e){
            return back()->withInput()->with(['status'=>'danger','message'=>$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        if (Session::get('role_id') == 1) {
            $user_id = $req->user_id;
        }else{
            $user_id = Session::get('user_id');
        }
        $user = User::find($user_id);
        $user->delete();

        return back()->with(['status'=>'success','message'=>'User Deleted successfully']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsersAjax(Request $req)
    {

        if ($req->level == 1) {
            $user = User::find($req->user_id);
            $name = $user->name;
            $levelsHeadingArray = array('name1'=>$name);
        }elseif ($req->level == 2) {
            $user = User::with('referedBy')->find($req->user_id);
            $name = $user->name;
            $levelsHeadingArray = array('name1'=>$user->referedBy->name,'name2'=>$name);
        }
        elseif ($req->level == 3) {
            $user = User::with('referedBy')->find($req->user_id);
            $name = $user->name;
            $parent = User::with('referedBy')->find($user->referedBy->id);
            $levelsHeadingArray = array('name1'=>$parent->referedBy->name, 'name2'=>$user->referedBy->name,'name3'=>$name);

        }
        $users = User::sortable()->with('referedBy')->where('reference_id',$req->user_id)->paginate(10);
        return view('admin.ajax.users_list_ajax',['users'=>$users,'level'=>$req->level,'levelsHeadingArray'=>$levelsHeadingArray,'referedBy'=>$req->user_id]);
    }

    /**
     * Displaying the listing of users called by ajax for pagination... For admin
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedUsersAjax(Request $req)
    {
        $referedBy = $req->referedBy;
        $users = User::sortable()->with('referedBy')
        ->when($referedBy, function($qu) use($referedBy){
            $qu->where('reference_id',$referedBy);
        })
        ->where('role_id',2)
        ->paginate(10);
        $level = $req->level;
        return view('admin.ajax.paginated_users_ajax',['users'=>$users,'level'=>$level]);
    }

    //--------------------------Functions for user------------------------------------

    /**
     * Display a listing of the resource for the user
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTeam()
    {
        $user_id = Session::get('user_id');
        $users = User::sortable()->with('referedBy')->where('reference_id',$user_id)->paginate(10);
        return view('user.users_listing',['users'=>$users]);
    }

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


        /**
     * Display a listing of the resource for the wizard .
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeamForWizard(Request $req)
    {
        // if (User::isAuthenticatedRequest($req->user_id, $req->level) == 0) {
        //     return 'Unauthenticated Request';
        // }
        if ($req->level == 2) {
            $user = User::find($req->user_id);
            $name = $user->name;
            $levelsHeadingArray = array('name1'=>$name);
        }elseif ($req->level == 3) {
            $user = User::with('referedBy')->find($req->user_id);
            $name = $user->name;
            $levelsHeadingArray = array('name1'=>$user->referedBy->name,'name2'=>$name);
        }
        $users = User::sortable()->with('referedBy')->where('reference_id',$req->user_id)->paginate(10);
        return view('user.ajax.team_for_Wizard',['users'=>$users,'level'=>$req->level,'levelsHeadingArray'=>$levelsHeadingArray,'referedBy'=>$req->user_id]);
    }

    public function getEmployees()
    {
        $createById = Session::get('user_id');
        if($createById == 1){
            $getEmployees = User::with('teamLead')->where('role_id',3)->orderBy('id','desc')->paginate(10);
        }else{
            $getEmployees = User::with('teamLead')->where('role_id',3)->orderBy('id','desc')->paginate(10);
        }
        return view('admin.employees.index',compact('getEmployees'));
    }

    public function addEmployee()
    {
        //  return 1;
        // $cities = City::get();
        return view('employees.create');
    }

    public function getTeamLeads()
    {
        $teamLeads = User::where('role_id',2)->orderBy('id','desc')->paginate(10);
        return view('admin.teamleads.index',compact('teamLeads'));
    }

    public function addTeamLead()
    {

        $cities = City::get();
        return view('admin.teamleads.create', compact('cities'));
    }

    public function saveTeamLead(Request $request)
    {

        // Validation rules
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:5|max:255',
            'phone_no' => 'required|unique:users|min:11|max:15',
            'cnic_no' => 'required|unique:users|min:13|max:15',
            'designation' => 'required',
            'date_of_joining' => 'required|date',
            'hourly_salary' => 'required|numeric',
            'city_id' => 'required',
            'address' => 'required',
        ];



        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }

        try {

            $createById = Session::get('user_id');

            $teamLead = new User();
            $teamLead->name = $request->name;
            $teamLead->email = $request->email;
            $teamLead->password = Hash::make($request->password);
            $teamLead->phone_no = $request->phone_no;
            $teamLead->cnic_no = $request->cnic_no;
            $teamLead->designation = $request->designation;
            $teamLead->date_of_joining = $request->date_of_joining;
            $teamLead->hourly_salary = $request->hourly_salary;
            $teamLead->city_id = $request->city_id;
            $teamLead->address = $request->address;
            $teamLead->updated_by = $createById;
            $teamLead->role_id = 2;
            $teamLead->save();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Team Lead stored successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function viewTeamLead(Request $request)
    {
        $teamLead = User::find($request->id);
        $cities = City::get();
        $attendances = Attendance::where('user_id',$request->id)->with('user')->orderBy('id','desc')->paginate(10);
        $totalPaidAmount = Attendance::where('user_id',$request->id)->where('payment_status','Paid')->sum('perday_salary');
        $totalUnPaidAmount = Attendance::where('user_id',$request->id)->where('payment_status','Unpaid')->sum('perday_salary');
        return view('admin.teamleads.view', compact('teamLead','cities','attendances','totalPaidAmount','totalUnPaidAmount'));
    }

    public function updateTeamLead(Request $request)
    {
        //return $request->all();

        // Validation rules
        $rules = [
            'id' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id . '|max:255',
            'phone_no' => 'required|unique:users,phone_no,' . $request->id . '|min:11|max:15',
            'cnic_no' => 'required|unique:users,cnic_no,' . $request->id . '|min:13|max:15',
            'designation' => 'required',
            'date_of_joining' => 'required|date',
            'hourly_salary' => 'required|numeric',
            'city_id' => 'required',
            'address' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }


        try {

            $teamLead = User::find($request->id);
            $createById = Session::get('user_id');

            $teamLead->name = $request->name;
            $teamLead->email = $request->email;
            if($request->password){
                $teamLead->password = Hash::make($request->password);
            }
            $teamLead->phone_no = $request->phone_no;
            $teamLead->cnic_no = $request->cnic_no;
            $teamLead->designation = $request->designation;
            $teamLead->date_of_joining = $request->date_of_joining;
            $teamLead->hourly_salary = $request->hourly_salary;
            $teamLead->city_id = $request->city_id;
            $teamLead->address = $request->address;
            $teamLead->updated_by = $createById;
            $teamLead->save();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Team Lead Updated successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }



    public function deleteTeamLead(Request $request)
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

            $teamLead = User::find($request->id);
            $teamLead->delete();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Team Lead deleted successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }


    public function teamLeadStatusChange(Request $request)
    {


        // Validation rules
        $rules = [
            'id' => 'required',
            'is_banned' => 'required',
            'reference_note' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }


        try {

            $teamLead = User::find($request->id);
            $teamLead->is_banned = $request->is_banned;
            $teamLead->reference_note = $request->reference_note;

            $teamLead->save();

            return back()->with(['status' => 'success', 'message' => 'Team Lead  status changed successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function saveEmployee(Request $request)
    {

        // Validation rules
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:5|max:255',
            'phone_no' => 'required|unique:users|min:11|max:15',
            'cnic_no' => 'required|unique:users|min:13|max:15',
            'designation' => 'required',
            'date_of_joining' => 'required|date',
            'hourly_salary' => 'required|numeric',
            'city_id' => 'required',
            'address' => 'required',
        ];



        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }

        try {

            $createById = Session::get('user_id');

            $employee = new User();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->password = Hash::make($request->password);
            $employee->phone_no = $request->phone_no;
            $employee->cnic_no = $request->cnic_no;
            $employee->designation = $request->designation;
            $employee->date_of_joining = $request->date_of_joining;
            $employee->hourly_salary = $request->hourly_salary;
            $employee->city_id = $request->city_id;
            $employee->address = $request->address;
            $employee->updated_by = $createById;
            $employee->role_id = 3;
            $employee->team_lead_id = $createById ;
            $employee->save();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Employee stored successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function viewEmployees(Request $request)
    {
        $employee = User::with('teamLead')->find($request->id);
        $cities = City::get();
        $attendances = Attendance::where('user_id',$request->id)->with('user')->orderBy('id','desc')->paginate(10);
        $totalPaidAmount = Attendance::where('user_id',$request->id)->where('payment_status','Paid')->sum('perday_salary');
        $totalUnPaidAmount = Attendance::where('user_id',$request->id)->where('payment_status','Unpaid')->sum('perday_salary');
        $totalFoodAmount = Payroll::where('user_id',$request->id)->sum('food_amount');
        $totalAdvanceAmount = Payroll::where('user_id',$request->id)->sum('advance_amount');
        $totalWorkingDays = Attendance::count();

        return view('admin.employees.view', compact('employee','cities','attendances','totalPaidAmount','totalUnPaidAmount',
         'totalFoodAmount','totalAdvanceAmount','totalWorkingDays'
    ));
    }


    public function employeeStatusChange(Request $request)
    {


        // Validation rules
        $rules = [
            'id' => 'required',
            'is_banned' => 'required',
            'reference_note' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }


        try {

            $teamLead = User::find($request->id);
            $teamLead->is_banned = $request->is_banned;
            $teamLead->reference_note = $request->reference_note;

            $teamLead->save();

            return back()->with(['status' => 'success', 'message' => 'Employee status changed successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function updateEmployee(Request $request)
    {


        // Validation rules
        $rules = [
            'id' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id . '|max:255',
            'phone_no' => 'required|unique:users,phone_no,' . $request->id . '|min:11|max:15',
            'cnic_no' => 'required|unique:users,cnic_no,' . $request->id . '|min:13|max:15',
            'date_of_joining' => 'required|date',
            'hourly_salary' => 'required|numeric',
            'city_id' => 'required',
            'designation' => 'required',
            'address' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation failure
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }


        try {

            $employee = User::find($request->id);
            $createById = Session::get('user_id');

            $employee->name = $request->name;
            $employee->email = $request->email;
            if($request->password){
                $employee->password = Hash::make($request->password);
            }
            $employee->phone_no = $request->phone_no;
            $employee->cnic_no = $request->cnic_no;
            $employee->designation = $request->designation;
            $employee->date_of_joining = $request->date_of_joining;
            $employee->hourly_salary = $request->hourly_salary;
            $employee->city_id = $request->city_id;
            $employee->address = $request->address;
            $employee->updated_by = $createById;
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

            $employee = User::find($request->id);
            $employee->delete();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Employee deleted successfully']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }



    public function searchTeamleadByajax(Request $req)
    {
        $name = $req->name;
        $email = $req->email;
        $phone_no = $req->phone_no;
        $cnic_no = $req->cnic_no;

        $is_banned = $req->is_banned;
        $records = $req->records;

        $teamLeads = User::where('role_id',2)
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
        return view('admin.teamleads.ajax.ajax-index', compact('teamLeads'));
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
}
