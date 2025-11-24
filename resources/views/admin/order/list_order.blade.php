@extends('admin.layouts.master')

@section('head')
<title>Quản lý đơn đặt hàng</title>
@endsection

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.bootstrap.min.css">
<style>
th,
td {
    white-space: nowrap;
}

div.dataTables_wrapper {
    margin: 0;
}
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ ĐƠN ĐẶT HÀNG
                    <small>Danh sách</small>
                </h1>
            </div>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                @foreach ($errors->all() as $err)
                {{ $err }}<br>
                @endforeach
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session('success') }}
            </div>
            @endif
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách đơn đặt hàng</b>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="table-ncc">
                                <table class="table table-striped table-bordered table-hover display nowrap"
                                    style="width:100%" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã hoá đơn</th>
                                            <th>Họ tên khách hàng</th>
                                            <th>Tổng tiền</th>
                                            <th>Ngày đặt</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($order))
                                        <?php $i = 1;?>
                                        @foreach ($order as $item)
                                        @if (isset($item->customer))
                                        <tr class="odd gradeX">
                                            <td class="" style="width: 80px; text-align: center;">{{ $i++ }}
                                            </td>
                                            <td style="text-align: center; color: red"> <b> DH {{ $item->id }}</b></td>
                                            <td>{{ $item->customer->name }}</td>
                                            <td>{{ number_format($item->total_money) }} VNĐ</td>
                                            <td>{{ date('d-m-Y H:m:s', strtotime($item->created_date)) }}</td>
                                            <td>
                                                @if ($item->status == 0)
                                                <a class="btn btn-success btn-xs"
                                                    href="{{route('admin.checkout.acceptOrder', ['id' => $item->id])}}"><i
                                                        class="fa fa-check"></i> Xác nhận</a>
                                                @elseif($item->status == 1)
                                                <a class="btn btn-success btn-xs"
                                                    href="{{route('admin.checkout.startShip', ['id' => $item->id])}}"><i
                                                        class="fas fa-shipping-fast"></i> Bắt đầu giao hàng</a>
                                                @elseif($item->status == 2)
                                                <a class="btn btn-danger btn-xs"
                                                    href="{{route('admin.checkout.cancelShip', ['id' => $item->id])}}"><i
                                                        class="fa fa-close"></i> Giao hàng không thành công</a> |
                                                <a class="btn btn-success btn-xs"
                                                    href="{{route('admin.checkout.acceptPayment', ['id' => $item->id])}}"><i
                                                        class="fa fa-check-circle"></i> Xác nhận thanh toán</a>
                                                @elseif($item->status == 3)
                                                <b style="color: green;">ĐÃ THANH TOÁN</b>
                                                @elseif($item->status == -1)
                                                <b style="color: red;">ĐƠN HÀNG ĐÃ BỊ HUỶ</b>
                                                @elseif($item->status == -2)
                                                <b style="color: red;">GIAO HÀNG KHÔNG THÀNH CÔNG</b> | <a
                                                    class="btn btn-success btn-xs"
                                                    href="{{route('admin.checkout.startShip', ['id' => $item->id])}}"><i
                                                        class="fa fa-sign-out"></i> Giao lại</a> | <a
                                                    class="btn btn-danger btn-xs"
                                                    href="{{route('admin.checkout.AdmincancelOrder', ['id' => $item->id])}}"><i
                                                        class="fa fa-ban"></i> Huỷ</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-xs btn-view" href="#"
                                                    data-url="{{ route('admin.checkout.getView', ['id' => $item->id]) }}"><i
                                                        class="fa fa-edit"></i> Xem chi tiết</a>
                                                <a class="btn btn-success btn-xs"
                                                    href="{{ route('admin.checkout.print', ['id' => $item->id]) }}"><i
                                                        class="fa fa-print"></i> In hoá đơn</a>
                                            </td>
                                            </>
                                            @endif
                                            @endforeach
                                            @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.order.view_bill')

@endsection
