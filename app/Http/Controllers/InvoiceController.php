<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\Driver;
use App\Models\DriverStock;
use App\Models\DriverStockChild;
use App\Models\Invoice;
use App\Models\InvoiceChild;
use App\Models\Item;
use App\Models\ShopLedger;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use PDF;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return view('invoice.create',compact('items','customers','drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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

                $date = date('Y-m-d');
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
             for ($i = 0; $i < $rows; $i++) {
                $purchasePriceExVAT = $request->purchase_price[$i];
                $vatAmountPurchase = $purchasePriceExVAT * ($request->vat_in_per[$i] / 100);
                $totalPurchasePriceWithVAT = ($purchasePriceExVAT + $vatAmountPurchase) * $request->quantity[$i];

                // Calculate the total selling price including VAT
                $sellingPriceExVAT = $request->selling_price[$i];
                $vatAmountSelling = $sellingPriceExVAT * ($request->vat_in_per[$i] / 100);
                $totalSellingPriceWithVAT = ($sellingPriceExVAT + $vatAmountSelling) * $request->quantity[$i];

                // Calculate profit
                $profit = $totalSellingPriceWithVAT - $totalPurchasePriceWithVAT;
                $invoiceChild = new InvoiceChild();
                $invoiceChild->invoice_id= $invoice_id;
                $invoiceChild->item_id      = $request->itemid[$i];
                $invoiceChild->purchase_price= $request->purchase_price[$i];
                $invoiceChild->selling_price= $request->selling_price[$i];
                $invoiceChild->vat_in_per= $request->vat_in_per[$i];
                $invoiceChild->total_vat= $request->total_vat[$i];
                $invoiceChild->quantity= $request->quantity[$i];
                $invoiceChild->total= $request->total[$i];
                $invoiceChild->profit= $profit;
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
                

                $date = date("Y-m-d");
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
                
             }

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
    
        $invoice=Invoice::with('customer')->where('id',$id)->first();
         $invoiceChild=InvoiceChild::with('items')->where('invoice_id',$id)->get();
        $pdf = PDF::loadView('invoice.invoiceViewPdf', ['invoice' => $invoice, 'invoiceChild' => $invoiceChild]);
        return $pdf->stream('Invoice-' . $invoice->invoice_no . '-Date' . $invoice->date . '.pdf');
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
        return view('invoice.createStockAssignToDriver',compact('items','customers','drivers'));
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
               $getQuantity =DriverStock::where('item_id',$request->itemid[$i])
               ->where('driver_id',$request->driver_id)
               ->select('id','current_stock')->first();
           
               if($getQuantity){
                   $lastDriverStockId = $getQuantity->id;
                   $DriverCurrentStock =$getQuantity->current_stock;
                   $updatedDriverStock = $DriverCurrentStock + $request->quantity[$i];

                   DB::table("driver_stocks")
                   ->where('id', $lastDriverStockId)
                   ->update(['current_stock' => $updatedDriverStock,'purchase_price'=>$request->purchase_price[$i]]);
               }else{
                $driverStock = new DriverStock();
                $driverStock->driver_id= $request->driver_id;
                $driverStock->item_id= $request->itemid[$i];
                $driverStock->purchase_price= $request->purchase_price[$i];
                $driverStock->current_stock=  $request->quantity[$i];
                $driverStock->save();
               }
               $getQuantity =Stock::where('item_id',$request->itemid[$i])->select('id','quantity')->first();
               $quantityInStock =$getQuantity->quantity;
           
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
        }else{
            $selling_price = '';
        }
        if($driverStock){
            $driverCurrentStock = $driverStock->current_stock??0;
            $purchase_price = $driverStock->purchase_price??0;
        }else{
            $driverCurrentStock = 0;
        }
        if($item)
        {            
            $unit_name = $item->unit->name??'';
            
            return response()->json(['unit_name' => $unit_name,'purchase_price'=>$purchase_price,'driverCurrentStock'=>$driverCurrentStock,'selling_price'=>$selling_price]);
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
}
