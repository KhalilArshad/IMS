<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ShopLedger;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers= Supplier::get();
        return view('supplier.list',compact('suppliers'));
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
    public function store(Request $request)
    {
        if(!empty($request->update_supplier_id)){
        Supplier::where('id',$request->update_supplier_id)
        ->update(['name'=>$request->name,'phone_no'=>$request->phone_no,'email'=>$request->email]);
        }else{
        $supplier = new Supplier();
        $supplier->name   = $request->name;
        $supplier->phone_no         = $request->phone_no;
        $supplier->email            = $request->email;
        $supplier->previous_balance            = $request->opening_balance??0;
        $supplier->save();
        $supplier_id=$supplier->id;
        }
        return response()->json(['success' => 'Supplier saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            Supplier::find($id)->delete();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Supplier deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Error deleting driver: ' . $e->getMessage()]);
        }
    }
    

    public function supplierLedger(){
        $suppliers= Supplier::select('id','name')->get();
        $supplier_id='';
        return view('supplier.supplierLedger',compact('suppliers','supplier_id'));
    }
    public function getSupplierLedger(Request $request){
     
        if (isset($request->from) && isset($request->to)) {
            $from = $request->get('from');
            $to = $request->get('to');
        }
        else{
            $from =SupplierLedger::select('date')->orderby('id','ASC')->first();
            $to =SupplierLedger::select('date')->orderby('id','DESC')->first();
            if ($from) {
                $from = $from->date;
                $to = $to->date;
            }
            else{
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
        }
        // return $to;
        $suppliers= Supplier::select('id','name')->get();
        $supplier_id = $request->get("supplier_id");
        $supplier= Supplier::select('name','previous_balance')->where('id',$supplier_id)->first();
        $supplier_name = $supplier->name;
        $balance = $supplier->previous_balance;
        
        $supplierLedgerArray=SupplierLedger::with('supplier','purchaseOrder')->where('supplier_id',$supplier_id)
        ->whereBetween('date', [$from, $to])
        ->get(); 
        $total_remaining=SupplierLedger::where('supplier_id',$supplier_id)
        ->whereBetween('date', [$from, $to])->orderBy('id','DESC')->select('id','remaining')->first();
        $remaining= $total_remaining->remaining??0;
        $flag="found";
        return view('supplier.supplierLedger',compact('suppliers','supplier_id','supplierLedgerArray','from','to','flag','remaining'));
     
    }
    public function getSupplierData(Request $request){
        $id=$request->id;
         $supplier=Supplier::select('id','name','phone_no','email')->where('id',$id)->first();
         return $supplier;
    }
    

        /**
     * supplierPayAbleCreate the specified resource .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function supplierPayAbleCreate(Request $request)
    {
        $supplier= Supplier::where('id',$request->id)->select('id','name','previous_balance')->first();
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('supplier.supplierPayable',compact('supplier','system_date'));
    }

    public function SaveSupplierPayable(Request $request){
        $rules = array(
            'supplier_id' => 'required',
            'date' => 'required',
            'paid_amount' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('supplier-payable')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            DB::transaction(function () use ($request) {
         // selecting previous_balance of supplier
         $supplierBalance = DB::select("SELECT `previous_balance` FROM `suppliers` WHERE `id`=?", [$request->supplier_id]);
         $supplierPreviousBalance = $supplierBalance[0]->previous_balance;
         $updatedSupplierBalance = $supplierPreviousBalance - $request->paid_amount;
         // updating previous_balance of supplier
         DB::table("suppliers")->where("id", '=', $request->supplier_id)->update(['previous_balance' => $updatedSupplierBalance]);
     
         $date = $request->date;
         $description = 'Payment To Supplier';
         $supplierLedger = new SupplierLedger();
         $supplierLedger->supplier_id= $request->supplier_id;
         $supplierLedger->previous_balance= $supplierPreviousBalance;
         $supplierLedger->total_bill= 0;
         $supplierLedger->payment=  $request->paid_amount;
         $supplierLedger->remaining= $updatedSupplierBalance;
         $supplierLedger->date= $date;
         $supplierLedger->description= $description;
         $supplierLedger->save();
         $voucher = new Voucher();
         $voucher->supplier_id= $request->supplier_id;
         $voucher->previous_balance= $supplierPreviousBalance;
         $voucher->type= 'Payment';
         $voucher->paid_amount=  $request->paid_amount;
         $voucher->remaining= $updatedSupplierBalance;
         $voucher->date= $date;
         $voucher->save();

             if ($request->paid_amount > 0) {
                 $shopLedgerBalance = ShopLedger::select('balance')->orderBy('id', 'desc')->first();
                 if (!$shopLedgerBalance) {
                     $lastShopLedgerBalance = 0;
                 } else {
                     $lastShopLedgerBalance = $shopLedgerBalance->balance;
                 }
                 $description = 'Paid against supplier';
                 $debit = 0;
                 $credit = $request->paid_amount;
                 $shopBalance = $debit - $credit + $lastShopLedgerBalance;
                 $shopLedger = new ShopLedger();
                 $shopLedger->supplier_id= $request->supplier_id;
                 $shopLedger->credit= $credit;
                 $shopLedger->debit= $debit;
                 $shopLedger->balance= $shopBalance;
                 $shopLedger->date= $date;
                 $shopLedger->description= $description;
                 $shopLedger->save();
             
             }
            });
            return redirect('supplier-list')->with(['status' => 'success', 'message' => 'Payment to Supplier successfully']);
        } catch (Exception $e) {
            return redirect('supplier-payable')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function paymentVoucher(Request $request){

        $oldDateFrom = $request->input('date_from', '');
        $oldSupplierId = $request->input('supplier_id', '');
    
        $query = Voucher::with('supplier')->where('type', '=', 'Payment')->orderBy('id', 'desc');
    
        if ($request->has('supplier_id') && !empty($request->input('supplier_id'))) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }
    
        if ($request->has('date_from') && !empty($request->input('date_from'))) {
            $query->whereDate('date', '=', $request->input('date_from'));
        }
        $paymentVouchers = $query->get();
        $suppliers = Supplier::all();
     
        return view('supplier.VoucherList',compact('paymentVouchers','suppliers','oldSupplierId','oldDateFrom'));
    }

    public function viewVoucher($id)
    {   
        $voucher = Voucher::with('supplier')->where('id', $id)->first();
        // Convert your view into HTML
        $html = view('supplier.voucherViewPdf', [
            'voucher' => $voucher, 
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
        return response($mpdf->Output('voucher-' . $voucher->id . '-Date' . $voucher->date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
}
