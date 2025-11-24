@extends('admin.layouts.master')

@section('head')
    <title>Danh sách slide</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ SLIDE
                    <small>Danh sách</small>
                </h1>
            </div>
            @if (session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('success') }}
            </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <p>
                        <a class="btn btn-primary" href="{{ route('admin.slide.create') }}"> <i class="fa fa-plus"></i>
                            Thêm mới</a>
                    <p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách slide</b>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="table-ncc">
                                <table class="table table-striped table-bordered table-hover display nowrap" style="width:100%" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên slide</th>
                                            <th>Nội dung</th>
                                            <th>Hình ảnh</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody style=" text-align: center;">
                                        <?php $i = 1; ?>
                                        @foreach($slides as $slide)
                                        <tr class="odd gradeX">
                                            <td>{{ $i++ }}</td>
                                            <td>{{$slide->name_slide}}</td>
                                            <td>{!!$slide->content!!}</td>
                                            <td class="">
                                                <img src="{{asset('uploads/images/slide/'.$slide->image)}}" alt="" srcset="" width="120px" height="120px">
                                            </td>
                                            <td class="center">
                                                <a class="btn btn-success btn-xs btn-edit"  href="{{route('admin.slide.edit', $slide->id, $slide->id)}}"><i class="fa fa-pencil fa-fw"></i>Cập nhật</a>
                                                <a class="btn btn-danger btn-xs"  onclick="return ConfirmDelete()" href="{{route('admin.slide.destroy', $slide->id)}}"><i class="fa fa-trash-o  fa-fw"></i> Xóa</a>
                                            </td>
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