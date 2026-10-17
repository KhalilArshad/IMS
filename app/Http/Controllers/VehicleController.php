<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverCustomer;
use App\Models\Setting;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use App\Models\VehicleInstallment;
use App\Models\VehiclePayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $drivers = Driver::get();
        $customers = Customer::get();
        return view('driver.list', compact('drivers', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveDriver(Request $request)
    {
        if (!empty($request->update_driver_id)) {
            Driver::where('id', $request->update_driver_id)
                ->update(['name' => $request->name, 'phone_no' => $request->phone_no, 'email' => $request->email]);
        } else {

            $driver = new Driver();
            $driver->name   = $request->name;
            $driver->phone_no         = $request->phone_no;
            $driver->email            = $request->email;
            $driver->save();
            $driver_id = $driver->id;
        }
        return response()->json(['success' => 'Driver saved successfully']);
    }

    public function saveAssignCustomerToDriver(Request $request)
    {
        DB::transaction(function () use ($request) {
            $rows = count($request->customer_ids);
            for ($i = 0; $i < $rows; $i++) {
                $exists = DriverCustomer::where('driver_id', $request->driver_id)
                    ->where('customer_id', $request->customer_ids[$i])
                    ->exists();
                if (!$exists) {
                    $driver = new DriverCustomer();
                    $driver->driver_id = $request->driver_id;
                    $driver->customer_id = $request->customer_ids[$i];
                    $driver->save();
                }
            }
        });
        return redirect('add-driver')->with(['status' => 'success', 'message' => 'Customers Assign To Driver Successfully']);
    }
    public function addVehicle()
    {
        $drivers = Driver::get();
        $vehicles = Vehicle::with('driver')->get();
        return view('vehicle.list', compact('drivers', 'vehicles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVehicle(Request $request)
    {
        if (!empty($request->update_vehicle_id)) {
            Vehicle::where('id', $request->update_vehicle_id)
                ->update([
                    'name' => $request->name,
                    'registration_no' => $request->reg_no,
                    'driver_id' => $request->driver_id,
                    'price' => $request->price,
                    'modal' => $request->modal
                ]);
        } else {

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
        $vehicleExpenses = VehicleExpense::with('vehicle')->orderBy('id', 'desc')->get();
        $vehicles = Vehicle::with('driver')->get();
        $system_date = Setting::select('id', 'system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('vehicle.vehicleExpense', compact('vehicleExpenses', 'vehicles', 'system_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVehicleExpense(Request $request)
    {
        if (!empty($request->update_vehicleExpense_id)) {
            VehicleExpense::where('id', $request->update_vehicleExpense_id)
                ->update(['vehicle_id' => $request->vehicle_id, 'date' => $request->date, 'expense_type' => $request->expense_type, 'amount' => $request->amount, 'description' => $request->description]);
        } else {

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
        $vehicleInstallments = VehicleInstallment::with('vehicle')
            ->where(function ($query) use ($request) {
                if ($request->has('vehicle_id') && !empty($request->vehicle_id)) {
                    $query->where('vehicle_id', $request->vehicle_id);
                }
            })
            ->orderBy('id', 'desc')->get();
        $vehicles = Vehicle::get();
        $oldVehicleId = $request->vehicle_id;
        $system_date = Setting::select('id', 'system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('vehicle.vehicleInstallment', compact('vehicles', 'vehicleInstallments', 'oldVehicleId', 'system_date'));
    }
    public function generatePayroll(Request $request)
    {
        $vehicle_id = $request->id;

        $vehicle = Vehicle::find($vehicle_id);

        if (!$vehicle) {
            return redirect()->back()->with([
                'status' => 'danger',
                'message' => 'Vehicle not found.'
            ]);
        }

        return view('vehicle.vehiclePayroll', compact('vehicle'));
    }
    public function createVehiclePayroll(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->id);
        $systemDate = Setting::select('system_date')->first();
        $defaultYear = $systemDate && $systemDate->system_date
            ? Carbon::parse($systemDate->system_date)->year
            : now()->year;

        $selectedYear = (int) ($request->year ?: $defaultYear);

        $payrollRows = VehiclePayroll::where('vehicle_id', $vehicle->id)
            ->whereYear('date', $selectedYear)
            ->orderBy('date')
            ->get()
            ->map(function ($payroll) {
                return [
                    'id' => $payroll->id,
                    'month' => $payroll->month,
                    'salary' => (float) $payroll->amount,
                    'advance' => (float) $payroll->advance,
                    'old_remaining' => (float) ($payroll->add_in_remaining ?? 0),
                    'paid_in_advance' => (float) $payroll->paid_in_advance,
                    'overtime' => (float) $payroll->overtime,
                    'current_paid' => (float) $payroll->current_paid,
                    'remaining' => (float) $payroll->remaining,
                ];
            });

        // GUESSED columns on vehicles: amount, advance, remaining. Rename to your real ones.
        if ($payrollRows->isEmpty()) {
            $payrollRows = collect([[
                'id' => null,
                'month' => '',
                'salary' => (float) ($vehicle->amount ?? 0),
                'advance' => (float) ($vehicle->advance ?? 0),
                'old_remaining' => (float) ($vehicle->remaining ?? 0),
                'paid_in_advance' => 0,
                'overtime' => 0,
                'current_paid' => 0,
                'remaining' => (float) ($vehicle->remaining ?? 0),
            ]]);
        }

        $lastMonth = optional(
            VehiclePayroll::where('vehicle_id', $vehicle->id)->orderBy('date', 'desc')->first()
        )->month;

        $yearOptions = VehiclePayroll::where('vehicle_id', $vehicle->id)
            ->selectRaw('YEAR(date) as payroll_year')
            ->distinct()
            ->orderBy('payroll_year', 'desc')
            ->pluck('payroll_year')
            ->filter()
            ->map(fn($year) => (int) $year)
            ->values()
            ->all();

        $yearOptions[] = $defaultYear;
        $yearOptions[] = $defaultYear - 1;
        $yearOptions[] = $defaultYear + 1;
        $yearOptions = collect($yearOptions)->unique()->sortDesc()->values()->all();

        return view('vehicle.vehiclePayroll', compact('vehicle', 'lastMonth', 'selectedYear', 'payrollRows', 'yearOptions'));
    }

    public function storeVehiclePayroll(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'year' => 'required|integer',
            'rows' => 'required|array',
        ]);

        $vehicleId = $request->vehicle_id;
        $year = (int) $request->year;

        DB::transaction(function () use ($request, $vehicleId, $year) {
            $keptIds = [];

            foreach ($request->rows as $row) {
                if (empty($row['month'])) {
                    continue;
                }

                $amount = (float) ($row['salary'] ?? 0);
                $oldRemaining = (float) ($row['old_remaining'] ?? 0);
                $paidOld = (float) ($row['paid_in_advance'] ?? 0);
                $overtime = (float) ($row['overtime'] ?? 0);
                $currentPaid = (float) ($row['current_paid'] ?? 0);
                $remaining = max($oldRemaining + $amount + $overtime - $paidOld - $currentPaid, 0);

                $payroll = !empty($row['id'])
                    ? VehiclePayroll::where('vehicle_id', $vehicleId)->findOrFail($row['id'])
                    : new VehiclePayroll(['vehicle_id' => $vehicleId]);

                $payroll->fill([
                    'date' => Carbon::parse('1 ' . $row['month'] . ' ' . $year)->toDateString(),
                    'month' => $row['month'],
                    'amount' => $amount,
                    'advance' => (float) ($row['advance'] ?? 0),
                    'add_in_remaining' => $oldRemaining,
                    'paid_in_advance' => $paidOld,
                    'overtime' => $overtime,
                    'current_paid' => $currentPaid,
                    'remaining' => $remaining,
                ])->save();

                $keptIds[] = $payroll->id;
            }

            // rows removed on screen are deleted for that year
            VehiclePayroll::where('vehicle_id', $vehicleId)
                ->whereYear('date', $year)
                ->whereNotIn('id', $keptIds)
                ->delete();
        });

        return redirect(url('vehicle-payroll') . '?' . http_build_query(['id' => $vehicleId, 'year' => $year]))
            ->with('status', 'success')
            ->with('message', 'Vehicle payroll saved successfully');
    }
    public function getRemaining(Request $request)
    {
        // Assuming you have a Vehicle model with a remaining field
        $id = $request->id;
        $vehicleRemaining = Vehicle::select('id', 'remaining')->where('id', $id)->first();
        return $vehicleRemaining;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDriverData(Request $request)
    {
        $id = $request->id;
        $driver = Driver::select('id', 'name', 'phone_no', 'email')->where('id', $id)->first();
        return $driver;
    }

    public function getDriverCustomers(Request $request)
    {
        $id = $request->id;
        $driver = Driver::where('id', $id)->select('id', 'name')->first();
        $driverName = $driver->name;
        $customers = DriverCustomer::with('customer')->where('driver_id', $id)->get();
        return response()->json(['customers' => $customers, 'driverName' => $driverName]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getVehicleData(Request $request)
    {
        $id = $request->id;
        $vehicle = Vehicle::select('id', 'name', 'registration_no', 'modal', 'driver_id', 'price')->where('id', $id)->first();
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


    public function getVehicleExpenseData(Request $request)
    {
        $id = $request->id;
        $vehicleExpense = VehicleExpense::select('id', 'vehicle_id', 'date', 'expense_type', 'amount', 'description')->where('id', $id)->first();
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
        $installmentAmount = $request->amount;
        $totalRemaining = $request->remaining - $installmentAmount;
        Vehicle::where('id', $request->vehicle_id)
            ->update(['remaining' => $totalRemaining]);
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
