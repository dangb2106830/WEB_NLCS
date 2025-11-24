@extends('front.layouts.layout')

@section('head')
<title>Thanh toán</title>
@endsection

@section('content')

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Đặt hàng</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('cart.index')}}">Giỏ hàng</a></li>
                    <li class="breadcrumb-item active">Đặt hàng</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Cart  -->
<div class="cart-box-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-6 mb-3">
                <div class="checkout-address">
                    <div class="title-left">
                        <h3>Thông tin và địa chỉ</h3>
                    </div>
                    <form class="needs-validation" action="{{ route('index.postCheckout') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="username">Họ tên *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="username" value="{{ Auth::user()->customer->name }}"
                                    placeholder="" name="name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="username">Email *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="username" value="{{ Auth::user()->email }}"
                                    placeholder="" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Địa chỉ *</label>
                            <input type="email" class="form-control" id="email"
                                value="{{ Auth::user()->customer->address }}" name="address" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="email">Số điện thoại *</label>
                            <input type="email" class="form-control" id="email"
                                value="{{ Auth::user()->customer->phone }}" name="phone" placeholder="">
                        </div>
                        <button class="btn btn-primary btn-lg btn-block"  type="submit">Đặt hàng</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 mb-3">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="order-box">
                            <div class="title-left">
                                <h3>Đơn hàng của bạn</h3>
                            </div>
                            <div class="d-flex">
                                <div class="font-weight-bold">Tên sản phẩm</div>
                                <div class="ml-auto font-weight-bold">Số lượng</div>
                            </div>
                            <hr class="my-1">
                            @foreach (Session::get('Cart')-> products as $item)
                            <div class="d-flex">
                                <h4>{{$item['productInfo']->name_pr }}</h4>
                                <div class="ml-auto font-weight-bold">x {{ $item['quanty'] }}</div>
                            </div>

                            @endforeach
                            <div class="d-flex">
                                <div class="font-weight-bold">Tổng tiền</div>
                                <div class="ml-auto font-weight-bold">
                                    @if (isset(Session::get('Cart')->totalQuanty))
                                    {{ number_format(Session::get('Cart')->totalPrice) }} VNĐ
                                    @else
                                    0
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</div>
<!-- End Cart -->
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