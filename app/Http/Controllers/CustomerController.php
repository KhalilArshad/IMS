<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionSummary;
use App\Models\Driver;
use App\Models\DriverCustomer;
use App\Models\Reminder;
use App\Models\Setting;
use App\Models\ShopLedger;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        return view('customer.list', compact('customers'));
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
        $reminderDays = (int) $request->reminder_days;
        $isUpdate     = !empty($request->update_customer_id);

        // one transaction: customer + opening balance + reminder are saved together or not at all
        $customerId = DB::transaction(function () use ($request, $reminderDays, $isUpdate) {

            if ($isUpdate) {

                // ---------- UPDATE EXISTING CUSTOMER ----------
                $customerId = $request->update_customer_id;

                Customer::where('id', $customerId)->update([
                    'name'             => $request->name,
                    'phone_no'         => $request->phone_no,
                    'email'            => $request->email,
                    'previous_balance' => $request->opening_balance,
                    'reminder_days'    => $request->reminder_days,
                ]);

                // exists() = light query (no need to load the full row)
                if (!CustomerTransactionSummary::where('customer_id', $customerId)->exists()) {
                    $this->saveOpeningBalance($customerId, $request->opening_balance);
                }

                // ---------- REMINDER (update the pending one, no duplicates) ----------
                if ($reminderDays > 0) {
                    Reminder::updateOrCreate(
                        [
                            'customer_id' => $customerId,
                            'invoice_id'  => null,
                            'status'      => 0,
                        ],
                        [
                            'reminder_date' => \Carbon\Carbon::today()->addDays($reminderDays)->format('Y-m-d'),
                            'message'       => 'Customer reminder set for ' . $reminderDays . ' days.',
                        ]
                    );
                } else {
                    // reminder days removed -> delete the old pending reminder
                    Reminder::where('customer_id', $customerId)
                        ->whereNull('invoice_id')
                        ->where('status', 0)
                        ->delete();
                }
            } else {

                // ---------- CREATE NEW CUSTOMER ----------
                $customer = new Customer();
                $customer->name             = $request->name;
                $customer->phone_no         = $request->phone_no;
                $customer->email            = $request->email;
                $customer->previous_balance = $request->opening_balance;
                $customer->reminder_days    = $request->reminder_days;
                $customer->save();

                $customerId = $customer->id;

                if (!empty($request->opening_balance)) {
                    $this->saveOpeningBalance($customerId, $request->opening_balance);
                }

                // new customer has no old reminder, so a direct insert is enough (no extra SELECT)
                if ($reminderDays > 0) {
                    Reminder::create([
                        'customer_id'   => $customerId,
                        'invoice_id'    => null,
                        'reminder_date' => \Carbon\Carbon::today()->addDays($reminderDays)->format('Y-m-d'),
                        'message'       => 'Customer reminder set for ' . $reminderDays . ' days.',
                        'status'        => 0, // 0 = pending
                    ]);
                }
            }

            return $customerId;
        });

        return response()->json(['success' => 'Customer saved successfully']);
    }

    /**
     * Opening balance row (was repeated twice in store, now one place)
     */
    private function saveOpeningBalance($customerId, $openingBalance)
    {
        $customerTran = new CustomerTransactionSummary();
        $customerTran->customer_id     = $customerId;
        $customerTran->today_bill      = 0;
        $customerTran->today_remaining = 0;
        $customerTran->old_remaining   = $openingBalance;
        $customerTran->old_received    = 0;
        $customerTran->net_remaining   = $openingBalance;
        $customerTran->description     = 'Customer Opening Balance';
        $customerTran->date            = date('Y-m-d');
        $customerTran->save();
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


    public function customerLedger()
    {
        $customers = Customer::select('id', 'name')->get();
        $customer_id = '';
        return view('customer.customersLedger', compact('customers', 'customer_id'));
    }
    public function getCustomerLedger(Request $request)
    {
        if (isset($request->from) && isset($request->to)) {
            $from = $request->get('from');
            $to = $request->get('to');
        } else {
            $from = CustomerLedger::select('date')->orderby('id', 'ASC')->first();
            $to = CustomerLedger::select('date')->orderby('id', 'DESC')->first();
            if ($from) {
                $from = $from->date;
                $to = $to->date;
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
        }
        // return $to;
        $customers = Customer::select('id', 'name')->get();
        $customer_id = $request->get("customer_id");
        $customer = Customer::select('id', 'name', 'previous_balance')->where('id', $customer_id)->first();
        $customer_name = $customer->name;
        $balance = $customer->previous_balance;

        $customerLedgerArray = CustomerLedger::with('customer', 'invoice')->where('customer_id', $customer_id)
            ->whereBetween('date', [$from, $to])
            ->get();
        $total_remaining = CustomerLedger::where('customer_id', $customer_id)
            ->whereBetween('date', [$from, $to])->orderBy('id', 'DESC')->select('id', 'balance')->first();
        $remaining = $total_remaining->balance ?? 0;
        $flag = "found";
        return view('customer.customersLedger', compact('customers', 'customer_id', 'customerLedgerArray', 'from', 'to', 'flag', 'remaining'));
    }

    public function getCustomerData(Request $request)
    {
        $id = $request->id;
        $customer = Customer::select('id', 'name', 'phone_no', 'email', 'previous_balance', 'reminder_days')->where('id', $id)->first();
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
        $customer = Customer::where('id', $request->id)->select('id', 'name', 'previous_balance')->first();
        $system_date = Setting::select('id', 'system_date')->first();
        $system_date = \Carbon\Carbon::parse($system_date->system_date)->format('Y-m-d');
        return view('customer.customerReceivable', compact('customer', 'system_date'));
    }

    public function SaveCustomerReceivable(Request $request)
    {
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

                $date = $request->date;
                $description = 'Payment Received Against Customer';
                $customerLedger = new CustomerLedger();
                $customerLedger->customer_id = $request->customer_id;
                $customerLedger->credit = $request->paid_amount;
                $customerLedger->debit = 0;
                $customerLedger->balance = $updatedCustomerBalance;
                $customerLedger->date = $date;
                $customerLedger->description = $description;
                $customerLedger->save();

                $voucher = new Voucher();
                $voucher->customer_id = $request->customer_id;
                $voucher->previous_balance = $customerPreviousBalance;
                $voucher->type = 'Receipt';
                $voucher->paid_amount =  $request->paid_amount;
                $voucher->remaining = $updatedCustomerBalance;
                $voucher->date = $date;
                $voucher->save();
                if ($request->paid_amount > 0) {
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
                    $shopLedger->customer_id = $request->customer_id;
                    $shopLedger->credit = $credit;
                    $shopLedger->debit = $debit;
                    $shopLedger->balance = $shopBalance;
                    $shopLedger->date = $date;
                    $shopLedger->description = $description;
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
    public function Reminders(Request $request)
    {
        $drivers = Driver::orderBy('name')->get(['id', 'name']);

        $query = Reminder::with(['customer:id,name', 'invoice:id,invoice_no,date'])
            ->where('status', 0);

        // Driver filter
        if ($request->filled('driver_id')) {
            $driverId = $request->driver_id;

            // Get customer IDs linked to this driver
            $customerIds = DriverCustomer::where('driver_id', $driverId)
                ->pluck('customer_id')
                ->unique()
                ->toArray();

            // Filter reminders by those customer IDs
            $query->whereIn('customer_id', $customerIds);
        }

        $reminders = $query->orderBy('id', 'desc')->get();

        return view('customer.reminder', compact('reminders', 'drivers'));
    }
    public function clearAll()
    {
        Reminder::where('status', 0)->update([
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All reminders cleared successfully.'
        ]);
    }
    public function printUnread()
    {
        $reminders = Reminder::with(['customer', 'invoice'])
            ->where('status', 0)
            ->get();

        $html = view('customer.reminder_pdf', compact('reminders'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P'
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('unread-reminders.pdf', 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }
    public function sendTodayReminderEmail()
    {
        $today = Carbon::today();
        $cacheKey = 'reminder-email-sent-' . $today->format('Y-m-d');

        // already emailed today
        if (Cache::has($cacheKey)) {
            return response()->json(['success' => true, 'message' => 'Reminder email already sent today.']);
        }

        $adminEmail = config('mail.admin_email');
        if (empty($adminEmail)) {
            Log::warning('Reminder email not sent: ADMIN_EMAIL is missing in .env');
            return response()->json(['success' => false, 'message' => 'ADMIN_EMAIL is missing in .env'], 500);
        }

        // reminders whose date is today (only pending) with customer + invoice loaded
        $reminders = Reminder::with([
            'customer:id,name,phone_no,previous_balance',
            'invoice:id,invoice_no,date',
        ])
            ->whereDate('reminder_date', $today)
            ->where('status', 0)            // 0 = pending (not cleared yet)
            ->get();

        if ($reminders->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'No reminders for today.']);
        }

        // reminders grouped by customer_id
        $remindersByCustomer = $reminders->groupBy('customer_id');

        // driver_customers rows of these customers (one query for all customers)
        $driverCustomers = DriverCustomer::whereIn('customer_id', $remindersByCustomer->keys())->get();

        // drivers of those rows (one query for all drivers)
        $drivers = Driver::whereIn('id', $driverCustomers->pluck('driver_id')->unique())
            ->orderBy('name')
            ->get();

        // build one table per driver
        $tables = '';
        $customersWithDriver = [];

        foreach ($drivers as $driver) {
            $customerIds = $driverCustomers->where('driver_id', $driver->id)->pluck('customer_id')->unique();

            $driverReminders = collect();
            foreach ($customerIds as $customerId) {
                $driverReminders = $driverReminders->merge($remindersByCustomer->get($customerId, collect()));
                $customersWithDriver[$customerId] = true;
            }

            $tables .= $this->buildReminderTable('Driver: ' . ($driver->name ?? ('#' . $driver->id)), $driverReminders);
        }

        // customers with a reminder but no driver assigned
        $noDriverReminders = $reminders->reject(function ($reminder) use ($customersWithDriver) {
            return isset($customersWithDriver[$reminder->customer_id]);
        });
        if ($noDriverReminders->isNotEmpty()) {
            $tables .= $this->buildReminderTable('No Driver Assigned', $noDriverReminders);
        }

        $html = '<h3>Reminders for ' . $today->format('d M Y') . '</h3>' . $tables;

        try {
            Mail::html($html, function ($message) use ($adminEmail, $reminders, $today) {
                $message->to($adminEmail)
                    ->subject('Today Reminders (' . $reminders->count() . ') - ' . $today->format('d M Y'));
            });

            // remember until end of today so it is not sent again
            Cache::put($cacheKey, true, $today->copy()->endOfDay());
        } catch (\Exception $e) {
            Log::error('Reminder email failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Email failed: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Reminder email sent to admin.']);
    }

    /**
     * One table (title + customers' reminders) for the email
     */
    private function buildReminderTable($title, $reminders)
    {
        $rows = '';
        foreach ($reminders->values() as $key => $reminder) {
            $rows .= '<tr>'
                . '<td>' . ($key + 1) . '</td>'
                . '<td>' . e($reminder->customer->name ?? '') . '</td>'
                . '<td>' . e($reminder->customer->phone_no ?? '-') . '</td>'
                . '<td>' . e($reminder->customer->previous_balance ?? '-') . '</td>'
                . '<td>' . e(Carbon::parse($reminder->reminder_date)->format('d M Y')) . '</td>'
                . '<td>' . e($reminder->message) . '</td>'
                . '</tr>';
        }

        return '<h4 style="margin-bottom:4px;">' . e($title) . '</h4>'
            . '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;margin-bottom:18px;">'
            . '<tr>'
            . '<th>#</th>'
            . '<th>Customer</th>'
            . '<th>Phone</th>'
            . '<th>Previous Balance</th>'
            . '<th>Reminder Date</th>'
            . '<th>Message</th>'
            . '</tr>'
            . $rows
            . '</table>';
    }
}
