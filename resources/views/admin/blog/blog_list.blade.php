@extends('admin.layouts.master')

@section('head')
    <title>Danh sách bài viết</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ BÀI VIẾT
                    <small>Danh sách</small>
                </h1>
            </div>
            @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('success') }}
            </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <p>
                        <a class="btn btn-primary" href="{{ route('admin.blog.create') }}"> <i class="fa fa-plus"></i>
                            Thêm mới</a>
                    <p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách bài viết</b>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="table-ncc">
                                <table class="table table-striped table-bordered table-hover display nowrap" style="width:100%" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên bài viết</th>
                                            <th>Đoạn mở đầu</th>
                                            <th>Nội dung</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên người viết</th>
                                            <th>Ngày đăng</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody style=" text-align: center;">
                                        <?php $i = 1; ?>
                                        @foreach($blogs as $blog)
                                        <tr class="odd gradeX">
                                            <td>{{ $i++ }}</td>
                                            <td>{{$blog->title}}</td>
                                            <td>{!!$blog->intro!!}</td>
                                            <td>{!!$blog->content!!}</td>
                                            <td class="">
                                                <img src="{{asset('uploads/images/blog/'.$blog->image)}}" alt="" srcset="" width="120px" height="120px">
                                            </td>
                                            <td>{{$blog->author}}</td>
                                            <td>{{$blog->created_date}}</td>
                                            <td style="text-align:center;">
                                                <span>
                                                    <a class="btn btn-danger btn-xs" href="{{route('admin.blog.destroy', $blog->id)}}"  onclick="return ConfirmDelete()"><i class="fa fa-trash-o  fa-fw"></i> Xóa</a>
                                                    <a class="btn btn-success btn-xs btn-edit" href="{{route('admin.blog.edit', $blog->id, $blog->id)}}"><i class="fa fa-pencil fa-fw"></i>Sửa</a>
                                                </span>
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