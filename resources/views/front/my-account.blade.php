@extends('front.layouts.layout')

@section('head')
<title>Quản lý tài khoản</title>
@endsection

@section('content')

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Tài khoản</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý tài khoản</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start My Account  -->
<div class="my-account-box-main">
    <div class="container">
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
        @if (session('loi'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            {{ session('loi') }}
        </div>
        @endif
        <div class="my-account-page">
            <div class="row">
                <div class="col-2">
                    <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list"
                            href="#list-home" role="tab" aria-controls="home">Thông tin tài khoản</a>
                        <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list"
                            href="#list-profile" role="tab" aria-controls="profile">Lịch sử đặt hàng</a>
                        <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list"
                            href="#list-messages" role="tab" aria-controls="messages">Thay đổi mật khẩu</a>
                    </div>
                </div>
                <div class="col-10">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="list-home" role="tabpanel"
                            aria-labelledby="list-home-list">
                            <div class="card">
                                <h5 class="card-header">Thông tin tài khoản</h5>
                                <div class="card-body">
                                    <form action="{{route('index.editProfile')}}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name" class="form-label"><b>Họ và tên:</b></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ Auth::user()->customer->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label"><b>Email:</b></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ Auth::user()->email }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sdt" class="form-label"><b>Số điện thoại:</b></label>
                                            <input type="text" class="form-control" id="sdt" name="phone"
                                                value="{{ Auth::user()->customer->phone }}">
                                        </div>
                                        <div class="mb-3" style="width: 40%">
                                            <label for="ngay_sinh" class="form-label"><b>Ngày sinh:</b></label>
                                            <input type="date" class="form-control" id="ngay_sinh" name="birth_day"
                                                value="{{ Auth::user()->customer->birth_day }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="dia_chi" class="form-label"><b>Địa chỉ:</b></label>
                                            <input type="text" class="form-control" id="dia_chi" name="address"
                                                value="{{ Auth::user()->customer->address }}">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="list-profile" role="tabpanel"
                            aria-labelledby="list-profile-list">
                            <div class="card">
                                <h5 class="card-header">Lịch sử đặt hàng</h5>
                                <div class="card-body">
                                    <table class="table">
                                        <thead class="bg-success">
                                            <tr style="background: #b0b435; color: #ffffff;">
                                                <th scope="col">STT</th>
                                                <th scope="col">Mã đơn</th>
                                                <th scope="col">Ngày đặt</th>
                                                <th scope="col">Tổng tiền</th>
                                                <th scope="col">Trạng thái đơn hàng</th>
                                                <th scope="col">Tác vụ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($order))
                                            <?php $i = 1; ?>
                                            @foreach ($order as $item)
                                            <tr>
                                                <th scope="row">{{ $i++ }}</th>
                                                <th scope="row">DH{{ $item->id }}</th>
                                                <td>{{ date('d-m-Y H:m:s', strtotime($item->created_date)) }}</td>
                                                <td>{{ number_format($item->total_money) }} VNĐ</td>
                                                <td>
                                                    @if ($item->status == 1)
                                                    <i style="color: green;">Đã xác nhận</i>
                                                    @elseif ($item->status == 2)
                                                    <i style="color: blue;">Đang giao hàng</i>
                                                    @elseif ($item->status == 3)
                                                    <b><i style="color: green;">Đã thanh toán</i></b>
                                                    @elseif ($item->status == -1)
                                                    <b><i style="color: red;">Đơn hàng đã bị huỷ</i></b>
                                                    @elseif ($item->status == -2)
                                                    <b><i style="color: red;">Giao hàng không thành công</i></b>
                                                    @else
                                                    <b><i>Chờ xác nhận</i></b>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status == 0)
                                                    <a href="{{route('admin.checkout.cancelOrder', ['id' => $item->id])}}"
                                                        class="btn btn-danger btn-xs">Huỷ đơn hàng</a>
                                                    @else
                                                    <button class="btn btn-danger btn-xs" disabled>Không thể huỷ đơn
                                                        hàng</button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach

                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="list-messages" role="tabpanel"
                            aria-labelledby="list-messages-list">
                            <div class="card">
                                <h5 class="card-header">Thay đổi mật khẩu</h5>
                                <div class="card-body">
                                    <form action="{{ route('index.postUserPassword') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="oldPassword">Mật khẩu cũ</label>
                                            <input type="password" class="form-control" id="oldPassword"
                                                name="oldPassword">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Mật khẩu mới</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" id="confirmPassword"
                                                name="confirmPassword">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Thay đổi</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End My Account -->
@section('feed')

<div class="main-instagram owl-carousel owl-theme">
    @foreach($brand as $item)
    <div class="item">
        <div class="ins-inner-box">
            <img style="height: 200px;" src="{{asset('uploads/images/brand/'.$item->image)}}" alt="" />
            <div class="hov-in">
                <a href="{{route('index.getProduct')}}"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
@endsection
@section('script')
<script type="text/javascript">
var password = document.getElementById("password"),
    confirm_password = document.getElementById("confirmPassword");

function validatePassword() {
    if (password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Xác nhận mật khẩu không đúng!");
    } else {
        confirm_password.setCustomValidity('');
    }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>

@endsection