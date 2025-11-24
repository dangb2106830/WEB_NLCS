@extends('front.layouts.layout')

@section('content')
<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">

        @yield('title')

    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Page  -->
<div class="shop-box-inner">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-lg-9 col-sm-12 col-xs-12 shop-content-right">
                <div class="right-product-box">
                    <div class="product-item-filter row">
                        <div class="col-12 col-sm-8 text-center text-sm-left">
                            <p>Hiển thị dạng</p>
                        </div>
                        <div class="col-12 col-sm-4 text-center text-sm-right">
                            <ul class="nav nav-tabs ml-auto">
                                <li>
                                    <a class="nav-link active" href="#grid-view" data-toggle="tab"> <i
                                            class="fa fa-th"></i> </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="#list-view" data-toggle="tab"> <i
                                            class="fa fa-list-ul"></i> </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- sản phẩm -->
                    @yield('content-pro')
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-sm-12 col-xs-12 sidebar-shop-left">
                <div class="product-categori">
                    <div class="search-product">
                        <form action="{{ route('index.getSearch') }}" method="GET">
                            <input class="form-control" placeholder="Search here..." type="text"  name="input_search" id="noi-dung">
                            <button type="submit"> <i class="fa fa-search"></i> </button>
                        </form>
                    </div>

                    <div class="filter-sidebar-left">
                        <div class="title-left">
                            <h3>Danh mục sản phẩm</h3>
                        </div>
                        <div class="list-group list-group-collapse list-group-sm list-group-tree" id="list-group-men"
                            data-children=".sub-men">
                            @foreach($category as $item)
                            <div class="list-group-collapse sub-men">
                                <a class="list-group-item list-group-item-action"
                                    href="{{ route('index.getCategory',['id'=>$item->id, 'slug'=>$item->slug]) }}">{{ $item->name_cate }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-sidebar-left">
                        <div class="title-left">
                            <h3>Thương hiệu sản phẩm</h3>
                        </div>
                        <div class="list-group list-group-collapse list-group-sm list-group-tree" id="list-group-men"
                            data-children=".sub-men">
                            @foreach($brand as $item)
                            <div class="list-group-collapse sub-men">
                                <a class="list-group-item list-group-item-action"
                                    href="{{ route('index.getBrand',['id'=>$item->id, 'slug'=>$item->slug]) }}">{{ $item->name_bra }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Shop Page -->

@endsection