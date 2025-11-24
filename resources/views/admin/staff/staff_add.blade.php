@extends('admin.layouts.master')

@section('head')
    <title>Thêm nhân viên</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">QUẢN LÝ NHÂN VIÊN
                    <small>Thêm nhân viên</small>
                </h1>
            </div>
            <div class="col-lg-7" style="padding-bottom:120px">
                <form action="{{ route('admin.staff.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="ho_ten">Họ tên</label>
                        <input type="text" class="form-control" id="ho_ten" placeholder="Họ và tên" name="name" value="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="dien_thoai">Điện thoại</label>
                        <input type="text" class="form-control" id="dien_thoai" placeholder="Số điện thoại" name="phone" value="" autocomplete="off" />
                    </div>
                    <div class="form-group" style="width: 40%;">
                        <label for="ngay_sinh">Ngày sinh</label>
                        <input type="date" class="form-control" id="ngay_sinh" name="birth_day" value="" autocomplete="off" />
                    </div>
                    <div class="form-group" style="width: 40%;">
                        <label for="gioi_tinh">Giới tính</label>
                        <select class="form-control" name="gender" id="gioi_tinh">
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dia_chi">Địa chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="address" value="" placeholder="Địa chỉ" />
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" placeholder="Mật khẩu" name="password" value="" required autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" id="confirm_password" placeholder="Xác nhận mật khẩu" name="passwordAgain" value="" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Thêm</button>
                    <button type="reset" class="btn btn-default">Nhập lại</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection