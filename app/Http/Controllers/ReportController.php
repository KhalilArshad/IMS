<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionSummary;
use App\Models\Driver;
use App\Models\DriverCustomer;
use App\Models\DriverStock;
use App\Models\DriverStockChild;
use App\Models\Employee;
use App\Models\EmployeeAdvance;
use App\Models\Invoice;
use App\Models\InvoiceChild;
use App\Models\Item;
use App\Models\Payroll;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderChild;
use App\Models\ShopLedger;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dailyDriverSaleReport(Request $request)
    {

        if(!empty($request->driver_id)){
        $invoiceChildren = InvoiceChild::with('invoice','items')
            ->whereHas('invoice', function ($query) use ($request) {
                if ($request->has('driver_id') && !empty($request->driver_id)) {
                    $query->where('driver_id', $request->driver_id);
                }
                if ($request->has('item_id') && !empty($request->item_id)) {
                    $query->where('item_id', $request->item_id);
                }
                if ($request->has('date_from') && !empty($request->date_from)) {
                    $query->whereDate('date', '>=', $request->date_from);
                }

                if ($request->has('date_to') && !empty($request->date_to)) {
                    $query->whereDate('date', '<=', $request->date_to);
                }
            })
          ->get();
          $sums = DB::table('invoices')
          ->select(
              DB::raw('SUM(invoices.total_bill) as totalBills'),
              DB::raw('SUM(invoices.discount) as totalDiscounts'),
              DB::raw('SUM(invoices.total_after_discount) as totalAfterDiscount'),
              DB::raw('SUM(invoices.paid_amount) as totalPaidAmount'),
              DB::raw('SUM(invoices.remaining) as totalRemaining')
          )
          ->where(function ($query) use ($request) {
              if ($request->has('driver_id') && !empty($request->driver_id)) {
                  $query->where('invoices.driver_id', $request->driver_id);
              }
      
              if ($request->has('date_from') && !empty($request->date_from)) {
                  $query->whereDate('invoices.date', '>=', $request->date_from);
              }
      
              if ($request->has('date_to') && !empty($request->date_to)) {
                  $query->whereDate('invoices.date', '<=', $request->date_to);
              }
          })
          ->first();
          $total_bills = $sums->totalBills;
          $total_discounts = $sums->totalDiscounts;
          $total_after_discount = $sums->totalAfterDiscount;
          $total_paid_amount = $sums->totalPaidAmount;
          $total_remaining = $sums->totalRemaining;
        }else{
            $invoiceChildren=[];
            $total_bills = '';
            $total_discounts = '';
            $total_after_discount = '';
            $total_paid_amount = '';
            $total_remaining = '';
        }
        $drivers= Driver::get();
        $items= Item::get();
        return view('reports.driverDailySaleReport',compact('invoiceChildren','drivers','items','total_bills','total_discounts','total_after_discount','total_paid_amount','total_remaining'))->with('oldDriverId', $request->driver_id)
        ->with('oldDateFrom', $request->date_from)
        ->with('oldItem_id', $request->item_id)
        ->with('oldDateTo', $request->date_to);
    }
    public function driverDailyReport(Request $request)
    {
        if(!empty($request->driver_id)){
            $invoices = Invoice::where(function ($query) use ($request) {
                if ($request->has('driver_id') && !empty($request->driver_id)) {
                    $query->where('driver_id', $request->driver_id);
                }
        
                if ($request->has('date_from') && !empty($request->date_from)) {
                    $query->whereDate('date', '>=', $request->date_from);
                }
        
                if ($request->has('date_to') && !empty($request->date_to)) {
                    $query->whereDate('date', '<=', $request->date_to);
                }
            })
            ->get();
            // Attach last balance from Customer to each invoice
            foreach ($invoices as $invoice) {
                // Assume Customer model has a 'last_balance' attribute
                $customer = Customer::where('id', $invoice->customer_id)->first();
                $invoice->customer_name = $customer ? $customer->name : 0;
                $invoice->customer_last_balance = $customer ? $customer->previous_balance : 0;
            }
    
           
              $vehicleExpense= VehicleExpense::with('vehicle')
            ->whereHas('vehicle', function ($query) use ($request) {
                if ($request->has('driver_id') && !empty($request->driver_id)) {
                    $query->where('driver_id', $request->driver_id);
                }
                if ($request->has('date_from') && !empty($request->date_from)) {
                    $query->whereDate('date', '>=', $request->date_from);
                }
    
                if ($request->has('date_to') && !empty($request->date_to)) {
                    $query->whereDate('date', '<=', $request->date_to);
                }
              })
            ->get();
            $driverDailyExpenseSum = 0;
            $otherExpenseSum = 0;
            
            foreach ($vehicleExpense as $expense) {
                if ($expense->expense_type === 'Driver Daily Expense') {
                    $driverDailyExpenseSum += $expense->amount;
                } else {
                    $otherExpenseSum += $expense->amount;
                }
            }
          $invoices = $invoices;
          $driverDailyExpenseSum = $driverDailyExpenseSum;
          $otherExpenseSum = $otherExpenseSum;
        }else{
            $invoices=[];
            $driverDailyExpenseSum = 0;
            $otherExpenseSum = 0;
        }
        $drivers= Driver::get();
        return view('reports.driverDailyReport',compact('invoices','drivers','driverDailyExpenseSum','otherExpenseSum'))->with('oldDriverId', $request->driver_id)
        ->with('oldDateFrom', $request->date_from)
        ->with('oldDateTo', $request->date_to);
    }
 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function driverDailyReportPrint(Request $request)
    {
        $invoices = Invoice::where(function ($query) use ($request) {
            if ($request->has('driver_id') && !empty($request->driver_id)) {
                $query->where('driver_id', $request->driver_id);
            }
    
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('date', '>=', $request->date_from);
            }
    
            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('date', '<=', $request->date_to);
            }
        })
        ->get();
        // Attach last balance from Customer to each invoice
        foreach ($invoices as $invoice) {
            // Assume Customer model has a 'last_balance' attribute
            $customer = Customer::where('id', $invoice->customer_id)->first();
            $invoice->customer_name = $customer ? $customer->name : 0;
            $invoice->customer_last_balance = $customer ? $customer->previous_balance : 0;
        }

        // return $invoices;
          $vehicleExpense= VehicleExpense::with('vehicle')
        ->whereHas('vehicle', function ($query) use ($request) {
            if ($request->has('driver_id') && !empty($request->driver_id)) {
                $query->where('driver_id', $request->driver_id);
            }
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('date', '<=', $request->date_to);
            }
          })
        ->get();
        $driverDailyExpenseSum = 0;
        $otherExpenseSum = 0;
        
        foreach ($vehicleExpense as $expense) {
            if ($expense->expense_type === 'Driver Daily Expense') {
                $driverDailyExpenseSum += $expense->amount;
            } else {
                $otherExpenseSum += $expense->amount;
            }
        }
      $invoices = $invoices;
      $driverDailyExpenseSum = $driverDailyExpenseSum;
      $otherExpenseSum = $otherExpenseSum;
      $driver= Driver::where('id',$request->driver_id)->first();
      $driverName= $driver->name??'';
        // Convert your view into HTML
        $html = view('reports.driverDailyReportPrint', [
            'invoices' => $invoices, 
            'driverDailyExpenseSum' => $driverDailyExpenseSum,
            'otherExpenseSum' => $otherExpenseSum,
            'driverName' => $driverName,
            'date' => $request->date_from,
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
        return response($mpdf->Output('driverDailyReport-Date' . $request->date_from . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    public function customerDetailsReport(Request $request)
    {
        if(!empty($request->driver_id)){
            
          $invoices = CustomerTransactionSummary::with('customer')->where(function ($query) use ($request) {
            if ($request->has('driver_id') && !empty($request->driver_id)) {
                $customerIds = DriverCustomer::where('driver_id', $request->driver_id)->pluck('customer_id');
                $query->whereIn('customer_id', $customerIds);
            }
            if ($request->has('customer_id') && !empty($request->customer_id)) {
                $query->where('customer_id', $request->customer_id);
            }
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('date', '<=', $request->date_to);
            }
        })
        ->get();
        }else{
            $invoices=[];
        
        }
        $drivers= Driver::get();
        return view('reports.customerDetailsReport',compact('invoices','drivers'))->with('oldDriverId', $request->driver_id)
        ->with('oldCustomerId', $request->customer_id)
        ->with('oldDateFrom', $request->date_from)
        ->with('oldDateTo', $request->date_to);
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerDetailsReportPrint(Request $request)
    {
        $invoices = CustomerTransactionSummary::with('customer')->where(function ($query) use ($request) {
            if ($request->has('driver_id') && !empty($request->driver_id)) {
                $customerIds = DriverCustomer::where('driver_id', $request->driver_id)->pluck('customer_id');
                $query->whereIn('customer_id', $customerIds);
            }
            if ($request->has('customer_id') && !empty($request->customer_id)) {
                $query->where('customer_id', $request->customer_id);
            }
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('date', '<=', $request->date_to);
            }
        })
        ->get();

      $driver= Driver::where('id',$request->driver_id)->first();
      $driverName= $driver->name??'';
      $date =date('Y-d-m');
        // Convert your view into HTML
        $html = view('reports.customerDetailsReportPrint', [
            'invoices' => $invoices, 
            'driverName' => $driverName,
            'date' => $date,
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
        return response($mpdf->Output('customerDetailsReport-Date' . $date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    public function allCustomerRemainingReport(Request $request)
    {
        if(!empty($request->driver_id)){
            
          $allRemaining = Customer::where(function ($query) use ($request) {
            if ($request->has('driver_id') && !empty($request->driver_id)) {
                $customerIds = DriverCustomer::where('driver_id', $request->driver_id)->pluck('customer_id');
                $query->whereIn('id', $customerIds);
            }
            if ($request->has('customer_id') && !empty($request->customer_id)) {
                $query->where('id', $request->customer_id);
            }
           })
          ->get();
        }else{
            $allRemaining=[];
        
        }
        $drivers= Driver::get();
        return view('reports.allCustomerRemainingReport',compact('allRemaining','drivers'))->with('oldDriverId', $request->driver_id)
        ->with('oldCustomerId', $request->customer_id);
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allCustomerRemainingReportPrint(Request $request)
    {
        $allRemaining = Customer::where(function ($query) use ($request) {
            if ($request->has('driver_id') && !empty($request->driver_id)) {
                $customerIds = DriverCustomer::where('driver_id', $request->driver_id)->pluck('customer_id');
                $query->whereIn('id', $customerIds);
            }
            if ($request->has('customer_id') && !empty($request->customer_id)) {
                $query->where('id', $request->customer_id);
            }
           })
          ->get();
      $driver= Driver::where('id',$request->driver_id)->first();
      $driverName= $driver->name??'';
      $date =date('Y-d-m');
        // Convert your view into HTML
        $html = view('reports.allCustomerRemainingReportPrint', [
            'allRemaining' => $allRemaining, 
            'driverName' => $driverName,
            'date' => $date,
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
        return response($mpdf->Output('AllCustomerRemainingReport-Date' . $date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    public function driverReport(Request $request)
    {
       
        if(!empty($request->driver_id)){
            $date = $request->date;
            $driverReceiveStocks = DriverStockChild::with('item')->whereDate('date',$date)->where('driver_id',$request->driver_id)->where('remarks','Received')->get();
          foreach ($driverReceiveStocks as $driverStock) {
            $purchaseOrder = PurchaseOrderChild::where('item_id', $driverStock->item_id)->whereDate('created_at',$date)->orderBy('id', 'desc')->first();
            $driverStock->purchase_price = $purchaseOrder ? $purchaseOrder->unit_price : 0;
            $driverStock->vat = $purchaseOrder ? $purchaseOrder->vat_in_per : 0;
        }
              $driverReceiveStocks ;
            $driverRemainingStocks = DriverStock::with('item')->where('driver_id',$request->driver_id)->get();
          foreach ($driverRemainingStocks as $driverStock) {
            $purchaseOrder = PurchaseOrderChild::where('item_id', $driverStock->item_id)->whereDate('created_at',$date)->orderBy('id', 'desc')->first();
            $driverStock->vat = $purchaseOrder ? $purchaseOrder->vat_in_per : 0;
            $driverStock->purchase_price_new = $purchaseOrder ? $purchaseOrder->unit_price : 0;
        }
      
    
           
              $vehicleExpense= VehicleExpense::with('vehicle')
            ->whereHas('vehicle', function ($query) use ($request,$date) {
                if ($request->has('driver_id') && !empty($request->driver_id)) {
                    $query->where('driver_id', $request->driver_id);
                }
                    $query->whereDate('date', $date);
              })
            ->get();
            $driverDailyExpenseSum = 0;
            $otherExpenseSum = 0;
            
            foreach ($vehicleExpense as $expense) {
                if ($expense->expense_type === 'Driver Daily Expense') {
                    $driverDailyExpenseSum += $expense->amount;
                } else {
                    $otherExpenseSum += $expense->amount;
                }
            }
            // $totalPaymentVoucher = Voucher::where('type','Payment')->whereDate('date',$date)->sum('paid_amount');
            // $totalEmployeePayroll = Payroll::whereDate('date',$date)->sum('total_salary_to_be_paid');
            // $totalEmployeeAdvance = EmployeeAdvance::whereDate('date',$date)->sum('advance_amount');
            $totalSales = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('total_bill');
            $totalDiscount = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('discount');
            $totalAfterDiscount = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('total_after_discount');
            $totalPaid = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('paid_amount');
            $totalRemaining = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('remaining');
            $totalProfit = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('profit');
          $driverDailyExpenseSum = $driverDailyExpenseSum;
          $otherExpenseSum = $otherExpenseSum;
          $driverReceiveStocks = $driverReceiveStocks;
          $driverRemainingStocks = $driverRemainingStocks;
        }else{
            $totalSales=0;
            $totalDiscount=0;
            $totalAfterDiscount=0;
            $totalPaid=0;
            $totalRemaining = 0;
            $totalProfit = 0;
            $driverDailyExpenseSum = 0;
            $otherExpenseSum = 0;
            $driverReceiveStocks = [];
            $driverRemainingStocks = [];
        }
        $drivers= Driver::get();
        return view('reports.driverReport',compact('totalSales','drivers','driverDailyExpenseSum','otherExpenseSum','driverReceiveStocks','driverRemainingStocks','totalDiscount','totalAfterDiscount','totalPaid','totalRemaining','totalProfit'))->with('oldDriverId', $request->driver_id)->with('oldDate', $request->date);
        
    }
    public function singleDriverReportPrint(Request $request)
    {
     
        $driverReports =[];
        $date = date('Y-m-d');

            $driverReceiveStocks = DriverStockChild::with('item')->whereDate('date',$date)->where('driver_id',$request->driver_id)->where('remarks','Received')->get();
            $total_purchase_of_single_driver =0;
           foreach ($driverReceiveStocks as $driverStock) {
            $purchaseOrder = PurchaseOrderChild::where('item_id', $driverStock->item_id)->whereDate('created_at',$date)->orderBy('id', 'desc')->first();
            $driverStock->purchase_price = $purchaseOrder ? $purchaseOrder->unit_price : 0;
            $driverStock->vat = $purchaseOrder ? $purchaseOrder->vat_in_per : 0;

            $purchasePriceExVAT =  $driverStock->purchase_price;
            $vatAmountPurchase = $purchasePriceExVAT * ($driverStock->vat / 100);
            $totalPurchasePriceWithVAT = ($purchasePriceExVAT + $vatAmountPurchase) * $driverStock->current_stock;
            $total_purchase_of_single_driver +=  $totalPurchasePriceWithVAT;
           }
            $driverRemainingStocks = DriverStock::with('item')->where('driver_id',$request->driver_id)->get();
            $total_remaining_of_single_driver =0;
          foreach ($driverRemainingStocks as $driverStock) {
            $purchaseOrder = PurchaseOrderChild::where('item_id', $driverStock->item_id)->whereDate('created_at',$date)->orderBy('id', 'desc')->first();
            $driverStock->vat = $purchaseOrder ? $purchaseOrder->vat_in_per : 0;
            $driverStock->purchase_price_new = $purchaseOrder ? $purchaseOrder->unit_price : 0;

            $purchasePriceExVAT =  $driverStock->purchase_price_new;
            $vatAmountPurchase = $purchasePriceExVAT * ($driverStock->vat / 100);
            $totalPurchasePriceWithVAT = ($purchasePriceExVAT + $vatAmountPurchase) * $driverStock->current_stock;
            $total_remaining_of_single_driver +=  $totalPurchasePriceWithVAT;
        }
              $vehicleExpense= VehicleExpense::with('vehicle')
            ->whereHas('vehicle', function ($query) use ($request,$date) {
                if (!empty($request->driver_id)) {
                    $query->where('driver_id', $request->driver_id);
                }
                    $query->whereDate('date', $date);
              })
            ->get();
            $driverDailyExpenseSum = 0;
            $otherExpenseSum = 0;
            
            foreach ($vehicleExpense as $expense) {
                if ($expense->expense_type === 'Driver Daily Expense') {
                    $driverDailyExpenseSum += $expense->amount;
                } else {
                    $otherExpenseSum += $expense->amount;
                }
            }
     
            $totalSales = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('total_bill');
            $totalDiscount = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('discount');
            $totalAfterDiscount = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('total_after_discount');
            $totalPaid = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('paid_amount');
            $totalRemaining = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('remaining');
            $totalProfit = Invoice::whereDate('date', $date)->where('driver_id',$request->driver_id)->sum('profit');
            
            $totalPaymentVoucher = Voucher::where('type','Payment')->whereDate('date',$date)->sum('paid_amount');
            $totalEmployeePayroll = Payroll::whereDate('date',$date)->sum('total_salary_to_be_paid');
            $totalEmployeeAdvance = EmployeeAdvance::whereDate('date',$date)->sum('advance_amount');
          $driverDailyExpenseSum = $driverDailyExpenseSum;
          $otherExpenseSum = $otherExpenseSum;
          $driverRemainingStocks = $driverRemainingStocks;
          $driver=Driver::where('id',$request->driver_id)->first();
          $driverName= $driver->name??'';
          $driverReports[] = [
            'driver_name' => $driver->name??'',
            'driver_daily_expense' => $driverDailyExpenseSum,
            'other_expense' => $otherExpenseSum,
            'total_purchase_of_single_driver' => $total_purchase_of_single_driver,
            'total_remaining_of_single_driver' => $total_remaining_of_single_driver,
            'total_sales' => $totalSales,
            'total_discount' => $totalDiscount,
            'total_after_discount' => $totalAfterDiscount,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'total_profit' => $totalProfit,
        ];
        

        $html = view('reports.singleDriverDailyReportPrint', [
            // 'invoices' => $invoices, 
            'driverReports' => $driverReports,
            'date' => $date,
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
        return response($mpdf->Output('singleDriverDailyReport-Date' . $date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
        
    }


    public function allDriverReportPrint(Request $request)
    {
        $drivers =Driver::get();
        $driverReports =[];
        $date = date('Y-m-d');
        foreach($drivers as $driver){
            $driverReceiveStocks = DriverStockChild::with('item')->whereDate('date',$date)->where('driver_id',$driver->id)->where('remarks','Received')->get();
            $total_purchase_of_single_driver =0;
           foreach ($driverReceiveStocks as $driverStock) {
            $purchaseOrder = PurchaseOrderChild::where('item_id', $driverStock->item_id)->whereDate('created_at',$date)->orderBy('id', 'desc')->first();
            $driverStock->purchase_price = $purchaseOrder ? $purchaseOrder->unit_price : 0;
            $driverStock->vat = $purchaseOrder ? $purchaseOrder->vat_in_per : 0;

            $purchasePriceExVAT =  $driverStock->purchase_price;
            $vatAmountPurchase = $purchasePriceExVAT * ($driverStock->vat / 100);
            $totalPurchasePriceWithVAT = ($purchasePriceExVAT + $vatAmountPurchase) * $driverStock->current_stock;
            $total_purchase_of_single_driver +=  $totalPurchasePriceWithVAT;
           }
            $driverRemainingStocks = DriverStock::with('item')->where('driver_id',$driver->id)->get();
            $total_remaining_of_single_driver =0;
          foreach ($driverRemainingStocks as $driverStock) {
            $purchaseOrder = PurchaseOrderChild::where('item_id', $driverStock->item_id)->whereDate('created_at',$date)->orderBy('id', 'desc')->first();
            $driverStock->vat = $purchaseOrder ? $purchaseOrder->vat_in_per : 0;
            $driverStock->purchase_price_new = $purchaseOrder ? $purchaseOrder->unit_price : 0;

            $purchasePriceExVAT =  $driverStock->purchase_price_new;
            $vatAmountPurchase = $purchasePriceExVAT * ($driverStock->vat / 100);
            $totalPurchasePriceWithVAT = ($purchasePriceExVAT + $vatAmountPurchase) * $driverStock->current_stock;
            $total_remaining_of_single_driver +=  $totalPurchasePriceWithVAT;
        }
              $vehicleExpense= VehicleExpense::with('vehicle')
            ->whereHas('vehicle', function ($query) use ($request,$date,$driver) {
                if (!empty($driver->id)) {
                    $query->where('driver_id', $driver->id);
                }
                    $query->whereDate('date', $date);
              })
            ->get();
            $driverDailyExpenseSum = 0;
            $otherExpenseSum = 0;
            
            foreach ($vehicleExpense as $expense) {
                if ($expense->expense_type === 'Driver Daily Expense') {
                    $driverDailyExpenseSum += $expense->amount;
                } else {
                    $otherExpenseSum += $expense->amount;
                }
            }
     
            $totalSales = Invoice::whereDate('date', $date)->where('driver_id',$driver->id)->sum('total_bill');
            $totalDiscount = Invoice::whereDate('date', $date)->where('driver_id',$driver->id)->sum('discount');
            $totalAfterDiscount = Invoice::whereDate('date', $date)->where('driver_id',$driver->id)->sum('total_after_discount');
            $totalPaid = Invoice::whereDate('date', $date)->where('driver_id',$driver->id)->sum('paid_amount');
            $totalRemaining = Invoice::whereDate('date', $date)->where('driver_id',$driver->id)->sum('remaining');
            $totalProfit = Invoice::whereDate('date', $date)->where('driver_id',$driver->id)->sum('profit');
          $driverDailyExpenseSum = $driverDailyExpenseSum;
          $otherExpenseSum = $otherExpenseSum;
          $driverRemainingStocks = $driverRemainingStocks;
          $driverName= $driver->name;
          $driverReports[] = [
            'driver_name' => $driver->name,
            'driver_daily_expense' => $driverDailyExpenseSum,
            'other_expense' => $otherExpenseSum,
            'total_purchase_of_single_driver' => $total_purchase_of_single_driver,
            'total_remaining_of_single_driver' => $total_remaining_of_single_driver,
            'total_sales' => $totalSales,
            'total_discount' => $totalDiscount,
            'total_after_discount' => $totalAfterDiscount,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'total_profit' => $totalProfit,
        ];
        }

       
            $totalPaymentVoucher = Voucher::where('type','Payment')->whereDate('date',$date)->sum('paid_amount');
            $totalEmployeePayroll = Payroll::whereDate('date',$date)->sum('total_salary_to_be_paid');
            $totalEmployeeAdvance = EmployeeAdvance::whereDate('date',$date)->sum('advance_amount');
            // $driverReports[] = [
            //     'totalPaymentVoucher' => $totalPaymentVoucher,
            //     'totalEmployeePayroll' => $totalEmployeePayroll,
            //     'totalEmployeeAdvance' => $totalEmployeeAdvance,
            // ];

        // Convert your view into HTML
        $html = view('reports.allDriverDailyReportPrint', [
            // 'invoices' => $invoices, 
            'driverReports' => $driverReports,
            'totalEmployeePayroll' => $totalEmployeePayroll,
            'date' => $date,
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
        return response($mpdf->Output('allDriverDailyReport-Date' . $date . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cashFlow(Request $request)
    {
        // return $request;
            if($request->date_from && $request->date_to){
              $date_from =$request->date_from;
              $date_to = $request->date_to;
            }else{
                $date_from = date('Y-m-d');
                $date_to = date('Y-m-d');
            }
                $cashFlow = ShopLedger::
                whereBetween('date', [$date_from, $date_to])->orderBy('id', 'desc')
              ->get();
            return view('reports.cashFlow',compact('cashFlow'))
            ->with('oldDateFrom', $date_from)
            ->with('oldDateTo', $date_to);
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
}
