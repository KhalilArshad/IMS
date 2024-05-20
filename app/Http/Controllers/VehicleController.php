<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDriver()
    {
        $drivers= Driver::get();
        $customers= Customer::get();
        return view('driver.list',compact('drivers','customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveDriver(Request $request)
    {
        if(!empty($request->update_driver_id)){
            Driver::where('id',$request->update_driver_id)
            ->update(['name'=>$request->name,'phone_no'=>$request->phone_no,'email'=>$request->email]);
        }else{

            $driver = new Driver();
            $driver->name   = $request->name;
            $driver->phone_no         = $request->phone_no;
            $driver->email            = $request->email;
            $driver->save();
            $driver_id=$driver->id;
        }
        return response()->json(['success' => 'Driver saved successfully']);
    }

    public function addVehicle()
    {
        $drivers= Driver::get();
        $vehicles= Vehicle::with('driver')->get();
        return view('vehicle.list',compact('drivers','vehicles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVehicle(Request $request)
    {
        $vehicle = new Vehicle();
        $vehicle->name   = $request->name;
        $vehicle->registration_no         = $request->reg_no;
        $vehicle->modal            = $request->modal;
        $vehicle->driver_id            = $request->driver_id;
        $vehicle->save();
        return response()->json(['success' => 'Vehicle saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addVehicleExpense()
    {
        $vehicleExpenses= VehicleExpense::with('vehicle')->orderBy('id', 'desc')->get();
        $vehicles= Vehicle::with('driver')->get();
        return view('vehicle.vehicleExpense',compact('vehicleExpenses','vehicles'));
    }
    
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVehicleExpense(Request $request)
    {
        if(!empty($request->update_vehicleExpense_id)){
            VehicleExpense::where('id',$request->update_vehicleExpense_id)
            ->update(['vehicle_id'=>$request->vehicle_id,'date'=>$request->date,'expense_type'=>$request->expense_type, 'amount'=>$request->amount,'description'=>$request->description]);
        }else{

            $vehicleExpense = new VehicleExpense();
            $vehicleExpense->vehicle_id   = $request->vehicle_id;
            $vehicleExpense->date         = $request->date;
            $vehicleExpense->expense_type = $request->expense_type;
            $vehicleExpense->amount       = $request->amount;
            $vehicleExpense->description  = $request->description;
            $vehicleExpense->save();
        }
        return response()->json(['success' => 'vehicle Expense saved successfully']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDriverData(Request $request){
        $id=$request->id;
        $driver=Driver::select('id','name','phone_no','email')->where('id',$id)->first();
        return $driver;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getVehicleData(Request $request){
        $id=$request->id;
        $vehicle=Vehicle::select('id','name','registration_no','modal','driver_id')->where('id',$id)->first();
        return $vehicle;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Driver::find($id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Driver deleted successfully.']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vehicleDelete($id)
    {
        Vehicle::find($id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Vehicle deleted successfully.']);
    }
    public function vehicleExpenseDelete($id)
    {
        VehicleExpense::find($id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Vehicle Expense deleted successfully.']);
    }

    
    public function getVehicleExpenseData(Request $request){
        $id=$request->id;
        $vehicleExpense=VehicleExpense::select('id','vehicle_id','date','expense_type','amount','description')->where('id',$id)->first();
        return $vehicleExpense;
    }
}
