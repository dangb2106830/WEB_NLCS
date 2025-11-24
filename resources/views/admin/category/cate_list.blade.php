@extends('admin.layouts.master')

@section('head')
    <title>Danh sách sản phẩm</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">DANH MỤC SẢN PHẨM
                    <small>Danh sách</small>
                </h1>
            </div>
            @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{session('success')}}
            </div>
            @endif
            @if(session('false'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('false') }}
            </div>
            @endif
            <div class="col-lg-8" style="padding-bottom:120px">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Xóa</th>
                            <th>Sửa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($categories as $category)
                        <tr class="odd gradeX" style="text-align: center;">
                            <td style=" width: 30px; text-align: center;">{{ $i++ }}</td>
                            <td>{{$category->name_cate}}</td>
                            <td class="center">
                                <a class="btn btn-danger btn-xs" onclick="return ConfirmDelete()" href="{{route('admin.category.destroy', $category->id)}}"><i class="fa fa-trash-o  fa-fw"  ></i> Xóa</a>
                            </td>
                            <td class="center">
                                <a class="btn btn-success btn-xs btn-edit" href="{{route('admin.category.edit', $category->id, $category->id)}}"><i class="fa fa-pencil fa-fw"></i>Sửa</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="form-add open-form">
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            THÊM DANH MỤC MỚI
                        </div>
                        @if(count($errors))
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $err)
                            {{$err}} <br>
                            @endforeach
                        </div>
                        @endif
                        <div class="panel-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12" style="padding-bottom:120px">
                                        <form action="{{ route('admin.category.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Tên danh mục</label>
                                                <input class="form-control" name="name_cate" placeholder="Nhập tên danh mục" />
                                            </div>
                                            <button style="background-color: gray;  color: white; " type="submit" class="btn btn-default">Thêm danh mục</button>
                                            <form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection