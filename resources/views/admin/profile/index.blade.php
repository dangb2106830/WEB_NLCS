@extends('admin.layouts.master')

@section('head')
<title>Thông tin nhân viên</title>
@endsection

@section('content')

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">THÔNG TIN NHÂN VIÊN</h1>
            </div>
        </div>
        @if(session('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            {{session('success')}}
        </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin nhân viên
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.staff.update',  ['id' => Auth::user()->id]) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="ho_ten">Họ tên</label>
                        <input type="text" class="form-control" id="ho_ten" placeholder="Họ và tên" name="name"
                            value="{{ Auth::user()->staff->name }}" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email"
                            value="{{ Auth::user()->email }}" autocomplete="off" disabled />
                    </div>

                    <div class="form-group">
                        <label for="dien_thoai">Điện thoại</label>
                        <input type="text" class="form-control" id="dien_thoai" placeholder="Số điện thoại" name="phone"
                            value="{{ Auth::user()->staff->phone }}" autocomplete="off" />
                    </div>

                    <div class="form-group" style="width: 40%;">
                        <label for="ngay_sinh">Ngày sinh</label>
                        <input type="date" class="form-control" id="ngay_sinh" name="birth_day"
                            value="{{ Auth::user()->staff->birth_day }}" autocomplete="off" />
                    </div>

                    <div class="form-group" style="width: 40%;">
                        <label for="gioi_tinh">Giới tính</label>
                        <select class="form-control" name="gender" id="gioi_tinh">
                            <option value="Nam">Nam</option>
                            <option value="Nữ" @if (Auth::user()->staff->gender == "Nữ") selected @endif>Nữ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dia_chi">Địa chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="address"
                            value="{{ Auth::user()->staff->address }}" placeholder="Địa chỉ" />
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>

@endsection