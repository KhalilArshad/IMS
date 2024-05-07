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
            width: 180px;
            height: 50px; /* Adjusted height for better spacing */
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
            width: 180px;
            height: 50px; /* Adjusted height for better spacing */
            margin-left: 190px;
            margin-top: -60px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section3 {
            width: 180px;
            height: 50px; /* Adjusted height for better spacing */
            margin-left: 390px;
            margin-top: -100px;
            background-color: white;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .label, .value {
            margin: 0;
            font-size: 15px;
            padding: 2px 0;
            color: blue;
        }

        /* .label {
            font-weight: bold;
        } */

        .value {
            font-size: 20px; /* Larger font size for the number */
            color: blue; /* Optional: Change color if needed */
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
        }

        .company-details-arabic {
            float: right;
            text-align: right;
            width: 40%;
            margin-top: -10;
        }
        strong {
        font-weight: bold;
        font-size: 140%; /* Makes the text larger */
    }

    .middleDiv {
        text-align: center; /* Center the text inside middleDiv */
        /*padding: 20px; /* Add some padding for aesthetics */
        font-size: 24px; /* Larger text for visibility */
        color: #000; /* Set text color */
        margin-top: 5px;
        background-color: #a8adbf; 
        border-radius: 10px;
        border: 1px solid #333;
        width: 130px;
        height: 45px;
        margin-left: 300px !important;
    }
    </style>
    {{-- /Custom Style --}}
</head>

<body>
    {{-- header --}}
    <div class="container">
        <div class="company-details">
            <p>
            <strong>TASHYED W ENGAZ CO </strong> <br>
            &nbsp;&nbsp;&nbsp; Wholesale, retail and food items <br>
            &nbsp;  C.R.: 1010324111 - C.C.NO.:4633 <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;    VAT:300491127800003
            </p>
        </div>

        <div class="company-logo">
            <img src="assets/images/logo-img.png" class="logo-icon" alt="logo icon" style="margin-left: -20rem;
                    width: 100px; height: 50px;" alt="Company Logo">
        </div>
        <div class="company-details-arabic">
            <p dir="rtl">
            شركة تشيد وإنجاز <br>
            بيع بالجملة والتجزئة والمواد الغذائية <br>
            س.ج: 102753733- ر.م: 365522 <br>
            الضريبة: 3002262266
            </p>
        </div>
    </div>
    <div class="center-wrapper">
        <div class="middleDiv">اجل</div>
    </div>
    <div class="container bill-details">
        <div>
            <div class="section">
                <div class="label label-background">WAREHOUSE  مستودع</div>
                
                <div class="value">12</div>
            </div>
            <div class="section2">
                <div class="label label-background">Invoice No  مستودع</div>
                
                <div class="value">12</div>
            </div>
            <div class="section3">
                <div class="label label-background">Date  مستودع</div>
                
                <div class="value">12</div>
            </div>

            
        </div>
    </div>

    <div class="pdf-header">
        <h1><u>Khan's Traders</u></h1>
        <p>Phone# &nbsp; 0515114010 &nbsp;Email testemail@gmail.com</p>
        <p>Al-Balad Jeddah Saudi Arabia</p>
    </div>
    {{-- / header --}}
    {{-- heading --}}
    <div class="pdf-heading">
        <h3>Invoice</h3>
    </div>
    {{-- / heading --}}
    {{-- incoice Details --}}
    <div class="pdf-invoice-detail">
        <span><b style="margin-left:30px">Date</b> &nbsp; {{$invoice->date}}</span>
        <span><b style="margin-left:270px">Status</b> &nbsp; {{$invoice->status}}</span>
    </div>
    <div class="pdf-invoice-detail">

        <span><b style="margin-left:30px;">Customer Name</b> &nbsp;{{$invoice->customer->name}}</span>
        <span><b style="margin-left:205px">Customer Contact</b> &nbsp; {{$invoice->customer->phone_no}}</span>
        {{-- <span><b style="margin-left:205px">Supplier Address</b> &nbsp; {{$invoice->customer->email}}</span> --}}
    </div>
    {{-- /incoice Details --}}
    {{-- items details --}}
    <table style="width:100%; margin-top:20px;">
        <thead style="font-size:15px; ">
            <tr style="border:2px solid black;">
                <th style="width:5%;">Sr.No</th>
                <th style="width:65%;">Item</th>
                <th style="width:20%;">Unit</th>
                <th style="width:20%;">Quantity</th>
                <th style="width:20%;">Price</th>
                <th style="width:20%;">Vat</th>
                <th style="width:20%;">Total Price</th>
            </tr>
        </thead>
        <tbody style="font-size:11px; text-align:center; padding:0px; margin:0px; ">
            @php
                $count = 0;
                $total_vat = 0;
            @endphp
            @foreach($invoiceChild as $data)
            @php
                $count++;
                $total_vat += $data->total_vat;
            @endphp
            <tr>
                <td>
                    <b>{{$count}}</b>
                </td>
                <td>{{$data->items->name}}</td>
                <td>{{$data->items->unit->name}}</td>
                <td>{{$data->quantity}}</td>
                <td>{{$data->selling_price}}</td>
                <td>{{$data->total_vat}}</td>
                <td>{{$data->total}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- /items details --}}
    {{-- bill details --}}
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Total: &nbsp; <small>{{$invoice->total_bill - $total_vat}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Discount: &nbsp; <small>0</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Vat: 15%&nbsp; <small>{{$total_vat}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;">Net Total&nbsp; <small>{{$invoice->total_bill}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Paid Amount: &nbsp; <small>{{$invoice->paid_amount}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Remaining: &nbsp; <small>{{$invoice->remaining}}</small> </h5>

    </div>
    {{-- /bill details --}}

</body>

</html>