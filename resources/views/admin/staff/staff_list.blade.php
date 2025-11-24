@extends('admin.layouts.master')

@section('head')
    <title>Danh sách nhân viên</title>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Danh sách tài khoản nhân viên</h1>
            </div>
        </div>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            @foreach ($errors->all() as $err)
            {{ $err }}<br>
            @endforeach
        </div>
        @endif
        @if (session('thongbao'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('thongbao') }}
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <p>
                    <a class="btn btn-primary" href="{{ route('admin.staff.create') }}"> <i class="fa fa-plus"></i>
                        Thêm mới</a>
                <p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Danh sách tài khoản nhân viên
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-admin">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Địa chỉ</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($user))
                                    @php $i = 1
                                    @endphp
                                    @foreach ($user as $item)
                                    <tr class="odd gradeX">
                                        <td style="width: 80px; text-align: center;">{{ $i++ }}
                                        </td>
                                        <td>{{ $item->staff->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->staff->phone }}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->staff->birth_day)) }}</td>
                                        <td>{{ $item->staff->gender}}</td>
                                        <td>{{ $item->staff->address}}</td>
                                        <td>
                                            <a class="btn btn-danger btn-xs"  onclick="return ConfirmDelete()" href="{{ route('admin.staff.destroy', ['id' => $item->id]) }}" ​><i class="fa fa-edit"></i> Xoá</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>
    <!-- /.container-fluid -->
</div>

@endsection