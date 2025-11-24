@extends('admin.layouts.master')

@section('head')
    <title>Danh sách sản phẩm</title>
@endsection

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.bootstrap.min.css">
<style>
    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        margin: 0;
    }
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ SẢN PHẨM
                    <small>Danh sách</small>
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
            @if (session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('success') }}
            </div>
            @endif
            @if(session('false'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('false') }}
            </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <p>
                        <a class="btn btn-primary" href="{{ route('admin.product.create') }}"> <i class="fa fa-plus"></i>
                            Thêm mới</a>
                    <p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách sản phẩm</b>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="table-ncc">
                                <table class="table table-striped table-bordered table-hover display nowrap" style="width:100%" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Chức năng</th>
                                            <th>Tên</th>
                                            <th>Giá</th>
                                            <th>Danh mục</th>
                                            <th>Thương hiệu</th>
                                            <th>Số lượng</th>
                                            <th>Đã bán</th>
                                            <th>Giảm giá</th>
                                            <th>Mô tả</th>
                                            <th>Ảnh sản phẩm</th>
                                            <th>Quà</th>
                                            <th>Trạng thái</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody style=" text-align: center;">
                                        <?php $i = 1; ?>
                                        @foreach($products as $product)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td style="text-align:center;">
                                                <span>
                                                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-warning btn-xs "> <i class="fa fa-edit"></i> Cập nhật</a>
                                                    <a href="{{route('admin.product.destroy', $product->id)}}"  onclick="return ConfirmDelete()" class="btn btn-danger btn-xs "><i class="fa fa-trash"></i> Xoá</a>
                                                </span>
                                            </td>
                                            <td>{{$product->name_pr}}</td>
                                            <td>{{$product->price}}</td>
                                            <td> @if ($product->category)
                                                {{ $product->category->name_cate }}
                                                @else
                                                Không tồn tại
                                                @endif
                                            </td>
                                            <td>{{$product->brand->name_bra}}</td>
                                            <td>{{$product->quantity}}</td>
                                            <td>{{$product->sold}}</td>
                                            <td>{{$product->discount}}</td>
                                            <td>{!!$product->description!!}</td>
                                            <td class="">
                                                <img src="{{asset('uploads/images/product/'.$product->image)}}" alt="" srcset="" width="120px" height="120px">
                                            </td>
                                            <td>{{$product->gift}}</td>
                                            <td>{{$product->status}}</td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection