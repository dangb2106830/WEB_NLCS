@extends('admin.layouts.master')

@section('head')
    <title>Sửa danh mục</title>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">DANH MỤC SẢN PHẨM
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
                    <a class="btn btn-primary" href="{{route('admin.category.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="col-lg-7" style="padding-bottom:120px">
                <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <input class="form-control" name="name_cate" value="{{$category->name_cate}}" placeholder="Nhập tên danh mục" />
                    </div>
                    <button type="submit" class="btn btn-default">Cập nhật</button>
                <form>
            </div>
        </div>
    </div>
</div>

@endsection
