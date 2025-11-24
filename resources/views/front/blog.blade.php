@extends('front.layouts.layout')


@section('head')
<title>Bài viết</title>
@endsection

@section('content')
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Bài viết</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('index')}}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Bài viết</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="about-box-main">
    <div class="container">
        @if(isset($blo))
        <div class="row">
            <div class="col-lg-6">
                <div class="banner-frame"> <img class="img-fluid" style="height: 500px;"
                        src="{{asset('uploads/images/blog/'.$blo->image)}}" alt="" />
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="noo-sh-title-top">{!!$blo -> title!!}<span>{!!$blo -> intro!!}</span></h2>
                <p>{!!$blo -> content!!}</p>
            </div>
        </div>
        @endif
        <div class="row my-5">
            <div class="col-lg-12">
                <div>
                    <h2 class="noo-sh-title">Các bài viết</h2>
                </div>
                <div class="featured-products-box owl-carousel owl-theme">
                    @if (count($blog) != null)
                    @foreach($blog as $item)
                    <div class="item">
                        <div class="products-single fix">
                            <div class="hover-team">
                                <div class="our-team"> <a
                                        href="{{ route('index.getBlog',['blog_id'=>$item->id, 'blog_slug'=>$item->slug]) }}"><img
                                            src="{{asset('uploads/images/blog/'.$item->image)}}" style="height: 250px;"
                                            alt="" /></a>
                                    <div class="team-content">
                                        <h3 class="title">{!!$item->title!!}</h3>
                                    </div>
                                    <div class="icon"> <i class="fa fa-plus" aria-hidden="true"></i> </div>
                                </div>
                                <div class="team-description">
                                    <p>{!!$item->intro!!}</p>
                                </div>
                                <hr class="my-0">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    Không có bài viết nào
                    @endif
                </div>
            </div>
        </div>
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