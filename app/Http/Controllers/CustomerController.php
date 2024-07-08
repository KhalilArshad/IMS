<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionSummary;
use App\Models\ShopLedger;
use App\Models\Voucher;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        return view('customer.list',compact('customers'));
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
        
        if(!empty($request->update_customer_id)){
            Customer::where('id',$request->update_customer_id)
            ->update(['name'=>$request->name,'phone_no'=>$request->phone_no,'email'=>$request->email,'previous_balance'=>$request->opening_balance]);

            $customer_check=CustomerTransactionSummary::where('customer_id',$request->update_customer_id)
             ->orderBy('id','DESC')->first();
            $customer_already_exist= $customer_check->customer_id;
            if(empty($customer_already_exist)){
                $customerTran = new CustomerTransactionSummary();
                $customerTran->customer_id        = $request->update_customer_id;
                $customerTran->today_bill         = 0;
                $customerTran->today_remaining    = 0;
                $customerTran->old_remaining      = $request->opening_balance;
                $customerTran->old_received       = 0;
                $customerTran->net_remaining      = $request->opening_balance;
                $customerTran->description        = 'Customer Opening Balance';
                $customerTran->date  = date('Y-m-d');
                $customerTran->save();
            }

            }else{
                $customer = new Customer();
                $customer->name   = $request->name;
                $customer->phone_no         = $request->phone_no;
                $customer->email            = $request->email;
                $customer->previous_balance  = $request->opening_balance;
                $customer->save();
                $customer_id =$customer->id;

                if(!empty($request->opening_balance)){
                    $customerTran = new CustomerTransactionSummary();
                    $customerTran->customer_id        = $customer_id;
                    $customerTran->today_bill         = 0;
                    $customerTran->today_remaining    = 0;
                    $customerTran->old_remaining      = $request->opening_balance;
                    $customerTran->old_received       = 0;
                    $customerTran->net_remaining      = $request->opening_balance;
                    $customerTran->description        = 'Customer Opening Balance';
                    $customerTran->date  = date('Y-m-d');
                    $customerTran->save();
                }
            }
        return response()->json(['success' => 'Customer saved successfully']);
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
            Customer::find($id)->delete();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Customer deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Error deleting driver: ' . $e->getMessage()]);
        }
    }

    
    public function customerLedger(){
        $customers= Customer::select('id','name')->get();
        $customer_id='';
        return view('customer.customersLedger',compact('customers','customer_id'));
    }
    public function getCustomerLedger(Request $request){
        if (isset($request->from) && isset($request->to)) {
            $from = $request->get('from');
            $to = $request->get('to');
        }
        else{
            $from =CustomerLedger::select('date')->orderby('id','ASC')->first();
            $to =CustomerLedger::select('date')->orderby('id','DESC')->first();
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
        $customers= Customer::select('id','name')->get();
        $customer_id = $request->get("customer_id");
        $customer= Customer::select('id','name','previous_balance')->where('id',$customer_id)->first();
        $customer_name = $customer->name;
        $balance = $customer->previous_balance;
        
        $customerLedgerArray=CustomerLedger::with('customer','invoice')->where('customer_id',$customer_id)
        ->whereBetween('date', [$from, $to])
        ->get(); 
        $total_remaining=CustomerLedger::where('customer_id',$customer_id)
        ->whereBetween('date', [$from, $to])->orderBy('id','DESC')->select('id','balance')->first();
        $remaining= $total_remaining->balance??0;
        $flag="found";
        return view('customer.customersLedger',compact('customers','customer_id','customerLedgerArray','from','to','flag','remaining'));
     
    }

    public function getCustomerData(Request $request){
        $id=$request->id;
        $customer=Customer::select('id','name','phone_no','email','previous_balance')->where('id',$id)->first();
        return $customer;
    }

    
          /**
     * customerReceivableCreate the specified resource .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function customerReceivableCreate(Request $request)
    {
        $customer= Customer::where('id',$request->id)->select('id','name','previous_balance')->first();
        
        return view('customer.customerReceivable',compact('customer'));
    }
    
    public function SaveCustomerReceivable(Request $request){
        $rules = array(
            'customer_id' => 'required',
            'date' => 'required',
            'paid_amount' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('customer-receivable')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            DB::transaction(function () use ($request) {
         // selecting previous_balance of supplier
         $customerBalance = DB::select("SELECT `previous_balance` FROM `customers` WHERE `id`=?", [$request->customer_id]);
         $customerPreviousBalance = $customerBalance[0]->previous_balance;
         $updatedCustomerBalance = $customerPreviousBalance - $request->paid_amount;
         // updating previous_balance of supplier
         DB::table("customers")->where("id", '=', $request->customer_id)->update(['previous_balance' => $updatedCustomerBalance]);
     
         $date = date('Y-m-d');
         $description = 'Payment Received Against Customer';
         $customerLedger = new CustomerLedger();
         $customerLedger->customer_id= $request->customer_id;
         $customerLedger->credit= $request->paid_amount;
         $customerLedger->debit= 0;
         $customerLedger->balance= $updatedCustomerBalance;
         $customerLedger->date= $date;
         $customerLedger->description= $description;
         $customerLedger->save();

         $voucher = new Voucher();
         $voucher->customer_id= $request->customer_id;
         $voucher->previous_balance= $customerPreviousBalance;
         $voucher->type= 'Receipt';
         $voucher->paid_amount=  $request->paid_amount;
         $voucher->remaining= $updatedCustomerBalance;
         $voucher->date= $date;
         $voucher->save();
            if($request->paid_amount > 0){
                $customerTran = new CustomerTransactionSummary();
                $customerTran->customer_id        = $request->customer_id;
                $customerTran->today_bill         = 0;
                $customerTran->today_remaining    = 0;
                $customerTran->old_remaining      = $customerPreviousBalance;
                $customerTran->old_received       = $request->paid_amount;
                $customerTran->net_remaining      = $updatedCustomerBalance;
                $customerTran->description        = $description;
                $customerTran->date  = $date;
                $customerTran->save();
       
                 $shopLedgerBalance = ShopLedger::select('balance')->orderBy('id', 'desc')->first();
                 if (!$shopLedgerBalance) {
                     $lastShopLedgerBalance = 0;
                 } else {
                     $lastShopLedgerBalance = $shopLedgerBalance->balance;
                 }
                 $description = 'Payment Received Against Customer';
                 $debit = $request->paid_amount;
                 $credit = 0;
                 $shopBalance = $debit - $credit + $lastShopLedgerBalance;
                 $shopLedger = new ShopLedger();
                 $shopLedger->customer_id= $request->customer_id;
                 $shopLedger->credit= $credit;
                 $shopLedger->debit= $debit;
                 $shopLedger->balance= $shopBalance;
                 $shopLedger->date= $date;
                 $shopLedger->description= $description;
                 $shopLedger->save();
             
             }
            });
            return redirect('customer-list')->with(['status' => 'success', 'message' => 'Payment Received successfully']);
        } catch (Exception $e) {
            return redirect('customer-receivable')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }
    public function receiptVoucher(Request $request)
    {
        $oldDateFrom = $request->input('date_from', '');
        $oldCustomerId = $request->input('customer_id', '');
    
        $query = Voucher::with('customer')->where('type', '=', 'Receipt')->orderBy('id', 'desc');
    
        if ($request->has('customer_id') && !empty($request->input('customer_id'))) {
            $query->where('customer_id', $request->input('customer_id'));
        }
    
        if ($request->has('date_from') && !empty($request->input('date_from'))) {
            $query->whereDate('date', '=', $request->input('date_from'));
        }
    
        $paymentVouchers = $query->get();
        $customers = Customer::all();
    
        return view('customer.VoucherList', compact('paymentVouchers', 'customers', 'oldCustomerId', 'oldDateFrom'));
    }
    

    public function viewVoucher($id)
    {   
        $voucher = Voucher::with('customer')->where('id', $id)->first();
        // Convert your view into HTML
        $html = view('customer.voucherViewPdf', [
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
