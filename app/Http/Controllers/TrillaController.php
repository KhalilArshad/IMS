<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trilla;
use App\Models\TrillaRecord;
use App\Models\TrillaRecordChild;
use Illuminate\Http\Request;

class TrillaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers= Driver::where('type','=','trilla')->get();
        return view('driver.trillaDriverlist',compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
            $driver->type             = 'trilla';
            $driver->save();
            $driver_id=$driver->id;
        }
        return response()->json(['success' => 'Driver saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function TrillaList()
    {
            $trillas= Trilla::with('driver')->get();
            $drivers= Driver::where('type','=','trilla')->get();
            return view('trilla.trillaList',compact('trillas','drivers'));
    }
    public function saveTrilla(Request $request)
    {
        if(!empty($request->update_trilla_id)){
            Trilla::where('id',$request->update_trilla_id)
            ->update(['name'=>$request->name,'driver_id'=>$request->driver_id,'reg_no'=>$request->reg_no,'modal'=>$request->modal]);
        }else{

            $trilla = new Trilla();
            $trilla->name   = $request->name;
            $trilla->driver_id         = $request->driver_id;
            $trilla->reg_no            = $request->reg_no;
            $trilla->modal             = $request->modal;
            $trilla->save();
            $trilla_id=$trilla->id;
        }
        return response()->json(['success' => 'Trilla saved successfully']);
    }

    public function getTrillaData(Request $request){
        $id=$request->id;
        $trilla=Trilla::select('id','name','driver_id','reg_no','modal')->where('id',$id)->first();
        return $trilla;
    }
    public function getDriverData(Request $request){
        $id=$request->id;
        $driver=Driver::select('id','name','phone_no','email')->where('id',$id)->first();
        return $driver;
    }
    public function trillaDelete($id)
    {
        try {
            Trilla::find($id)->delete();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Trilla deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Error deleting trilla: ' . $e->getMessage()]);
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function TrillaRecordList()
    {
        $trilla_records = TrillaRecord::with(['trilla.driver','trillaRecordChild'])->get();
        $trillas= Trilla::with('driver')->select('id','name','driver_id')->get();
        return view('trilla.trillaRecordList', compact('trillas', 'trilla_records'));
    }

    /**
     * TrillaRecordCreate the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function TrillaRecordCreate()
    {
            $trillas= Trilla::with('driver')->select('id','name','driver_id')->get();
            return view('trilla.trillaRecordCreate', compact('trillas'));
    }

    public function saveTrillaRecord(Request $request)
    {
        $vailldation = $request->validate([
            'trilla_id' => 'required|exists:trillas,id',
            'month' => 'required|string',
            'date.*' => 'required|date',
            // Add validation for other fields as needed
        ]);

        $otherExpenseItems = $this->prepareOtherExpenses($request);
        $otherExpenseTotal = collect($otherExpenseItems)->sum(function ($item) {
            return (float) ($item['amount'] ?? 0);
        });

        $trilla_record = new TrillaRecord();
        $trilla_record->trilla_id   = $request->trilla_id;
        $trilla_record->month         = $request->month;
        $trilla_record->driver_salary = $request->driver_salary;
        $trilla_record->total_expenses   = $request->total_expenses;
        $trilla_record->profit = $request->profit;
        $trilla_record->other_expense = json_encode($otherExpenseItems);
        $trilla_record->net_profit   = ((float) $request->profit) - $otherExpenseTotal;
        $trilla_record->save();

        // Save child records
        foreach ($request->date as $key => $date) {

            $child = new TrillaRecordChild();

            $child->trilla_record_id = $trilla_record->id;
            $child->date             = $date;
            $child->from_location    = $request->from_location[$key] ?? null;
            $child->to_location      = $request->to_location[$key] ?? null;
            $child->trip_amount      = $request->trip_amount[$key] ?? 0;
            $child->diesel           = $request->diesel[$key] ?? 0;
            $child->d_trip           = $request->d_trip[$key] ?? 0;
            $child->maintenance      = $request->maintenance[$key] ?? 0;
            $child->cash_received    = $request->cash_received[$key] ?? 0;
            $child->bill_no          = $request->bill_no[$key] ?? null;

            $child->save();
        }
        return redirect()->route('trilla-record-list')->with('success', 'Trilla record created successfully.');
    }

    public function edit(Request $request)
    {
        $record = TrillaRecord::with('trillaRecordChild')->findOrFail($request->id);
        $trillas = Trilla::with('driver')->get();

        return view('trilla.trillaRecordEdit', compact('record','trillas'));
    }

    public function update(Request $request,$id)
    {
        $otherExpenseItems = $this->prepareOtherExpenses($request);
        $otherExpenseTotal = collect($otherExpenseItems)->sum(function ($item) {
            return (float) ($item['amount'] ?? 0);
        });

        $trilla_record = TrillaRecord::findOrFail($id);
        $trilla_record->trilla_id   = $request->trilla_id;
        $trilla_record->month         = $request->month;
        $trilla_record->driver_salary = $request->driver_salary;
        $trilla_record->total_expenses   = $request->total_expenses;
        $trilla_record->profit = $request->profit;
        $trilla_record->other_expense = json_encode($otherExpenseItems);
        $trilla_record->net_profit   = ((float) $request->profit) - $otherExpenseTotal;
        $trilla_record->save();

        // delete old trips
        TrillaRecordChild::where('trilla_record_id',$trilla_record->id)->delete();

        // insert new trips
        foreach($request->date as $key => $date){

            if($date){

                TrillaRecordChild::create([
                    'trilla_record_id' => $trilla_record->id,
                    'date' => $date,
                    'from_location' => $request->from_location[$key],
                    'to_location' => $request->to_location[$key],
                    'trip_amount' => $request->trip_amount[$key],
                    'diesel' => $request->diesel[$key],
                    'd_trip' => $request->d_trip[$key],
                    'maintenance' => $request->maintenance[$key],
                    'cash_received' => $request->cash_received[$key],
                    'bill_no' => $request->bill_no[$key],
                ]);

            }

        }

        return redirect()->route('trilla-record-list')
            ->with(['status'=>'success','message'=>'Record Updated Successfully']);
    }

    private function prepareOtherExpenses(Request $request): array
    {
        $items = [];
        $amounts = $request->input('other_expense_amount', []);
        $explanations = $request->input('other_expense_explanation', []);

        if (is_array($amounts) || is_array($explanations)) {
            $maxCount = max(count((array) $amounts), count((array) $explanations));

            for ($i = 0; $i < $maxCount; $i++) {
                $rawAmount = $amounts[$i] ?? null;
                $explanation = trim((string) ($explanations[$i] ?? ''));

                $hasAmount = $rawAmount !== null && $rawAmount !== '';
                $hasExplanation = $explanation !== '';

                if (!$hasAmount && !$hasExplanation) {
                    continue;
                }

                $items[] = [
                    'amount' => (float) ($rawAmount ?: 0),
                    'explanation' => $explanation,
                ];
            }
        }

        if (empty($items) && $request->filled('other_expense')) {
            $legacyOtherExpense = $request->input('other_expense');
            $decoded = json_decode($legacyOtherExpense, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            if (is_numeric($legacyOtherExpense)) {
                $items[] = [
                    'amount' => (float) $legacyOtherExpense,
                    'explanation' => '',
                ];
            }
        }

        return $items;
    }

    public function print($id)
    {
        $record = TrillaRecord::with(['trilla.driver','trillaRecordChild'])
                    ->findOrFail($id);

        return view('trilla.print', compact('record'));
    }
}
