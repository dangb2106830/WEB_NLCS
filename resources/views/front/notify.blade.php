@extends('front.layouts.layout')

@section('head')
<title>Thông báo</title>
@endsection

@section('content')
<div class="jumbotron jumbotron-fluid mt-5 mb-5">
    <div class="container">
        <h1 class="display-4">BẠN ĐÃ ĐẶT HÀNG THÀNH CÔNG!</h1>
        <p class="lead">Cảm ơn bạn đã mua hàng của chúng tôi. <a style="color: blue" href="{{route('index.getAccount')}}">Hãy theo dõi đơn hàng của bạn...</a></p> 
    </div>
</div>
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