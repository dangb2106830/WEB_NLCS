@extends('admin.layouts.master')

@section('head')
    <title>Danh sách thương hiệu</title>
@endsection


@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ THƯƠNG HIỆU
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
                            <th>Tên thương hiệu</th>
                            <th>Hình ảnh</th>
                            <th>Xóa</th>
                            <th>Sửa</th>
                        </tr>
                    </thead>
                    <tbody style=" text-align: center;" >
                        <?php $i = 1; ?>
                        @foreach($brands as $brand)
                        <tr class="odd gradeX">
                            <td>{{ $i++ }}</td>
                            <td>{{$brand->name_bra}}</td>
                            <td><img src="{{asset('uploads/images/brand/'.$brand->image)}}" alt="" srcset="" width="120px" height="120px"></td>
                            <td class="center">
                                <a class="btn btn-danger btn-xs" href="{{route('admin.brand.destroy', $brand->id)}}" onclick="return ConfirmDelete()"><i class="fa fa-trash-o  fa-fw"></i> Xóa</a>
                            </td>
                            <td class="center">
                                <a class="btn btn-success btn-xs btn-edit" href="{{route('admin.brand.edit', $brand->id, $brand->id)}}"><i class="fa fa-pencil fa-fw"></i>Sửa</a>
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
                            THÊM THƯƠNG HIỆU MỚI
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
                                        <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label>Tên danh mục</label>
                                                <input class="form-control" name="name_bra" placeholder="Nhập tên danh mục" />
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Hình ảnh</label>
                                                    <input type="file" class="form-control" id="exampleInputEmail1" name="image" accept="image/*" placeholder="Thêm ảnh">
                                                </div>
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
