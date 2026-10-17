<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCustomer;
use App\Models\Setting;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use App\Models\VehicleInstallment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function saveAssignCustomerToDriver(Request $request){
        DB::transaction(function () use ($request) {
                $rows = count($request->customer_ids);
                for ($i = 0; $i < $rows; $i++) {
                    $exists = DriverCustomer::where('driver_id', $request->driver_id)
                    ->where('customer_id', $request->customer_ids[$i])
                    ->exists();
                    if (!$exists) {
                        $driver =new DriverCustomer();
                        $driver->driver_id= $request->driver_id;
                        $driver->customer_id= $request->customer_ids[$i];
                        $driver->save();
                    }
                }
        });
        return redirect('add-driver')->with(['status' => 'success', 'message' => 'Customers Assign To Driver Successfully']);
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
        if(!empty($request->update_vehicle_id)){
            Vehicle::where('id',$request->update_vehicle_id)
            ->update(['name'=>$request->name,
            'registration_no'=>$request->reg_no,
            'driver_id'=>$request->driver_id,
            'price'=>$request->price,
            'modal'=>$request->modal]);
        }else{

            $vehicle = new Vehicle();
            $vehicle->name   = $request->name;
            $vehicle->registration_no         = $request->reg_no;
            $vehicle->modal            = $request->modal;
            $vehicle->driver_id            = $request->driver_id;
            $vehicle->price            = $request->price;
            $vehicle->remaining            = $request->price;
            $vehicle->save();
        }
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
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('vehicle.vehicleExpense',compact('vehicleExpenses','vehicles','system_date'));
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addVehicleInstallment(Request $request)
    {
        $vehicleInstallments= VehicleInstallment::with('vehicle')
        ->where(function ($query) use ($request) {
            if ($request->has('vehicle_id') && !empty($request->vehicle_id)) {
                $query->where('vehicle_id', $request->vehicle_id);
            }
        })
        ->orderBy('id', 'desc')->get();
        $vehicles= Vehicle::get();
        $oldVehicleId=$request ->vehicle_id;
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('vehicle.vehicleInstallment',compact('vehicles','vehicleInstallments','oldVehicleId','system_date'));
    }
    public function getRemaining(Request $request)
    {
        // Assuming you have a Vehicle model with a remaining field
        $id=$request->id;
        $vehicleRemaining=Vehicle::select('id','remaining')->where('id',$id)->first();
        return $vehicleRemaining;
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

    public function getDriverCustomers(Request $request){
        $id=$request->id;
        $driver=Driver::where('id',$id)->select('id','name')->first();
        $driverName = $driver->name;
        $customers=DriverCustomer::with('customer')->where('driver_id',$id)->get();
        return response()->json(['customers' => $customers,'driverName'=>$driverName]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getVehicleData(Request $request){
        $id=$request->id;
        $vehicle=Vehicle::select('id','name','registration_no','modal','driver_id','price')->where('id',$id)->first();
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
        try {
            Driver::find($id)->delete();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Driver deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Error deleting driver: ' . $e->getMessage()]);
        }
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
          /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVehicleInstallment(Request $request)
    {
        // if(!empty($request->update_vehicleExpense_id)){
        //     VehicleExpense::where('id',$request->update_vehicleExpense_id)
        //     ->update(['vehicle_id'=>$request->vehicle_id,'date'=>$request->date,'expense_type'=>$request->expense_type, 'amount'=>$request->amount,'description'=>$request->description]);
        // }else{
            $installmentAmount =$request->amount;
            $totalRemaining= $request->remaining - $installmentAmount;
           Vehicle::where('id',$request->vehicle_id)
            ->update(['remaining'=>$totalRemaining]);
            $vehicleInstallment = new VehicleInstallment();
            $vehicleInstallment->vehicle_id   = $request->vehicle_id;
            $vehicleInstallment->date         = $request->date;
            $vehicleInstallment->remaining = $totalRemaining;
            $vehicleInstallment->amount       = $request->amount;
            $vehicleInstallment->description  = $request->description;
            $vehicleInstallment->save();
        // }
        return response()->json(['success' => 'vehicle Installment saved successfully']);
    }
}
