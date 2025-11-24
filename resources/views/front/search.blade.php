@extends('front.layouts.layout')

@section('content')
<div class="products-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>TÌM KIẾM</h1>
                    <p>Tìm thấy {{count($product)}} sản phẩm </p>
                </div>
            </div>
        </div>
        <div class="row special-list">
            @foreach($product as $item)
            <div class="col-lg-3 col-md-6 special-grid all">
                <div class="products-single fix">
                    <div class="box-img-hover">
                        @if($item->discount !=0)
                        <div class="type-lb">
                            <p class="sale">Sale</p>
                        </div>
                        @endif
                        @if($item->status != 0)
                        <div class="type-lb">
                            <p class="new">Nổi bật</p>
                        </div>
                        @endif
                        <img src="{{asset('uploads/images/product/'.$item->image)}}" style="height: 200px;"
                            class="img-fluid" alt="Image">
                        <div class="mask-icon">
                            <ul>
                                <li><a href="{{ route('index.getProductDetail', ['product_slug' => $item->slug, 'product_id' => $item->id]) }}"
                                        data-toggle="tooltip" data-placement="right" title="View"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a class="cart add-cart" href="#" onclick="addCart({{ $item->id }})"
                                data-id="{{route('cart.addCart',[$item->id] )}}">Thêm vào giỏ
                                hàng</a>
                        </div>
                    </div>
                    <div class="why-text">
                        <h4 style="text-align: center;">{{$item->name_pr}}</h4>
                        <h4 style="color: red; text-align: center;">{{$item->brand->name_bra}}</h4>
                        @if($item->discount != 0)
                        <h4><del style="color: chocolate;">{{number_format($item->price)}}
                                VNĐ</h5>
                                <h5 style="text-align: center;">
                            </del>{{number_format($item->discount)}} VNĐ</h5>
                            @else
                            <div style="padding-top: 33px;">
                                <h5>{{number_format($item->price)}} VNĐ</h5>
                            </div>
                            @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
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