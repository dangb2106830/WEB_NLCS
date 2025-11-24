@extends('admin.layouts.master')

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ THƯƠNG HIỆU
                    <small>Thêm</small>
                </h1>
            </div>
            <div class="col-lg-12">
                <p>
                    <a class="btn btn-primary" href="{{route('admin.brand.index')}}">Quay lại danh sách</a>
                <p>
            </div>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                @foreach ($errors->all() as $err)
                {{ $err }}<br>
                @endforeach
            </div>
            @endif
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('admin.brand.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Tên thương hiệu</label>
                                <input class="form-control" name="name_bra" placeholder="Nhập tên thương hiệu" />
                            </div>
                            <button type="submit" class="btn btn-default">Thêm thương hiệu</button>
                            <form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection