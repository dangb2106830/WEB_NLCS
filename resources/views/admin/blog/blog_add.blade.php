@extends('admin.layouts.master')

@section('head')
    <title>Thêm bài viết</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ BÀI VIẾT
                    <small>Thêm</small>
                </h1>
            </div>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                @foreach ($errors->all() as $err)
                {{ $err }}<br>
                @endforeach
            </div>
            @endif
            <div class="col-lg-12">
                <p>
                    <a class="btn btn-primary" href="{{route('admin.blog.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('admin.blog.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tiêu đề</label>
                                        <input class="form-control" name="title" placeholder="Nhập tên bài viết" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tên người viết</label>
                                        <input class="form-control" name="author" placeholder="Nhập tên người viết" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Đoạn mở đầu</label>
                                <textarea id="editor3" name="intro" style="width: 100%"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nội dung</label>
                                <textarea id="editor2" name="content" style="width: 100%"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group" style="width: 40%;">
                                        <label for="ngay_tao">Ngày tạo</label>
                                        <input type="date" class="form-control" id="ngay_tao" name="created_date" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Hình ảnh</label>
                                        <input type="file" class="form-control" id="exampleInputEmail1" name="image" accept="image/*" placeholder="Thêm ảnh">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" style="background-color: blue;  color: white; " class="btn btn-default">Thêm bài viết</button>
                            <form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
    CKEDITOR.replace( 'editor2' );
    CKEDITOR.replace( 'editor3' );
</script>

@endsection
