<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
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

        .middleDiv {
            text-align: center; /* Center the text inside middleDiv */
            /*padding: 20px; /* Add some padding for aesthetics */
            font-size: 24px; /* Larger text for visibility */
            color: #000; /* Set text color */
            margin-top: 3px;
            margin-bottom: 3px;
            background-color: #a8adbf; 
            border-radius: 10px;
            border: 1px solid #333;
            width: 130px;
            height: 45px;
            margin-left: 290px !important;
        }

        /* pdf-invoice-detail-right */
        td {
                border: 1px solid black;
            }

            /* bill details */
            .pdf-bill-details {
                margin: 10px 0px 10px 530px;
            }

            thead th {
            background-color: #a8adbf; /* Matches the color used in .container and other sections */
            color: #753e30; /* Keeping the text color consistent with other headers */
            padding: 10px; /* Added padding for better spacing */
        }

        tbody td {
            font-size: 14px; /* Increased from 11px to 14px for better readability */
            padding: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            margin-top: 20px;
            margin-left: -30px; 
            border: 4px solid #a8adbf;
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
                    width: 100px; height: 50px;" alt="Company Logo"> -->
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
    <div class="center-wrapper">
        <div class="middleDiv">شراء</div>
    </div>
    <div class="bill-details">
        <div>
            <div class="section">
                <div class="label label-background">WAREHOUSE  المستودع</div>
                
                <div class="value"></div>
            </div>
            <div class="section2">
                <div class="label label-background">PO NO  رقم شراء</div>
                
                <div class="value">{{$purchaseOrder->po_no}}</div>
            </div>
            <div class="section3">
                <div class="label label-background">DATE  &nbsp;&nbsp; التاريخ</div>
                
                <div class="value">{{$purchaseOrder->date}}</div>
            </div>
            <div class="section4">
                <div class="label label-background">BRANCH NO  رقم الضرع</div>
                
                <div class="value"></div>
            </div>
        </div>
        <div>
            <div class="section5">
                <div class="value">{{$purchaseOrder->supplier->name}}</div>
                <div class="value">Phone No: {{$purchaseOrder->supplier->phone_no}} Email: {{$purchaseOrder->supplier->email}}</div>
            </div>
            <div class="section6">
            <div class="value"> اسم المورد</div>
                <div class="value">Supplier Name</div>
            </div>
        </div>
    </div>

    {{-- /incoice Details --}}
    {{-- items details --}}
    
    <table>
        <thead style="font-size:15px; ">
            <tr style="border:2px solid black;">
                <th style="width:5%;">Sr.No</th>
                <th style="width:65%;">Item</th>
                <th style="width:20%;">Unit</th>
                <th style="width:20%;">Quantity</th>
                <th style="width:20%;">Purchase Price</th>
                <th style="width:20%;">Total Vat</th>
                <th style="width:20%;">Total Price</th>
            </tr>
        </thead>
        <tbody style="font-size:11px; text-align:center; padding:0px; margin:0px; ">
            @php
                $count = 0;
                $total_vat = 0;
            @endphp
            @foreach($purchaseOrderChild as $data)
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
                <td>{{$data->unit_price}}</td>
                <td>{{$data->total_vat}}</td>
                <td>{{$data->total}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- /items details --}}
    {{-- bill details --}}
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Total: &nbsp; <small>{{$purchaseOrder->total_bill - $total_vat}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Discount: &nbsp; <small>0</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Vat: 15%&nbsp; <small>{{$total_vat}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;">Net Total&nbsp; <small>{{$purchaseOrder->total_bill}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Paid Amount: &nbsp; <small>{{$purchaseOrder->current_payment}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;">Old Paid: &nbsp; <small>{{$purchaseOrder->old_receive}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Remaining: &nbsp; <small>{{$purchaseOrder->remaining}}</small> </h5>

    </div>
    {{-- /bill details --}}
    
    <div class="footer">
        <h6> © 2024 Iman Yahya Bin Muhammad Al Mufarreh Foundation General All rights reserved.</h6>
    </div>
</body>

</html>