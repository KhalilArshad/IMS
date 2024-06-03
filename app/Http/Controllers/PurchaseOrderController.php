<?php

namespace App\Http\Controllers;

use App\Models\DriverStock;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderChild;
use App\Models\ShopLedger;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items= Item::get();
        $suppliers= Supplier::get();
        return view('purchaseOrder.create',compact('items','suppliers'));
    }
    public function getPoNo(){
         $purchase_order= PurchaseOrder::orderBy('id','desc')->first();
        if(empty($purchase_order->id)){
            $purchase_order_last_id= 1;
        }else{
            $purchase_order_last_id= $purchase_order->id;
        }
      return  $updated_po_no ='PO-'.$purchase_order_last_id+1;
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
        $rules = array(
            'supplier_id' => 'required',
            'po_no' => 'required',
            'date' => 'required',
            'total_bill' => 'required',
            'itemid.*' => 'required|numeric|exists:items,id',
            'quantity.*' => 'required',
            'unit_price.*' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('create-purchase-order')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            DB::transaction(function () use ($request) {
             $purchaseOrder = new PurchaseOrder();
             $purchaseOrder->supplier_id= $request->supplier_id;
             $purchaseOrder->status      = 'Inprogress';
             $purchaseOrder->po_no= $request->po_no;
             $purchaseOrder->total_bill= $request->total_bill;
             $purchaseOrder->current_payment= $request->paid_amount;
             $purchaseOrder->remaining= $request->remaining;
             $purchaseOrder->date= $request->date;
             $purchaseOrder->save();
             $purchase_order_id= $purchaseOrder->id;
             
             $rows = count($request->itemid);
             for ($i = 0; $i < $rows; $i++) {
                $purchaseOrderChild = new PurchaseOrderChild();
                $purchaseOrderChild->purchase_order_id= $purchase_order_id;
                $purchaseOrderChild->item_id      = $request->itemid[$i];
                $purchaseOrderChild->unit_price= $request->unit_price[$i];
                // $purchaseOrderChild->sale_price= $request->selling_price[$i];
                $purchaseOrderChild->quantity= $request->quantity[$i];
                $purchaseOrderChild->vat_in_per= $request->vat_in_per[$i];
                $purchaseOrderChild->total_vat= $request->total_vat[$i];
                $purchaseOrderChild->total= $request->total[$i];
                $purchaseOrderChild->save();
             }

            });
            return redirect()->back()->with(['status' => 'success', 'message' => 'Purchase Order stored successfully']);
        } catch (Exception $e) {
            return redirect('create-purchase-order')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $suppliers= Supplier::get();
         $purchaseOrders= PurchaseOrder::with('supplier')->orderBy('id', 'desc')->get();
        return view('purchaseOrder.list',compact('purchaseOrders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPurchaseOrder($id)
    {
    
        $purchaseOrder=PurchaseOrder::with('supplier')->where('id',$id)->first();
         $purchaseOrderChild=PurchaseOrderChild::with('items')->where('purchase_order_id',$id)->get();
        // $pdf = PDF::loadView('purchaseOrder.purchaseOrderViewPdf', ['purchaseOrder' => $purchaseOrder, 'purchaseOrderChild' => $purchaseOrderChild]);
        // return $pdf->stream('PurchaseOrder-' . $purchaseOrder->po_no . '-Date' . $purchaseOrder->date . '.pdf');
        // Convert your view into HTML
        $html = view('purchaseOrder.purchaseOrderViewPdf', [
            'purchaseOrder' => $purchaseOrder, 
            'purchaseOrderChild' => $purchaseOrderChild
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
        return response($mpdf->Output('PurchaseOrder-' . $purchaseOrder->po_no . '-Date' . $purchaseOrder->date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receivePo($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $lastSupplierLedgerBalance = 0;     
                DB::table("purchase_orders")->where('id', '=', $id)->update(['status' => 'Received']);
                $purchaseOrder=PurchaseOrder::where('id',$id)->first();
                $purchase_order_id = $purchaseOrder->id;
                $supplier_id = $purchaseOrder->supplier_id;
                $purchase_order_date = $purchaseOrder->date;
                $total_bill = $purchaseOrder->total_bill;
                $current_payment = $purchaseOrder->current_payment;
                $remaining = $purchaseOrder->remaining;
                // selecting previous_balance of supplier
                $supplierBalance = DB::select("SELECT `previous_balance` FROM `suppliers` WHERE `id`=?", [$supplier_id]);
                $supplierPreviousBalance = $supplierBalance[0]->previous_balance;
                $updatedSupplierBalance = $supplierPreviousBalance + $remaining;
                // updating previous_balance of supplier
                DB::table("suppliers")->where("id", '=', $supplier_id)->update(['previous_balance' => $updatedSupplierBalance]);
                // selecting previous_balance of SupplierLedger
                // $supplierLedgerBalance = SupplierLedger::select('balance')->where('supplier_id', $supplier_id)->orderBy('id', 'desc')->first();
                // if (!$supplierLedgerBalance) {
                //     $lastSupplierLedgerBalance = 0;
                // } else {
                //     $lastSupplierLedgerBalance = $supplierLedgerBalance->balance;
                // }
                $date = date('Y-m-d');
                if($current_payment == $total_bill){
                    $description = 'Paid against purchase order';
                }else{
                    $description = 'Remaining against purchase order';
                }
             
                $supplierLedger = new SupplierLedger();
                $supplierLedger->supplier_id= $supplier_id;
                $supplierLedger->purchase_order_id= $purchase_order_id;
                $supplierLedger->previous_balance= $supplierPreviousBalance;
                $supplierLedger->total_bill= $total_bill;
                $supplierLedger->payment= $current_payment;
                $supplierLedger->remaining= $updatedSupplierBalance;
                $supplierLedger->date= $date;
                $supplierLedger->description= $description;
                $supplierLedger->save();

                    if ($current_payment > 0) {
                        $shopLedgerBalance = ShopLedger::select('balance')->orderBy('id', 'desc')->first();
                        if (!$shopLedgerBalance) {
                            $lastShopLedgerBalance = 0;
                        } else {
                            $lastShopLedgerBalance = $shopLedgerBalance->balance;
                        }
                        $description = 'Paid against supplier';
                        $debit = 0;
                        $credit = $current_payment;
                        $shopBalance = $debit - $credit + $lastShopLedgerBalance;
                        $shopLedger = new ShopLedger();
                        $shopLedger->supplier_id= $supplier_id;
                        $shopLedger->purchase_order_id= $purchase_order_id;
                        $shopLedger->credit= $credit;
                        $shopLedger->debit= $debit;
                        $shopLedger->balance= $shopBalance;
                        $shopLedger->date= $date;
                        $shopLedger->description= $description;
                        $shopLedger->save();
                    
                    }
        
                $purchaseOrderChild=PurchaseOrderChild::where('purchase_order_id',$id)->get();
        
                $child_po_data = DB::select("SELECT * FROM `purchase_order_children` WHERE `purchase_order_id`=?", [$id]);
                foreach ($purchaseOrderChild as $row) :
                    $stocks = Stock::select('id','quantity')->where('item_id', $row->item_id)->first();
                    if ($stocks) {
                        $lastStock_id = $stocks->id;
                        $lastStockQuantity = $stocks->quantity;
                        $updatedQuantity = $lastStockQuantity + $row->quantity;
                    }
                 
                    if ($stocks) {
                        DB::table("stocks")
                            ->where('id', $lastStock_id)
                            ->update(['quantity' => $updatedQuantity,'unit_price' =>$row->unit_price]);
                    } else {
                        $newStock = new Stock();
                        $newStock->item_id= $row->item_id;
                        $newStock->quantity= $row->quantity;
                        $newStock->unit_price= $row->unit_price;
                        $newStock->save();
                    }
                    $date = date("Y-m-d");
                    $stockTransaction = new StockTransaction();
                    $stockTransaction->purchase_order_id= $id;
                    $stockTransaction->supplier_id= $supplier_id;
                    $stockTransaction->item_id= $row->item_id;
                    $stockTransaction->unit_price= $row->unit_price;
                    $stockTransaction->quantity= $row->quantity;
                    $stockTransaction->total= $row->total;
                    $stockTransaction->date= $date;
                    $stockTransaction->inventory_type= 'inventory_With_Po';
                    $stockTransaction->save();
                  endforeach;

            });
            return redirect()->back()->with(['status' => 'success', 'message' => 'Purchase Order Received successfully']);
        } catch (Exception $e) {
            return redirect('purchase-order-list')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id=$request->id;
        $suppliers= Supplier::select('id','name')->get();
        $purchaseOrder=PurchaseOrder::where('id',$id)->first();
        $purchaseOrderChild=PurchaseOrderChild::with('items')->where('purchase_order_id',$id)->get();
        $items= Item::get();
        return view('purchaseOrder.edit',compact('purchaseOrder','purchaseOrderChild','items','suppliers'));
    }

    public function deletePoItem($id)
    {
        $url = url()->previous();
        $NoOfItems = null;
        $purchase_order_id = 0;
        DB::transaction(function () use ($id, &$purchase_order_id, &$NoOfItems) {
            $poChildData =PurchaseOrderChild::select('purchase_order_id','total')->where('id',$id)->first();
            $totalOfPoChild = $poChildData->total;
            $purchase_order_id = $poChildData->purchase_order_id;
          
            $poData =PurchaseOrder::select('total_bill')->where('id',$purchase_order_id)->first();
            $totalOfPoParent = $poData->total_bill;
            $updatedPoParentTotal = $totalOfPoParent - $totalOfPoChild;
            DB::table("purchase_orders")
                ->where("id", $purchase_order_id)
                ->update(["total_bill" => $updatedPoParentTotal]);
            PurchaseOrderChild::destroy($id);
            $NoOfItems = PurchaseOrderChild::where('purchase_order_id', $purchase_order_id)->count();
        });
        if ($NoOfItems > 0) {
            return redirect($url);
        } else {
            PurchaseOrder::destroy($purchase_order_id);
            return redirect()->to('/purchase-order-list');
        }
    }

    public function updatePurchaseOrder(Request $request){
        DB::transaction(function () use ($request) {
            $purchase_order_id = $request->purchase_order_id;
            $supplier_id = $request->supplier_id;
            $po_no = $request->po_no;
            $date = $request->date;
            $total_bill = $request->total_bill;
            $paid_amount = $request->paid_amount;
            $remaining = $request->remaining;

            // updating table parent
            DB::table("purchase_orders")->where('id', '=', $purchase_order_id)
                ->update([
                    'supplier_id' => $supplier_id,
                    'po_no' => $po_no,
                    'total_bill' => $total_bill,
                    'current_payment' => $paid_amount,
                    'remaining' => $remaining,
                    'date' => $date,
                ]);

                $rows = count($request->purchase_order_child_id);
                for ($i = 0; $i < $rows; $i++) {
                   $purchaseOrderChild = PurchaseOrderChild::find($request->purchase_order_child_id[$i]);
                   $purchaseOrderChild->unit_price= $request->unit_price[$i];
                 //$purchaseOrderChild->sale_price= $request->selling_price[$i];
                   $purchaseOrderChild->quantity= $request->quantity[$i];
                   $purchaseOrderChild->total= $request->total[$i];
                   $purchaseOrderChild->save();
                }
        });
        return redirect('purchase-order-list')->with(['status' => 'success', 'message' => 'Purchase Order Update successfully']);
    }
    public function getItemUnit(Request $request)
    {
        $item_id=$request->get('item_id');
        $driver_id=$request->get('driver_id');
        $item= Item::with('unit')->where('id',$item_id)->first();
        $stocks=Stock::select('quantity','unit_price')->where('item_id',$item_id)->first();
        $driverStock=DriverStock::select('id','current_stock')->where('driver_id',$driver_id)->where('item_id',$item_id)->first();
        if($driverStock){
            $driverCurrentStock = $driverStock->current_stock;

        }else{
            $driverCurrentStock = 0;
        }
        if($item)
        {            
            $unit_name = $item->unit->name??'';
            $purchase_price = $stocks->unit_price??0;
            $total_stock = $stocks->quantity??0;
            return response()->json(['unit_name' => $unit_name, 'total_stock' => $total_stock,'purchase_price'=>$purchase_price,'driverCurrentStock'=>$driverCurrentStock]);
        }
        else
        {
            echo "not found";
        }
    } 
    public function deletePurchaseOrder($id){
        PurchaseOrder::destroy($id);
        PurchaseOrderChild::where('purchase_order_id',$id)->delete();
        return redirect('purchase-order-list')->with(['status' => 'success', 'message' => 'Purchase Order Deleted successfully']);
    }
}
