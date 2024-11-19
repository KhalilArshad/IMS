<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <center>
    <div class="sidebar-header">
        <div>
            <img src="assets/images/logo-img.png" class="logo-icon" alt="logo icon" style="
    margin-left: 4rem;
    width: 100px; height: 50px;
">

        </div>

        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    </center>
    <!--navigation-->
    <ul class="metismenu" id="menu">



        <li>
            <a href="{{ url('dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-users"></i>
                </div>
                <div class="menu-title">Supplier</div>
            </a>
            <ul>
                <li> <a href="{{ url('supplier-list') }}" ><i class="bx bx-list-plus"></i>List</a>
                </li>
                    <li> <a href="{{ url('supplier-ledger') }}" ><i class="bx bx-list-ul"></i>Supplier Ledger</a>
                </li>
                    <li> <a href="{{ url('payment-voucher') }}" ><i class="bx bx-list-ul"></i>Payment Voucher</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-users"></i>
                </div>
                <div class="menu-title">Customer</div>
            </a>
            <ul>
                <li> <a href="{{ url('customer-list') }}" ><i class="bx bx-list-plus"></i>List</a>
                </li>
                    <li> <a href="{{url('customer-ledger')}}" ><i class="bx bx-list-ul"></i>Customer Ledger</a>
                </li>
                <li> <a href="{{ url('Receipt-voucher') }}" ><i class="bx bx-list-ul"></i>Receipt  Voucher</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-cart-full"></i>
                </div>
                <div class="menu-title">Purchase Order</div>
            </a>
            <ul>
                <li> <a href="{{url('create-purchase-order')}}" ><i class="bx bx-list-plus"></i>Add Purchase Order</a>
                </li>
                    <li> <a href="{{url('purchase-order-list')}}" ><i class="bx bx-list-ul"></i>Purchase Order List</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-cart-full"></i>
                </div>
                <div class="menu-title">Sales</div>
            </a>
            <ul>
                <li> <a href="{{url('stockAssignTo-driver')}}" ><i class="bx bx-list-plus"></i>Stock Assign To Drivers</a>
                </li>
                <li> <a href="{{url('driverStock-history')}}" ><i class="bx bx-list-plus"></i>Drivers Stock History</a>
                </li>
                <li> <a href="{{url('create-invoice')}}" ><i class="bx bx-list-plus"></i>Create Invoice</a>
                </li>
                    <li> <a href="{{url('invoice-list')}}" ><i class="bx bx-list-ul"></i>Invoice List</a>
                </li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-database"></i>
                </div>
                <div class="menu-title">Stock</div>
            </a>
            <ul>
                <li> <a href="{{ url('stock-list') }}" ><i class="bx bx-list-plus"></i>Stock List</a>
                <!-- </li>
                    <li> <a href="#" ><i class="bx bx-list-ul"></i>Stock transections</a>
                </li> -->
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-caravan"></i>
                </div>
                <div class="menu-title">Vehicle/Driver</div>
            </a>
            <ul>
        <!-- </li> -->
                <li> <a href="{{ url('add-driver') }}" ><i class="bx bx-list-ul"></i>Add Driver</a>
                </li>
                <li> <a href="{{ url('add-vehicle') }}" ><i class="bx bx-list-plus"></i>Add Vehicle</a>
                </li>
                    <li> <a href="{{ url('add-vehicle-expense') }}" ><i class="bx bx-list-ul"></i>Vehicle Expense</a>
                </li>
                    <li> <a href="{{ url('add-vehicle-installment') }}" ><i class="bx bx-list-ul"></i>Vehicle Installment</a>
                </li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-dropbox"></i>
                </div>
                <div class="menu-title">Products</div>
            </a>
            <ul>
                <li>
                    <a href="{{ url('add-unit') }}"><i class="bx bx-list-plus"></i>Unit List</a>
                </li>
                <li>
                    <a href="{{ url('add-items') }}"><i class="bx bx-list-plus"></i>Items List</a>
                </li>
                
            </ul>
        </li>



        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-users"></i>
                </div>
                <div class="menu-title">Employee</div>
            </a>
            <ul>
                <li> <a href="{{url('addEmployee')}}" ><i class="bx bx-list-plus"></i>Add Employee</a>
                </li>
                <li> <a href="{{url('employees-list')}}" ><i class="bx bx-list-ul"></i>Employee List</a>
                </li>
                <li> <a href="{{url('AddEmployee-advance')}}" ><i class="bx bx-list-ul"></i>Advance/Remaining Logs</a>
                </li>
                <li> <a href="{{url('get-payroll')}}" ><i class="bx bx-list-ul"></i>Payroll</a>
                </li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-layers"></i>
                </div>
                <div class="menu-title">Reports</div>
            </a>
            <ul>
        <!-- </li> -->
                <li> <a href="{{url('driverDaily-saleReport')}}" ><i class="bx bx-list-ul"></i>Daily Driver Sale Report</a>
                </li>
                <li> <a href="{{url('customerDetailsReport')}}" ><i class="bx bx-list-ul"></i>Customer Details Report</a>
                </li>
                <li> <a href="{{url('allCustomerRemainingReport')}}" ><i class="bx bx-list-ul"></i>All Customer Remaining Report</a>
                </li>
                <li> <a href="{{url('driverDailyReport')}}" ><i class="bx bx-list-ul"></i>Driver Daily Report</a>
                </li>
                <li> <a href="{{url('driverReport')}}" ><i class="bx bx-list-ul"></i>Driver Sale Purchase & Profit</a>
                </li>
                <li> <a href="{{url('cash-flow')}}" ><i class="bx bx-list-plus"></i>Cash Flow</a>
                </li>
            </ul>
        </li>


            <br>
            <br>
            <br>
            <br>


            <li>
                <a  href="logout">
                    <div class="parent-icon"><i class="bx bx-money"></i>
                    </div>
                    <div class="menu-title" id="dis_comission" style="color: red;">LOG OUT</div>
                </a>
            </li>




    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
