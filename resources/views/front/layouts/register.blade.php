<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link rel="stylesheet" href="/front/assets/style_register.css">

<div class="container register">
    <div class="row">
        <div class="col-md-3 register-left">
            <!-- <img src="https:/image.ibb.co/n7oTvU/logo_white.png" alt="" /> -->
            <h3>Chào mừng đến với Long Tơ</h3>
            <p>Đăng nhập ngay nào!</p>
            <button
                style="border-radius: 1.5rem; font-weight: 600; width: 50%; border: none; margin-top: 10%; padding: 2%;"><a
                    href="{{route('index.login')}}" style="color: black; font-family: revert;"> Đăng nhập
                </a></button>
        </div>

        <div class="col-md-9 register-right">
            @if (session('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session('message') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session('error') }}
            </div>
            @endif
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h3 class="register-heading">Đăng ký</h3>
                    <form action="{{ route('clients.postUserRegister') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row register-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Họ và tên" name="name"
                                        value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Địa chỉ" name="address"
                                        value="" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password"
                                        value="" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="confirm_password"
                                        placeholder="Xác nhận mật khẩu" name="passwordAgain" value="" required>
                                </div>
                                <div class="form-group">
                                    <div class="maxl">
                                        <label class="radio inline">
                                            <input type="radio" name="gender" value="Nam" checked>
                                            <span> Nam </span>
                                        </label>
                                        <label class="radio inline">
                                            <input type="radio" name="gender" value="Nữ">
                                            <span>Nữ </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" name="email"
                                        value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nhập mã xác thực"
                                        name="customer_verification_code_register" value="" />
                                </div>
                                <div class="form-group">
                                    <input type="text" minlength="10" maxlength="10" name="phone" class="form-control"
                                        placeholder="Số điện thoại" value="" />
                                </div>
                                <div class="form-group">
                                    <label for="ngay_sinh">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngay_sinh" name="birth_day" value=""
                                        autocomplete="off" />
                                </div>
                                <input type="submit" class="btnRegister" value="Đăng ký" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>