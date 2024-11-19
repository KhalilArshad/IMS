<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
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
        .section {
            width: 160px;
            height: 65px; 
            margin-left: -10px;
            margin-top: -10px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
     
        .section2 {
            width: 160px;
            height: 65px; /* Adjusted height for better spacing */
            margin-left: 163px;
            margin-top: -63px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
   
        .section3 {
            width: 160px;
            height: 65px; /* Adjusted height for better spacing */
            margin-left: 335px;
            margin-top: -65px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section4 {
            width: 180px;
            height: 65px; /* Adjusted height for better spacing */
            margin-left: 505px;
            margin-top: -65px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section5 {
            width: 505px;
            height: 65px; 
            margin-left: -10px;
            margin-top: 10px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section6 {
            width: 180px;
            height: 65px; /* Adjusted height for better spacing */
            margin-left: 505px;
            margin-top: -65px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
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

    .footer {
        position: fixed;
        left: -20px;
        bottom: -35px;
        width: 100%;
        padding: 20px;
        text-align: center;
        background-color: #a8adbf;
        color: #753e30;
        border-top: 2px solid #333;
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
    <table class="mt-2">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Phone No</th>
                <th>Date Of Joining</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payroll->employee->name }}</td>
                <td>{{ $payroll->employee->phone_no }}</td>
                <td>{{ $payroll->employee->date_of_joining }}</td>
            </tr>
            <tr>
                <th>Salary Slip Of Month</th>
                <th>Designation</th>
                <th>Salary</th>
            </tr>
            <tr>
                @php
                $date = new DateTime($payroll->date);
                @endphp
                <td>{{  $payroll->month }}</td>
                <td>{{ $payroll->employee->designation }}</td>
                <td>{{ $payroll->employee->salary}}</td>
            </tr>
            <tr>
                <th>Advance</th>
                <th>Paid In Advance</th>
                <th>Remaining Advance</th>
            </tr>
            <tr>
                @php
                $advance = $payroll->advance;
                $paid_in_advance = $payroll->paid_in_advance;
                $remainingAdvance =  $advance - $paid_in_advance;
                @endphp
                <td>{{ $payroll->advance }}</td>
                <td>{{ $payroll->paid_in_advance }}</td>
                <td>{{ $remainingAdvance }}</td>
            </tr>
            <tr>
                <th>Total Remaining</th>
                <th>Over Time</th>
                <th>Total Salary To be Paid</th>
            </tr>
            <tr>
                <td>{{ $payroll->remaining }}</td>
                <td>{{ $payroll->overtime }}</td>
                <td>{{ $payroll->total_salary_to_be_paid }}</td>
            </tr>
          
        </tbody>
    </table>

    <div class="footer">
        <h6> © 2024 Iman Yahya Bin Muhammad Al Mufarreh Foundation General All rights reserved.</h6>
    </div>
</body>

</html>