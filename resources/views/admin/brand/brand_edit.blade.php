@extends('admin.layouts.master')

@section('head')
<title>Sửa thương hiệu</title>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ THƯƠNG HIỆU
                    <small>Sửa</small>
                </h1>
            </div>
            @if(count($errors))
            <div class="alert alert-danger">
                @foreach($errors->all() as $err)
                {{$err}} <br>
                @endforeach
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
            @endif
            <div class="col-lg-12">
                <p>
                    <a class="btn btn-primary" href="{{route('admin.brand.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="col-lg-7" style="padding-bottom:120px">
                <form action="{{ route('admin.brand.update', $brand->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label>Tên thương hiệu</label>
                        <input class="form-control" name="name_bra" value="{{$brand->name_bra}}"
                            placeholder="Hãy nhập tên thương hiệu" />
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Hình ảnh</label>
                            <input type="file" class="form-control" id="exampleInputEmail1" name="image"
                                accept="image/*" placeholder="Thêm ảnh">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default">Cập nhật</button>
                    <form>
            </div>
        </div>
    </div>
</div>

@endsection