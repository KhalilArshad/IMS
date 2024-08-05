<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionSummary;
use App\Models\Driver;
use App\Models\DriverCustomer;
use App\Models\DriverStock;
use App\Models\DriverStockChild;
use App\Models\Invoice;
use App\Models\InvoiceChild;
use App\Models\Item;
use App\Models\Setting;
use App\Models\ShopLedger;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use PDF;
use Illuminate\Support\Facades\Validator;
// use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items= Item::get();
        $customers= Customer::get();
        $drivers= Driver::get();
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('invoice.create',compact('items','customers','drivers','system_date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDriverCustomer(Request $request)
    {
        $driver_id = $request->get('driver_id');
       
        $driverCustomers = DriverCustomer::where('driver_id', $driver_id)
                                        ->with('customer')
                                        ->get();
      
        $customerData = [];
        foreach ($driverCustomers as $dc) {
            $customerData[] = [
                'customer_id' => $dc->customer->id,
                'customer_name' => $dc->customer->name
            ];
        }

        return response()->json(['customers' => $customerData]);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'customer_id' => 'required',
            'driver_id' => 'required',
            'date' => 'required',
            'total_bill' => 'required',
            'itemid.*' => 'required|numeric|exists:items,id',
            'quantity.*' => 'required',
            'selling_price.*' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('create-invoice')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            DB::transaction(function () use ($request) {

             $invoice = new Invoice();
             $invoice->customer_id= $request->customer_id;
             $invoice->driver_id= $request->driver_id;
             if(($request->total_after_discount) == $request->paid_amount){
                $status ='Paid';
             }elseif($request->paid_amount == 0){
                $status ='UnPaid';
             }else{
                $status ='Partial Paid';
             }
             $invoice->status      = $status;
             $invoice->total_bill= $request->total_bill;
             $invoice->discount= $request->discount;
             $invoice->total_after_discount= $request->total_after_discount;
             $invoice->paid_amount= $request->paid_amount;
             $invoice->remaining= $request->remaining;
             $invoice->old_receive= $request->old_receive;
             $invoice->date= $request->date;
             $invoice->save();
             $invoice_id= $invoice->id;
             $invoice_no = 'INO-0'.$invoice_id;
             $update_invoice_no=Invoice::find($invoice_id)->update(['invoice_no'=> $invoice_no]);

             $customerBalance = DB::select("SELECT `previous_balance` FROM `customers` WHERE `id`=?", [$request->customer_id]);
             $customerPreviousBalance = $customerBalance[0]->previous_balance;
             $updatedCustomerBalance = $customerPreviousBalance + $request->remaining;
             // updating previous_balance of supplier
             DB::table("customers")->where("id", '=', $request->customer_id)->update(['previous_balance' => $updatedCustomerBalance]);

                $date = $request->date;
                $description = 'Credit Against Invoice:'.' '.$invoice_no;
                $customerLedger = new CustomerLedger();
                $customerLedger->customer_id= $request->customer_id;
                $customerLedger->invoice_id= $invoice_id;
                $customerLedger->credit= $request->paid_amount;
                $customerLedger->debit= $request->remaining;
                $customerLedger->balance= $updatedCustomerBalance;
                $customerLedger->date= $date;
                $customerLedger->description= $description;
                $customerLedger->save();
                
               
                if($request->old_receive == 0){
                    $customerTran = new CustomerTransactionSummary();
                    $customerTran->customer_id        = $request->customer_id;
                    $customerTran->today_bill         = $request->total_after_discount;
                    $customerTran->today_remaining    = $request->remaining;
                    $customerTran->old_remaining      = $customerPreviousBalance;
                    $customerTran->old_received       = 0;
                    $customerTran->net_remaining      = $updatedCustomerBalance;
                    $customerTran->description        = $description;
                    $customerTran->date  = $date;
                    $customerTran->save();
                }
                if($request->old_receive > 0){

                    $customerBalance = DB::select("SELECT `previous_balance` FROM `customers` WHERE `id`=?", [$request->customer_id]);
                    $customerPreviousBalance_ = $customerBalance[0]->previous_balance;
                    $updatedCustomerBalance_ = $customerPreviousBalance_ - $request->old_receive ;
                    // updating previous_balance of supplier
                    DB::table("customers")->where("id", '=', $request->customer_id)->update(['previous_balance' => $updatedCustomerBalance_]);
       
                    $description = 'Old Amount Received Against Invoice:'.' '.$invoice_no;
                    $customerLedger = new CustomerLedger();
                    $customerLedger->customer_id= $request->customer_id;
                    $customerLedger->invoice_id= $invoice_id;
                    $customerLedger->credit= $request->old_receive;
                    $customerLedger->debit= 0;
                    $customerLedger->balance= $updatedCustomerBalance_;
                    $customerLedger->date= $date;
                    $customerLedger->description= $description;
                    $customerLedger->save();

                    $customerTran = new CustomerTransactionSummary();
                    $customerTran->customer_id        = $request->customer_id;
                    $customerTran->today_bill         = $request->total_after_discount;
                    $customerTran->today_remaining    = $request->remaining;
                    $customerTran->old_remaining      = $customerPreviousBalance;
                    $customerTran->old_received       = $request->old_receive;
                    $customerTran->net_remaining      = $updatedCustomerBalance_;
                    $customerTran->description        = $description;
                    $customerTran->date  = $date;
                    $customerTran->save();
                    $shopLedgerBalance = ShopLedger::select('balance')->orderBy('id', 'desc')->first();
                    if (!$shopLedgerBalance) {
                        $lastShopLedgerBalance = 0;
                    } else {
                        $lastShopLedgerBalance = $shopLedgerBalance->balance;
                    }
                    $credit = 0;
                    $debit = $request->old_receive;
                    $shopBalance = $debit - $credit + $lastShopLedgerBalance;
                    $shopLedger = new ShopLedger();
                    $shopLedger->customer_id= $request->customer_id;
                    $shopLedger->invoice_id= $invoice_id;
                    $shopLedger->credit= $credit;
                    $shopLedger->debit= $debit;
                    $shopLedger->balance= $shopBalance;
                    $shopLedger->date= $date;
                    $shopLedger->description= $description;
                    $shopLedger->save();
                }
                if ($request->paid_amount > 0) {
                    $shopLedgerBalance = ShopLedger::select('balance')->orderBy('id', 'desc')->first();
                    if (!$shopLedgerBalance) {
                        $lastShopLedgerBalance = 0;
                    } else {
                        $lastShopLedgerBalance = $shopLedgerBalance->balance;
                    }
                    $description = 'Receive against Invoice'.' '.$invoice_no;
                    $credit = 0;
                    $debit = $request->paid_amount;
                    $shopBalance = $debit - $credit + $lastShopLedgerBalance;
                    $shopLedger = new ShopLedger();
                    $shopLedger->customer_id= $request->customer_id;
                    $shopLedger->invoice_id= $invoice_id;
                    $shopLedger->credit= $credit;
                    $shopLedger->debit= $debit;
                    $shopLedger->balance= $shopBalance;
                    $shopLedger->date= $date;
                    $shopLedger->description= $description;
                    $shopLedger->save();
                
                }
             $rows = count($request->itemid);
             $total_profit = 0;
             for ($i = 0; $i < $rows; $i++) {
                $purchasePriceExVAT = $request->purchase_price[$i];
                // $vatAmountPurchase = $purchasePriceExVAT * (15 / 100);
                // $totalPurchasePriceWithVAT = ($purchasePriceExVAT + $vatAmountPurchase) * $request->quantity[$i];

                // Calculate the total selling price including VAT
                $sellingPriceExVAT = $request->selling_price[$i];
                // $vatAmountSelling = $sellingPriceExVAT * ($request->vat_in_per[$i] / 100);
                // $totalSellingPriceWithVAT = ($sellingPriceExVAT + $vatAmountSelling) * $request->quantity[$i];

                // Calculate profit
                $profit = $sellingPriceExVAT - $purchasePriceExVAT;
                $item_total_profit = $profit *  $request->quantity[$i];
                $invoiceChild = new InvoiceChild();
                $invoiceChild->invoice_id= $invoice_id;
                $invoiceChild->item_id      = $request->itemid[$i];
                $invoiceChild->purchase_price= $request->purchase_price[$i];
                $invoiceChild->selling_price= $request->selling_price[$i];
                // $invoiceChild->vat_in_per= $request->vat_in_per[$i];
                // $invoiceChild->total_vat= $request->total_vat[$i];
                $invoiceChild->quantity= $request->quantity[$i];
                $invoiceChild->total= $request->total[$i];
                $invoiceChild->profit= $item_total_profit;
                $invoiceChild->save();

                // geeting quantity of item from stock table using item_id
                $driverStock =DriverStock::where('item_id',$request->itemid[$i])
                ->where('driver_id',$request->driver_id)
                ->select('id','current_stock')->first();
                $driverCurrentStock =$driverStock->current_stock;
                if ($driverCurrentStock == $request->quantity[$i]) {
                    DriverStock::where('item_id',$request->itemid[$i])->where('driver_id',$request->driver_id)->update(['current_stock'=>0]);
                    $remainingDriverStock =0;
                } elseif ($request->quantity[$i] < $driverCurrentStock) {
                    $remainingDriverStock = $driverCurrentStock -  $request->quantity[$i];
                    DriverStock::where('item_id',$request->itemid[$i])->where('driver_id',$request->driver_id)->update(['current_stock'=>$remainingDriverStock]);
                }

                $driverStockChild = new DriverStockChild();
                $driverStockChild->driver_id= $request->driver_id;
                $driverStockChild->customer_id= $request->customer_id;
                $driverStockChild->item_id      = $request->itemid[$i];
                $driverStockChild->date= $request->date;
                $driverStockChild->current_stock= $remainingDriverStock;
                $driverStockChild->sold_stock=  $request->quantity[$i];
                $driverStockChild->remarks= "Sold";
                $driverStockChild->save();
                
                $stockTransaction = new StockTransaction();
                $stockTransaction->invoice_id= $invoice_id;
                $stockTransaction->customer_id= $request->customer_id;
                $stockTransaction->driver_id= $request->driver_id;
                $stockTransaction->item_id= $request->itemid[$i];
                $stockTransaction->unit_price= $request->purchase_price[$i];
                $stockTransaction->quantity=  $request->quantity[$i];
                $stockTransaction->sale_price= $request->selling_price[$i];
                $stockTransaction->total=  $request->total[$i];
                $stockTransaction->date= $date;
                $stockTransaction->inventory_type= 'inventory sale with invoice'.' '.$invoice_no;
                $stockTransaction->save();
                
                $total_profit = $total_profit + $item_total_profit;
             }
             $update_invoice_no=Invoice::find($invoice_id)->update(['profit'=> $total_profit]);
            });
            return redirect()->back()->with(['status' => 'success', 'message' => 'Invoice stored successfully']);
        } catch (Exception $e) {
            return redirect('create-purchase-order')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
        
    }

    public function list()
    {
        $customers= Customer::get();
         $invoices= Invoice::with('customer','driver')->orderBy('id', 'desc')->get();
        return view('invoice.list',compact('invoices'));
    }
      /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewInvoice($id)
    {   
        $invoice = Invoice::with('customer')->where('id', $id)->first();
        $invoiceChild = InvoiceChild::with('items')->where('invoice_id', $id)->get();
        // Convert your view into HTML
        $html = view('invoice.invoiceViewPdf', [
            'invoice' => $invoice, 
            'invoiceChild' => $invoiceChild
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
        return response($mpdf->Output('Invoice-' . $invoice->invoice_no . '-Date' . $invoice->date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
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
        //
    }

    public function createStockAssignToDriver(){
        $items= Item::get();
        $customers= Customer::get();
        $drivers= Driver::get();
        $system_date = Setting::select('id','system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('invoice.createStockAssignToDriver',compact('items','customers','drivers','system_date'));
    }

    public function  saveStockAssignToDriver(Request $request){
       $rules = array(
        'driver_id' => 'required',
        'date' => 'required',
        'itemid.*' => 'required|numeric|exists:items,id',
        'quantity.*' => 'required',
      
       );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('stockAssignTo-driver')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            DB::transaction(function () use ($request) {

            $rows = count($request->itemid);
            for ($i = 0; $i < $rows; $i++) {

                //first get the item that already in drive stock
                $getQuantity =Stock::where('item_id',$request->itemid[$i])->select('id','quantity','unit_price')->first();
                $quantityInStock =$getQuantity->quantity;
                $purchase_price =$getQuantity->unit_price;

                $getQuantity =DriverStock::where('item_id',$request->itemid[$i])
                ->where('driver_id',$request->driver_id)
                ->select('id','current_stock')->first();
            
                if($getQuantity){
                    $lastDriverStockId = $getQuantity->id;
                    $DriverCurrentStock =$getQuantity->current_stock;
                    $updatedDriverStock = $DriverCurrentStock + $request->quantity[$i];

                    DB::table("driver_stocks")
                    ->where('id', $lastDriverStockId)
                    ->update(['current_stock' => $updatedDriverStock,'purchase_price'=>$purchase_price]);
                }else{
                    $driverStock = new DriverStock();
                    $driverStock->driver_id= $request->driver_id;
                    $driverStock->item_id= $request->itemid[$i];
                    $driverStock->purchase_price= $purchase_price;
                    $driverStock->current_stock=  $request->quantity[$i];
                    $driverStock->save();
                }
              
            
                if ($quantityInStock == $request->quantity[$i]) {
                    Stock::where('item_id',$request->itemid[$i])->update(['quantity'=>0]);
                } elseif ($request->quantity[$i] < $quantityInStock) {
                    $remainingQuantity = $quantityInStock -  $request->quantity[$i];
                    Stock::where('item_id',$request->itemid[$i])->update(['quantity'=>$remainingQuantity]);
                }

                $driverStockChild = new DriverStockChild();
                $driverStockChild->driver_id= $request->driver_id;
                $driverStockChild->item_id      = $request->itemid[$i];
                $driverStockChild->date= $request->date;
                $driverStockChild->current_stock= $request->quantity[$i];
                $driverStockChild->sold_stock= 0;
                $driverStockChild->remarks= "Received";
                $driverStockChild->save();
                
            }

            });
            return redirect()->back()->with(['status' => 'success', 'message' => 'Stock Assign To Driver successfully']);
        } catch (Exception $e) {
            return redirect('stockAssignTo-driver')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function getItemUnitForSale(Request $request){
        $item_id=$request->get('item_id');
        $driver_id=$request->get('driver_id');
        $customer_id=$request->get('customer_id');
        $item= Item::with('unit')->where('id',$item_id)->first();
        $stocks=Stock::select('quantity','unit_price')->where('item_id',$item_id)->first();
        $driverStock=DriverStock::select('id','current_stock','purchase_price')->where('driver_id',$driver_id)->where('item_id',$item_id)->first();
        $invoice = InvoiceChild::with('invoice')
        ->where('item_id', $item_id)
        ->whereHas('invoice', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
        })
        ->orderBy('id', 'desc')
        ->first();
        if($invoice){
            $selling_price = $invoice->selling_price??'';
            $last_selling_date = $invoice->created_at->format('Y-m-d');
        }else{
            $selling_price = '';
            $last_selling_date = '';
        }
        if($driverStock){
            $driverCurrentStock = $driverStock->current_stock??0;
            $purchase_price = $driverStock->purchase_price??0;
        }else{
            $driverCurrentStock = 0;
            $purchase_price = 0;
        }
        if($item)
        {            
            $unit_name = $item->unit->name??'';
            
            return response()->json(['unit_name' => $unit_name,'purchase_price'=>$purchase_price,'driverCurrentStock'=>$driverCurrentStock,'selling_price'=>$selling_price,'last_selling_date'=>$last_selling_date]);
        }
        else
        {
            echo "not found";
        }
    }

    public function driverStockHistory(){
    
        $drivers= Driver::get();
        return view('driver.driverStockHistory',compact('drivers'));
    }

    public function currentDriverStock(Request $request){
       $driverStocks = DriverStock::with('driver','item')->where('driver_id',$request->id)->get();
      return view('driver.driversCurrentStock',compact('driverStocks'));

    }

    public function driverStockFlow(Request $request){
       
        $driver_id =$request->id;
       $driver=Driver::where('id',$driver_id)->select('id','name')->first();
       $driverName =$driver->name;
       if ($request->from && $request->to) {
        $from = $request->from;
        $to = $request->to;
    } else {
        // $from = Carbon::now()->subDay()->toDateString();
        $from = date('Y-m-d');
        $to = date('Y-m-d');
    }
    // return [$from, $to];    
        //  return $from;
       $driverReceiveStock=DriverStockChild::with('item')->where('driver_id',$driver_id)->where('remarks','Received')->whereBetween('date', [$from, $to])->get();
       $driverSoldStock = InvoiceChild::with('invoice','items')
       ->whereHas('invoice', function ($query) use ($driver_id) {
           $query->where('driver_id', $driver_id);
       })
       ->whereBetween('created_at', [$from, $to])
       ->get();
       $driveTotalInvoices =Invoice::with('customer')->where('driver_id',$driver_id)->whereBetween('date', [$from, $to])->get();
      return view('driver.driverStockFlow',compact('driverName','driver_id','driverReceiveStock','driverSoldStock','driveTotalInvoices', 'from', 'to'));

    }

    public function getCustomerRemaining(Request $request)
    {
        $id=$request->id;
        $previous_balance=Customer::select('id','previous_balance')->where('id',$id)->first();
        return $previous_balance;
    }
}
