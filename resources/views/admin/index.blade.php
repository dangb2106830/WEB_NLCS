@extends('admin.layouts.master')

@section('head')
    <title>Trang chủ</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Trang chủ</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-cubes fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px; text-align: center;">DANH MỤC {{$category->count()}}</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.category.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-cube fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px;text-align: center;"> {{$product->count()}} SẢN PHẨM</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.product.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-share-square-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px;text-align: center;"> {{$brand->count()}} THƯƠNG HIỆU</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.brand.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-edit fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px;text-align: center;"> {{$blog->count()}} BÀI VIẾT</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.blog.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading" style="background-color: brown;">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-camera fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px;text-align: center;"> {{$slide->count()}} SLIDE</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.slide.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading" style="background-color: coral;">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-paste fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px;text-align: center;"> {{$order->count()}} ĐƠN HÀNG</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.checkout.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading" style="background-color: darkorange;">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-group fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 20px;text-align: center;"> {{$customer->count()}} TK KHÁCH HÀNG</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.checkout.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

            @if (Auth::user()->role == 1)
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading" style="background-color: mediumblue;">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-group fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size: 25px"> {{$staff->count()}} NHÂN VIÊN</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.checkout.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">Truy cập</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection