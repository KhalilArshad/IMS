<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Services\Settings;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/us-clear', function() {

    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "Cleared!";
});

Route::get('setTheme', [Settings::class, 'setTheme']);
Route::get('logout', [AuthController::class, 'logout']);






Route::middleware([AlreadyLoggedIn::class])->group(function () {


    Route::get('/authentication-signup', function () {
        return view('authentication.authentication-signup');
    });


    Route::post('register', [AuthController::class, 'store']);

    Route::get('/', [AuthController::class, 'loginForm'])->name('login');
    Route::get('authentication-forgot-password', [AuthController::class, 'passwordForgotForm'])->name('authentication-forgot-password');
    Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('forgot.password.link');
    Route::get('/password-reset-{token}', [AuthController::class, 'showResetForm'])->name('reset.password.form');
    Route::post('password-reset', [AuthController::class, 'passwordRest'])->name('passowrd.rest');
    Route::get('/signup', [AuthController::class, 'register']);
    Route::post('/authentication-signup', [AuthController::class, 'login']);
    Route::post('adminlogin', [AuthController::class, 'adminlogin']);
    Route::post('register', [AuthController::class, 'store']);
});





//------------------------------------Common routes-----------------------------------
Route::middleware([NotLoggedIn::class])->group(function () {



    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin-dashboard');




    Route::get('/user-profile', [UserController::class, 'edit']);

    Route::post('/updateUser', [UserController::class, 'update']);
     Route::get('/deleteUser', [UserController::class, 'destroy']);

});

