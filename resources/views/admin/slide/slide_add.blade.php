@extends('admin.layouts.master')

@section('head')
    <title>Thêm slide</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ SLIDE
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
                    <a class="btn btn-primary" href="{{route('admin.slide.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('admin.slide.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Tên slide</label>
                                <input class="form-control" name="name_slide" placeholder="Nhập tên slide" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nội dung</label>
                                <textarea id="editor4" name="content" style="width: 100%"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Hình ảnh</label>
                                <input type="file" class="form-control" id="exampleInputEmail1" name="image" accept="image/*" placeholder="Thêm ảnh">
                            </div>
                            <button type="submit" style="background-color: blueviolet;  color: white; " class="btn btn-default">Thêm slide</button>
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
    CKEDITOR.replace( 'editor4' );
</script>

@endsection