@extends('admin.layouts.master')

@section('head')
    <title>Sửa nhân viên</title>
@endsection

@section('content')

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sửa nhân viên</h1>
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


        <div class="panel panel-default">
            <div class="panel-heading">
                Sửa nhân viên
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.staff.update', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="ho_ten">Họ tên</label>
                        <input type="text" class="form-control" id="ho_ten" placeholder="Họ và tên" name="name" value="{{ $user->staff->name }}" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ $user->email }}" autocomplete="off" disabled />
                    </div>

                    <div class="form-group">
                        <label for="dien_thoai">Điện thoại</label>
                        <input type="text" class="form-control" id="dien_thoai" placeholder="Số điện thoại" name="phone" value="{{ $user->staff->phone }}" autocomplete="off" />
                    </div>

                    <div class="form-group" style="width: 40%;">
                        <label for="ngay_sinh">Ngày sinh</label>
                        <input type="date" class="form-control" id="ngay_sinh" name="birth_day" value="{{ $user->staff->birth_day }}" autocomplete="off" />
                    </div>

                    <div class="form-group" style="width: 40%;">
                        <label for="gioi_tinh">Giới tính</label>
                        <select class="form-control" name="gender" id="gioi_tinh">
                            <option value="Nam">Nam</option>
                            <option value="Nữ" @if ($user->staff->gender == "Nữ") selected @endif>Nữ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dia_chi">Địa chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="address" value="{{ $user->staff->address }}" placeholder="Địa chỉ" />
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">Cập nhật</button>
                    <button type="reset" class="btn btn-default">Nhập lại</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>

@endsection