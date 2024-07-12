<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Details Report</title>
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
            &nbsp;  &nbsp; الدمام،الدمام،الامام الترمزي  32416<br>
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
                            <div class="border rounded">
                            <table>
                            <thead>
                            </thead>
                               <tbody>
                                           <tr>
                                                <td>Driver Name:  {{$driverName}}  </td>
                                                <td>Date : {{$date}}</td>
                                            </tr>
                                </tbody>
                             </table>
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th>Sr #</th>
                                    <th>گاہک کا نام</th>
                                    <th>کل بل</th>
                                    <th>اج بقایا</th>
                                    <th>پرانا بقایا</th>
                                    <th>پرانا موصول </th>
                                    <th>کل بقایا</th>
                                     <th>تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                            @php($i = 1)
                                  
                                            @foreach ($invoices  as $child)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $child->customer->name }}</td>
                                                <td>{{ $child->today_bill}}</td>
                                                <td>{{ $child->today_remaining}}</td>
                                                <td>{{ $child->old_remaining}}</td>
                                                <td>{{ $child->old_received}}</td>
                                                <td>{{ $child->net_remaining}}</td>
                                                <td>{{ $child->date}}</td>
                                            </tr>
                                    
                                            @php($i++)
                                            @endforeach
                                            </tbody>
                               
                            </table>
                             <!-- can show here -->
                            </div>
                      

</body>

</html>