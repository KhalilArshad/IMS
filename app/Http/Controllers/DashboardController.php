<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Team;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Faq;
use App\Models\History;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Invoice;
use App\Models\InvoiceChild;
use App\Models\Payroll;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\VehicleExpense;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date_filter;
        $startDate = '';
        $endDate = '';

        switch ($date) {
            case 'Today':
                $startDate = date('Y-m-d 00:00:00');  // Start of today
                $endDate = date('Y-m-d 23:59:59');    // End of today
                break;
            case 'This Week':
                $startDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
                $endDate = date('Y-m-d 23:59:59', strtotime('sunday this week'));
                break;
            case 'This Month':
                $startDate = date('Y-m-01 00:00:00'); // Start of this month
                $endDate = date('Y-m-t 23:59:59');    // Last moment of this month
                break;
            case 'This Year':
                $startDate = date('Y-01-01 00:00:00'); // Start of this year
                $endDate = date('Y-12-31 23:59:59');   // Last moment of this year
                break;
            default:
                $startDate = date('Y-m-d 00:00:00'); // Default to start of today
                $endDate = date('Y-m-d 23:59:59');   // Default to end of today
                $date = 'Today';
                break;
        }

        // Fetching data with date and time in consideration
        $totalSales = Invoice::whereBetween('date', [$startDate, $endDate])->sum('total_bill');
        $totalPurchase = PurchaseOrder::whereBetween('date', [$startDate, $endDate])->sum('total_bill');
        $vehicleExpense = VehicleExpense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $totalProfit = InvoiceChild::whereBetween('created_at', [$startDate, $endDate])->sum('profit');
        $totalEmployeePayroll = Payroll::whereBetween('date', [$startDate, $endDate])->sum('total_salary_to_be_paid');
        $supplierRemaining = Supplier::sum('previous_balance');
        $customerPayable = Customer::sum('previous_balance');

        return view('index', compact('totalSales', 'totalPurchase', 'vehicleExpense', 'totalProfit','supplierRemaining','customerPayable', 'date','totalEmployeePayroll'));

    }
    public function create()
    {
        return view('admin.add_project');
    }

}

