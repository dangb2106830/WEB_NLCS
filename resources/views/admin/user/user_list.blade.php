@extends('admin.layouts.master')

@section('content')

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Quản lí khách hàng</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Danh sách tài khoản khách hàng
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-admin">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên khách hàng</th>
                                        <th>Email</th>
                                        <th>Giới tính</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày sinh</th>
                                        <th>Số điện thoại</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                    @foreach ($customer as $cus)
                                    <tr class="text-align:center">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $cus->name }}</td>
                                        <td>{{ $cus->user->email }}</td>
                                        <td>{{ $cus->gender}}</td>
                                        <td>{{ $cus->address}}</td>
                                        <td>{{ date('d/m/Y', strtotime($cus->birth_day)) }}</td>
                                        <td>{{ $cus->phone }}</td>
                                    </tr>
                                    @endforeach
                             
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