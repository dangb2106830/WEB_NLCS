@extends('admin.layouts.master')

@section('head')
    <title>Sửa bài viết</title>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ BÀI VIẾT
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
                    <a class="btn btn-primary" href="{{route('admin.blog.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="col-lg-12" style="padding-bottom:120px">
                <form action="{{ route('admin.blog.update', $blog->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tiêu đề</label>
                                <input class="form-control" name="title" placeholder="Nhập tên bài viết" value="{{ $blog->title }}" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tên người viết</label>
                                <input class="form-control" name="author" placeholder="Nhập tên người viết" value="{{ $blog->author }}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Đoạn mở đầu</label>
                        <textarea id="editor7" name="intro" style="width: 100%">{{ $blog->intro}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nội dung</label>
                        <textarea id="editor6" name="content" style="width: 100%">{{ $blog->content}}</textarea>
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
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <label for="hinh_anh">Hình ảnh </label>
                                        <input type="file" class="custom-file-input" id="hinh_anh" name="image" placeholder="Không cần chọn nếu không thay đổi ảnh">
                                    </div>
                                </div>
                            </div>
                        </div>
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
    CKEDITOR.replace( 'editor6' );
    CKEDITOR.replace( 'editor7' );
</script>

@endsection
