<!-- Start Cart  -->
<div>
    <h2> Xin chào {{ Auth::user()->customer->name }}</h2>
    <p>Cảm ơn bạn đã mua hàng của chúng tôi. Vui lòng xem lại thông tin đơn hàng của bạn và hãy theo dõi đơn hàng của
        mình trên website.</p>
</div>

<div class="main">
    <table>
        <tr>
            <td>Mã hóa đơn: </td>
            <th style="padding-left: 30px; color: rgb(119, 0, 0); text-align: left;">HĐ{{$order->id}}</th>
        </tr>
        <tr>
            <td>Khách hàng: </td>
            <th style="padding-left: 30px; text-align: left;">{{ Auth::user()->customer->name }}</th>
        </tr>
        <tr>
            <td>Email: </td>
            <th style="padding-left: 30px; text-align: left;">{{ Auth::user()->email }}</th>
        </tr>
        <tr>
            <td>Địa chỉ: </td>
            <th style="padding-left: 30px; text-align: left;">{{Auth::user()->customer->address}}</th>
        </tr>
        <tr>
            <td>Số điện thoại: </td>
            <th style="padding-left: 30px; text-align: left;">{{Auth::user()->customer->phone}}</th>
        </tr>
        <tr>
            <td>Ngày đặt hàng: </td>
            <th style="padding-left: 30px; text-align: left;">{{date('d-m-Y', strtotime($order->created_date))}}</th>
        </tr>
        <tr>
            <td>Tổng tiền: </td>
            <th style="padding-left: 30px; text-align: left;">{{number_format($order->total_money)}} VND</th>
        </tr>
    </table>

    <div class="title">
        <h3>CHI TIẾT ĐƠN HÀNG</h3>
    </div>
    <table border="1" cellspacing="0" , cellpadding="10" , style="width: 100%;">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($order->order_detail as $item)
            <tr class="tr-nhap">
                <td>{{$i++}}</td>
                <td>{{$item->product->name_pr}}</td>
                <td>{{$item->quanty}}</td>
                <td>{{number_format($item->price)}} VND</td>
                <td>{{number_format($item->quanty * $item->price)}} VND</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>