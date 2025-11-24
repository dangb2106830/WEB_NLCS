<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hoá đơn bán hàng</title>

    <!-- Styles -->
    <style>
        body {
            font-family: 'arial',
        }
        .header{
            text-align: center;
            margin-bottom: 50px;
        }

        .title {
            text-align: center;
            margin-top: 20px;
        }
        table {
            margin: 0 auto;
            font-size: 18px;
            margin-bottom: 30px;
        }
        #chi-tiet {
            border-collapse: collapse;
        }
        #chi-tiet,#chi-tiet th,#chi-tiet td {
            border: 1px solid black;
            padding: 5px 10px;
            font-size: 14px;
        }

        .tr-nhap, .tr-nhap td {
            border: 1px solid black;
            padding: 10px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
   <div class="header">
       <h1>HOA DON BAN HANG</h1>
   </div>

   <div class="main">
        <table>
            <tr>
                <td>Ma hoa don: </td>
                <th style="padding-left: 30px; color: rgb(119, 0, 0); text-align: left;">HĐ{{$order->id}}</th>
            </tr>
            <tr>
                <td>Khach hang: </td>
                <th style="padding-left: 30px; text-align: left;">{{$order->customer?$order->customer->name:''}}</th>
            </tr>
            <tr>
                <td>Nhan vien ban: </td>
                <th style="padding-left: 30px; text-align: left;">{{$order->staff?$order->staff->name:''}}</th>
            </tr>
            <tr>
                <td>Ngay lap: </td>
                <th style="padding-left: 30px; text-align: left;">{{date('d-m-Y', strtotime($order->created_date))}}</th>
            </tr>
            <tr>
                <td>Tong tien: </td>
                <th style="padding-left: 30px; text-align: left;">{{number_format($order->total_money)}} VND</th>
            </tr>
    </table>

    <div class="title">
        <h3>CHI TIET HOA DON BAN HANG</h3>
    </div>
    <table id="chi-tiet">
        <thead>
            <tr>
                <th>STT</th>
                <th>San pham</th>
                <th>So luong</th></th>
                <th>Don gia</th>
                <th>Thanh tien</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($order_detail))
                <?php $i = 1; ?>
                @foreach ($order_detail as $item)
                    <tr class="tr-nhap">
                        <td>{{$i++}}</td>
                        <td>{{$item->product?$item->product->name_pr:''}}</td>
                        <td>{{$item->quanty}}</td>
                        <td>{{number_format($item->price)}} VND</td>
                        <td>{{number_format($item->quanty*$item->price)}} VND</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
   </div>
</body>
</html>