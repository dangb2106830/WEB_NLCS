@extends('admin.layouts.master')

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">DANH MỤC SẢN PHẨM
                    <small>Thêm</small>
                </h1>
            </div>
            @if(count($errors))
            <div class="alert alert-danger">
                @foreach($errors->all() as $err)
                {{$err}} <br>
                @endforeach
            </div>
            @endif
            <div class="col-lg-12">
                <p>
                    <a class="btn btn-primary" href="{{route('admin.category.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12" style="padding-bottom:120px">
                        <form action="{{route('admin.category.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Tên danh mục</label>
                                <input class="form-control" name="name_cate" placeholder="Nhập tên danh mục" />
                            </div>
                            <button type="submit" class="btn btn-default">Thêm danh mục</button>
                        <form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

@endsection