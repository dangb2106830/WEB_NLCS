@extends('admin.layouts.master')

@section('head')
    <title>Sửa slide</title>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Thương hiệu sản phẩm
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
                    <a class="btn btn-primary" href="{{route('admin.slide.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="col-lg-7" style="padding-bottom:120px">
                <form action="{{ route('admin.slide.update', $slide->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label>Tên slide</label>
                        <input class="form-control" name="name_slide" placeholder="Nhập tên slide" value="{{$slide->name_slide}}"/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nội dung</label>
                        <textarea id="editor5" name="content" style="width: 100%">{{$slide->content}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Hình ảnh</label>
                        <input type="file" class="form-control" id="exampleInputEmail1" name="image" accept="image/*" placeholder="Thêm ảnh">
                    </div>
                    <button type="submit" style="background: limegreen;color: white;" class="btn btn-default">Cập nhật</button>
                    <form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

<script type="text/javascript">
    CKEDITOR.replace( 'editor5' );
</script>

@endsection