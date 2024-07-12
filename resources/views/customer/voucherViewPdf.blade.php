<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt Voucher</title>
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

    
    .middleDiv {
    display: flex; /* Enable flexbox */
    align-items: center; /* Vertically center the content */
    justify-content: center; /* Horizontally center the content */
    text-align: center; /* Center the text inside middleDiv */
    padding: 0px; /* Add some padding for aesthetics */
    font-size: 20px; /* Larger text for visibility */
    line-height: 45px; /* Match the height of the div to center text vertically */
    color: #000; /* Set text color */
    margin-top: 8px;
    margin-bottom: -10px;
    background-color: #a8adbf; 
    border-radius: 10px;
    border: 1px solid #333;
    width: 200px;
    height: 45px;
    margin-left: 220px !important;
}

.center-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: auto; /* Ensure the wrapper takes the height of its content */
}

table {
    width: 100%;
    margin-top: 20px;
    page-break-inside: avoid; /* Prevent page break inside the table */
}

thead, tbody, tr, td, th {
    page-break-inside: avoid; /* Prevent page break inside table elements */
    page-break-after: auto; /* Ensure no page break after each table row */
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
    <div class="center-wrapper">
        <div class="middleDiv">Receipt Voucher</div>
    </div>
    <table class="mt-4">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Phone No</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $voucher->customer->name }}</td>
                <td>{{ $voucher->customer->phone_no }}</td>
                <td>{{ $voucher->date }}</td>
            </tr>
            <tr>
                <th>Previous Total</th>
                <th>Paid Amount</th>
                <th>Total Remaining</th>
            </tr>
            <tr>
                <td>{{ $voucher->previous_balance }}</td>
                <td>{{ $voucher->paid_amount }}</td>
                <td>{{ $voucher->remaining}}</td>
            </tr>
            
           
        </tbody>
    </table>

    <div class="footer">
        <h6> © 2024 Iman Yahya Bin Muhammad Al Mufarreh Foundation General All rights reserved.</h6>
    </div>
</body>

</html>