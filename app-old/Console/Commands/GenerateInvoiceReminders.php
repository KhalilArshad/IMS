<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Reminder;

class GenerateInvoiceReminders extends Command
{
    protected $signature = 'reminders:generate-invoice';
    protected $description = 'Generate invoice reminders based on customer reminder days';

    public function handle()
    {
        $today = Carbon::today();

        $customers = Customer::whereNotNull('reminder_days')
            ->where('reminder_days', '>', 0)
            ->get();

        foreach ($customers as $customer) {

            $lastInvoice = Invoice::where('customer_id', $customer->id)
                ->latest('date')
                ->first();

            if (!$lastInvoice) {
                continue;
            }

            $reminderDate = Carbon::parse($lastInvoice->date)
                ->addDays($customer->reminder_days + 1);

            if ($reminderDate->isSameDay($today)) {

                // Prevent duplicate reminders
                $exists = Reminder::where('customer_id', $customer->id)
                    ->whereDate('reminder_date', $today)
                    ->exists();

                if (!$exists) {
                    Reminder::create([
                        'customer_id'   => $customer->id,
                        'invoice_id'    => $lastInvoice->id,
                        'reminder_date' => $today,
                        'message'       => 'Invoice reminder for customer',
                        'status'        => '0',
                    ]);
                }
            }
        }

        $this->info('Invoice reminders generated successfully.');
    }
}
