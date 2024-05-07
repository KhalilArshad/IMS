<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
    {{-- Custom Style --}}
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;

        }

        /* header  */
        .pdf-header {
            width: 100%;
            height: 80px;
            text-align: center;
            margin: -35px 0px 0px -3px;
            padding-top: 0px;

        }

        /* header h1  */
        .pdf-header h1 {
            padding: 0px;
            margin: 0px 0px 7px 0px;
            font-weight: bold;
        }

        /* header paragraph */
        .pdf-header p {
            padding: 0px;
            margin: 0px;
            font-size: 12px;
            font-weight: bold;
        }

        /*  pdf heading */
        .pdf-heading {
            margin-top: 0px;
            text-align: center;
        }

        /* pdf-invoice-detail-left */
        .pdf-invoice-detail {
            width: 100%;
        }

        .pdf-invoice-detail span {
            width: 50%;
            font-size: 12px;
            margin: 0px;
            padding: 0px;
        }

        /* pdf-invoice-detail-right */
        td {
            border: 1px solid black;
        }

        /* bill details */
        .pdf-bill-details {
            margin: 10px 0px 10px 530px;
        }
    </style>
    {{-- /Custom Style --}}
</head>

<body>
    {{-- header --}}
    <div class="pdf-header">
        <h1><u>Khan's Traders</u></h1>
        <p>Phone# &nbsp; 0515114010 &nbsp;Email testemail@gmail.com</p>
        <p>Al-Balad Jeddah Saudi Arabia</p>
    </div>
    {{-- / header --}}
    {{-- heading --}}
    <div class="pdf-heading">
        <h3>Purchase Order</h3>
    </div>
    {{-- / heading --}}
    {{-- incoice Details --}}
    <div class="pdf-invoice-detail">
        <span><b style="margin-left:30px">Date</b> &nbsp; {{$purchaseOrder->date}}</span>
        <span><b style="margin-left:270px">Status</b> &nbsp; {{$purchaseOrder->status}}</span>
    </div>
    <div class="pdf-invoice-detail">

        <span><b style="margin-left:30px;">Supplier Name</b> &nbsp;{{$purchaseOrder->supplier->name}}</span>
        <span><b style="margin-left:205px">Supplier Contact</b> &nbsp; {{$purchaseOrder->supplier->phone_no}}</span>
        {{-- <span><b style="margin-left:205px">Supplier Address</b> &nbsp; {{$purchaseOrder->supplier->email}}</span> --}}
    </div>
    {{-- /incoice Details --}}
    {{-- items details --}}
    <table style="width:100%; margin-top:20px;">
        <thead style="font-size:15px; ">
            <tr style="border:2px solid black;">
                <th style="width:5%;">Sr.No</th>
                <th style="width:65%;">Item</th>
                <th style="width:15%;">Unit Price</th>
                <th style="width:10%;">Quantity</th>
                <th style="width:15%;">Total Price</th>
            </tr>
        </thead>
        <tbody style="font-size:11px; text-align:center; padding:0px; margin:0px; ">
            @php($count=0)
            @foreach($purchaseOrderChild as $data)
            @php($count++)
            <tr>
                <td>
                    <b>{{$count}}</b>
                </td>
                <td>{{$data->items->name}}</td>
                <td>{{$data->unit_price}}</td>
                <td>{{$data->quantity}}</td>
                <td>{{$data->total}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- /items details --}}
    {{-- bill details --}}
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Total Bill: &nbsp; <small>{{$purchaseOrder->total_bill}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Paid Amount: &nbsp; <small>{{$purchaseOrder->current_payment}}</small> </h5>

    </div>
    <div class="pdf-bill-details">
        <h5 style="padding:0px; margin:0px; border:1px solid black; text-align:center;"> Remaining: &nbsp; <small>{{$purchaseOrder->remaining}}</small> </h5>

    </div>
    {{-- /bill details --}}

</body>

</html>