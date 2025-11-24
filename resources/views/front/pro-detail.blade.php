@extends('front.layouts.layout')

@section('head')
<title>Chi tiết sản phẩm</title>
@endsection

@section('content')
<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Chi tiết sản phẩm</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('index.getProduct')}}">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">Chi tiết </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Detail  -->
<div class="shop-detail-box-main">
    <div class="container">
        @if (isset($product))

        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-6">

                <div id="carousel-example-1" class="single-product-slider carousel slide" data-ride="carousel">

                    <div class="carousel-inner" role="listbox">
                        @foreach($products as $key => $item)
                        <div class="carousel-item {{$key == 0 ? 'active' : '' }}">
                            <img src="{!!asset('uploads/images/product_img/'.$item['pro_image'])!!}"
                                class="d-block w-100" alt="First slide">
                        </div>
                        @endforeach
                    </div>

                    <a class="carousel-control-prev" href="#carousel-example-1" role="button" data-slide="prev">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel-example-1" role="button" data-slide="next">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span class="sr-only">Next</span>
                    </a>
                    <ol class="carousel-indicators">
                        @foreach($products as $key => $item)

                        <li data-target="#carousel-example-1" data-slide-to=" {{$key == 0 }}" class="active">
                            <img class="d-block w-100 img-fluid" src="{!!asset('uploads/images/product_img/'.$item['pro_image'])!!}" alt="" />
                        </li>

                        @endforeach
                    </ol>

                </div>
            </div>

            <div class="col-xl-7 col-lg-7 col-md-6">
                <div class="single-product-details">
                    <h2>{{$product->name_pr}}</h2>
                    <h4>Thương hiệu: {{$product->brand->name_bra}}</h4>
                    @if($product->discount != 0)
                    <h4><del style="color: chocolate;">{{number_format($product->price)}} VNĐ </h5>
                            <h5>
                        </del>{{number_format($product->discount)}} VNĐ</h5>
                        @else
                        <h5>Giá: {{number_format($product->price)}} VNĐ</h5>
                        @endif
                        <p class="available-stock">Đã bán: <span>{{$product->sold}} </span>sản phẩm
                        <p>
                        <p class="available-stock">Số lượng trong kho: <span>
                                {{$product->quantity}} </span>sản phẩm
                        <p>
                        <p class="available-stock">Quà tặng: <span>
                                {{$product->gift}} </span>
                        <p>
                        <div style="display: flex;  flex-wrap: wrap;">
                            <h4>Mô tả:
                                <p> {!!$product->description!!}</p>
                            </h4>
                        </div>
                        <div class="price-box-bar">
                            <div class="cart-and-bay-btn">

                                <a class="btn hvr-hover" href="#" onclick="addCart({{ $product->id }})"
                                    data-id="{{ $product->id }}">Thêm vào giỏ hàng</a>

                            </div>
                        </div>
                </div>
            </div>
        </div>

        @endif

        <div class="row my-5">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Sản phẩm liên quan</h1>
                    <p>Một số sản phẩm mà bạn có thể quan tâm</p>
                </div>

                <div class="featured-products-box owl-carousel owl-theme">
                    @foreach ($product->category->product as $item)
                    @if ($item->id != $product->id && $item->quantity>0)
                    <div class="item">
                        <div class="products-single fix">
                            <div class="box-img-hover">
                                <img src="{{asset('uploads/images/product/'.$item->image)}}" style="height: 230px;"
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
                    @endif
                    @endforeach
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