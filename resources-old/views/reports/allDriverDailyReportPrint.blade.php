<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Driver Report</title>
    {{-- Custom Style --}}
    <style>
      .container {
            width: 700px;
            /* margin: 0 auto; */
            margin-left: -20px;
            background-color: #a8adbf; 
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            color: #753e30;
        }
      .bill-details {
            width: 700px;
            height: 100px;
            /* margin: 0 auto; */
            margin-left: -20px;
            background-color: #a8adbf; 
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            color: #753e30;
        }
        

        .label {
            margin: 0;
            font-size: 14px;
            padding: 2px 0;
            color: blue;
        }
        .value {
            margin: 0;
            font-size: 16px;
            padding: 2px 0;
            color: blue;
        }

        .label-background {
            background-color: #a8adbf;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
        .company-details {
            float: left;
            width: 40%;
            text-align: left;
            margin-top: -10;
        }

       .company-logo {
            display: inline-block;
            margin: 0 auto;
            width: 50px !important;
            height: 80px;
            margin-top: 20;
        }

        .company-details-arabic {
            float: right;
            text-align: right;
            width: 40%;
            margin-top: -100;
        }
        strong {
        font-weight: bold;
        font-size: 100%; /* Makes the text larger */
        }
        /* bill details */
        .pdf-bill-details {
            margin: 10px 0px 10px 530px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: -15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #a8adbf;
            color: white;
        }
        .header-table th {
            width: 50%; /* Ensures equal distribution */
            text-align: left;
        }
    </style>
    {{-- /Custom Style --}}
</head>

<body>
    {{-- header --}}
    <div class="container">
        <div class="company-details">
            <p>
            <strong>Iman Yahya Bin Muhammad Al</strong> <br>
            <strong>Mufarreh Foundation General</strong> <br>
            <strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contracting</strong> <br>
            &nbsp;  &nbsp; الدمام،الدمام،الامام الترمزي  32263<br>
            &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;VAT No:310421133800003
            </p>
        </div>

        <div class="company-logo">
            <!-- <img src="assets/images/logo-img.png" class="logo-icon" alt="logo icon" style="margin-left: -17rem;
                    width: 100px; height: 70px;" alt="Company Logo"> -->
        </div>
        <div class="company-details-arabic">
            <p dir="rtl">
            <strong>مؤسسة ايمان يحي ابن محمد آل مفرح</strong><br>
            <strong>للمقاولات العامة  </strong><br>  
            الدمام،الدمام،الامام الترمزي 
            32263<br>
            الرقم الضريبي :310421133800003
            </p>
        </div>
    </div>
  <!-- <p>Driver Name:</p> -->
                           
                            <table class="header-table">
                                <thead>
                                    <tr>
                                        <th>All Driver Daily Report</th>
                                        <th>Date: {{$date}}</th>
                                    </tr>
                                </thead>
                             
                            </table>
                            <div class="border rounded">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th>Sr #</th>
                                    <th>ڈرائیور نام</th>
                                    <th>کل فروخت</th>
                                    <th>کل خریداری</th>
                                    <th>خالص منافع</th>
                                    <th>روزانہ کا خرچہ</th>
                                    <th>دیگر اخراجات</th>
                                    <th>بقایا منافع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                    $totalProfit = 0;
                                @endphp
                                @if (count($driverReports) > 0)
                                @foreach ($driverReports as $driverReport)
                                    @if (isset($driverReport['driver_name']))
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $driverReport['driver_name'] }}</td>
                                            <td>{{ $driverReport['total_after_discount'] }}</td>
                                            <td>{{ $driverReport['total_purchase_of_single_driver'] }}</td>
                                            <td>{{ $driverReport['total_profit'] }}</td>
                                            <td>{{ $driverReport['driver_daily_expense'] }}</td>
                                            <td>{{ $driverReport['other_expense'] }}</td>
                                            <td>{{ $driverReport['total_profit'] - $driverReport['driver_daily_expense'] - $driverReport['other_expense'] }}</td>
                                        </tr>
                                        @php
                                            $totalProfit += $driverReport['total_profit'] - $driverReport['driver_daily_expense'] - $driverReport['other_expense'];
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach
                                @else
                                    <p>No data available for drivers on this date.</p>
                                @endif
                            </tbody>
                               
                            </table>
                             <!-- can show here -->
                            </div>
                            <div class="border rounded">
                            <table>
                            <thead>
                            </thead>
                            <tbody>
                              
                                 <tr>
                                                <td>منافع: {{$totalProfit}}  </td>
                                                <td>تنخواہ کا خرچ: {{$totalEmployeePayroll}} </td>
                                                <td>بقایا منافع: {{$totalProfit - $totalEmployeePayroll}} </td>
                                            </tr>
                                            </tbody>
                             </table>
                        </div>

</body>

</html>