//------------------------------------Common routes-----------------------------------
Route::middleware([CommonRoutes::class])->group(function () {

    Route::get('dashboard', function () {
        return view('index');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');








});


//---------------------------------------------Admin Routes -------------------------------------
Route::middleware([Admin::class])->group(function () {

    Route::get('showCategories', [CategoryController::class, 'index']);
    Route::get('storeCategories', [CategoryController::class, 'store']);
    
 

    Route::post('userRegister', [UserController::class, 'store']);

     Route::get('create-employee-form', [DashboardController::class, 'add'])->name('create-employee-form');

     Route::get('create-employee-form', [DashboardController::class, 'add'])->name('create-employee-form');

     Route::post('insert-employee',[DashboardController::class,'insertemployee'])->name('insert-employee');



     Route::get('list-employee', [DashboardController::class, 'employeeList'])->name('list-employee');
    Route::get('employee-edit', [DashboardController::class, 'employeeEdit'])->name('employee-edit');

    Route::post('/update-employee/{id}',[DashboardController::class,'employeeupdate'])->name('update-employee');

    Route::get('/employee-delete/{id}',[DashboardController::class,'employeedelete'])->name('employee-delete');
    Route::get('list-contact', [DashboardController::class, 'contactList'])->name('list-contact');


});

// supplier routs
Route::get('supplier-list', [SupplierController::class, 'index'])->name('supplier-list');
Route::post('supplier-save', [SupplierController::class, 'store'])->name('supplier-save');
Route::post('get-supplier-data', [SupplierController::class, 'getSupplierData'])->name('get-supplier-data');
Route::get('supplier-delete/{id}', [SupplierController::class, 'destroy'])->name('supplier-delete');
Route::get('supplier-ledger', [SupplierController::class, 'supplierLedger'])->name('supplier-ledger');
Route::post('getSupplierLedger', [SupplierController::class, 'getSupplierLedger'])->name('getSupplierLedger');
Route::get('supplier-payable', [SupplierController::class, 'supplierPayAbleCreate'])->name('supplier-payable');
Route::post('SaveSupplierPayable', [SupplierController::class, 'SaveSupplierPayable'])->name('SaveSupplierPayable');
// customers route
Route::get('customer-list', [CustomerController::class, 'index'])->name('customer-list');
Route::post('customer-save', [CustomerController::class, 'store'])->name('customer-save');
Route::post('get-customer-data', [CustomerController::class, 'getCustomerData'])->name('get-customer-data');
Route::get('customer-delete/{id}', [CustomerController::class, 'destroy'])->name('customer-delete');
Route::get('customer-ledger', [CustomerController::class, 'customerLedger'])->name('customer-ledger');
Route::post('getCustomerLedger', [CustomerController::class, 'getCustomerLedger'])->name('getCustomerLedger');
Route::get('customer-receivable', [CustomerController::class, 'customerReceivableCreate'])->name('customer-receivable');
Route::post('SaveCustomerReceivable', [CustomerController::class, 'SaveCustomerReceivable'])->name('SaveCustomerReceivable');

//Units and Item routes
Route::get('add-unit', [ProductController::class, 'index'])->name('add-unit');
Route::post('saveUnits', [ProductController::class, 'store'])->name('saveUnits');
Route::get('add-items', [ProductController::class, 'addItem'])->name('add-items');
Route::post('saveItems', [ProductController::class, 'saveItem'])->name('saveItems');
//purchase order route
Route::get('get-po-no', [PurchaseOrderController::class, 'getPoNo'])->name('get-po-no');
Route::get('create-purchase-order', [PurchaseOrderController::class, 'index'])->name('create-purchase-order');
Route::post('savePurchaseOrder', [PurchaseOrderController::class, 'store'])->name('savePurchaseOrder');
Route::get('purchase-order-list', [PurchaseOrderController::class, 'list'])->name('purchase-order-list');
Route::get('view-purchaseOrder/{id}', [PurchaseOrderController::class, 'viewPurchaseOrder'])->name('view-purchaseOrder');
Route::get('purchase-order-edit', [PurchaseOrderController::class, 'edit'])->name('purchase-order-edit');
Route::post('getItemUnit', [PurchaseOrderController::class, 'getItemUnit'])->name('getItemUnit');
Route::get('/receive-purchase-order/{id}', [PurchaseOrderController::class, 'receivePo'])->name('receivePo');
Route::get('/delete-po-item/{id}', [PurchaseOrderController::class, 'deletePoItem'])->name('deletePoItem');
Route::get('purchase-order-delete/{id}', [PurchaseOrderController::class, 'deletePurchaseOrder'])->name('purchase-order-delete');
Route::post('updatePurchaseOrder', [PurchaseOrderController::class, 'updatePurchaseOrder'])->name('updatePurchaseOrder');
//stock route
Route::get('stock-list', [StockController::class, 'index'])->name('stock-list');

// sales invoice route
Route::get('create-invoice', [InvoiceController::class, 'index'])->name('create-invoice');
Route::post('saveInvoice', [InvoiceController::class, 'store'])->name('saveInvoice');
Route::get('invoice-list', [InvoiceController::class, 'list'])->name('invoice-list');
Route::get('view-invoice/{id}', [InvoiceController::class, 'viewInvoice'])->name('view-invoice');
//stock Assign to driver and driver reports route
Route::get('stockAssignTo-driver', [InvoiceController::class, 'createStockAssignToDriver'])->name('stockAssignTo-driver');
Route::post('saveStockAssignToDriver', [InvoiceController::class, 'saveStockAssignToDriver'])->name('saveStockAssignToDriver');
Route::get('driverStock-history', [InvoiceController::class, 'driverStockHistory'])->name('driverStock-history');
Route::post('getDriverCustomer', [InvoiceController::class, 'getDriverCustomer'])->name('getDriverCustomer');
Route::post('getItemUnitForSale', [InvoiceController::class, 'getItemUnitForSale'])->name('getItemUnitForSale');
//vehicle and driver route
Route::get('add-driver',[VehicleController::class,'addDriver'])->name('add-driver');
Route::post('save-driver',[VehicleController::class,'saveDriver'])->name('save-driver');
Route::post('get-driver-data',[VehicleController::class,'getDriverData'])->name('get-driver-data');
Route::get('driver-delete/{id}', [VehicleController::class, 'destroy'])->name('driver-delete');
Route::get('add-vehicle',[VehicleController::class,'addVehicle'])->name('add-vehicle');
Route::post('vehicle-save',[VehicleController::class,'saveVehicle'])->name('vehicle-save');
Route::get('vehicle-delete/{id}', [VehicleController::class, 'vehicleDelete'])->name('vehicle-delete');
Route::post('get-vehicle-data',[VehicleController::class,'getVehicleData'])->name('get-vehicle-data');
Route::get('add-vehicle-expense',[VehicleController::class,'addVehicleExpense'])->name('add-vehicle-expense');
Route::post('save-vehicle-expense',[VehicleController::class,'saveVehicleExpense'])->name('save-vehicle-expense');
Route::get('vehicle-expense-delete/{id}', [VehicleController::class, 'vehicleExpenseDelete'])->name('vehicle-expense-delete');
Route::post('get-vehicleExpense-data',[VehicleController::class,'getVehicleExpenseData'])->name('get-vehicleExpense-data');

//report route
Route::post('get-dashboard-data',[DashboardController::class,'index'])->name('get-dashboard-data');

//Employee route
Route::get('create-employee-form', [DashboardController::class, 'add'])->name('create-employee-form');
Route::get('create-employee-form', [DashboardController::class, 'add'])->name('create-employee-form');
Route::post('insert-employee',[DashboardController::class,'insertemployee'])->name('insert-employee');
Route::get('list-employee', [DashboardController::class, 'employeeList'])->name('list-employee');
Route::get('employee-edit', [DashboardController::class, 'employeeEdit'])->name('employee-edit');
Route::post('/update-employee/{id}',[DashboardController::class,'employeeupdate'])->name('update-employee');
Route::get('/employee-delete/{id}',[DashboardController::class,'employeedelete'])->name('employee-delete');

// 
Route::get('addEmployee', [EmployeeController::class, 'addEmployee'])->name('addEmployee');
Route::post('saveEmployee', [EmployeeController::class, 'saveEmployee'])->name('saveEmployee');
Route::get('getEmployees', [EmployeeController::class, 'getEmployees'])->name('getEmployees');
Route::get('viewEmployees', [EmployeeController::class, 'viewEmployees'])->name('viewEmployees');
Route::post('updateEmployee', [EmployeeController::class, 'updateEmployee'])->name('updateEmployee');
Route::get('deleteEmployee', [EmployeeController::class, 'deleteEmployee'])->name('deleteEmployee');
Route::post('employeeStatusChange', [EmployeeController::class, 'employeeStatusChange']);
Route::get('searchEmployee', [EmployeeController::class, 'searchEmployeeByajax'])->name('search-employee-ajax');

