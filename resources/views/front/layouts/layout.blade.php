<!DOCTYPE html>
<html lang="en">
<!-- Basic -->

<head>
    @yield('head')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>Đồ gia dụng</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Site CSS -->
    <link rel="stylesheet" href="/front/assets/css/style.css">
    <!-- Responsive CSS -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/front/assets/css/custom.css">
    @yield('style')
</head>

<body>
    <!-- Start Main Top -->
    <div class="main-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="right-phone-box">
                        <p>Liên hệ : <a href="#"> 096 488 82 89</a></p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="login-box">
                        <ul>
                            <ul class="navbar-nav ml-auto ml-md-0">
                                @if (Auth::check() && Auth::user()->role == 3)
                                <li class="nav-item dropdown" style="background: #ffb307;">
                                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="color: black"><i
                                            class="fas fa-user fa-fw"></i>{{ Auth::user()->customer->name }}</a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="{{route('index.logout')}}">Đăng xuất</a>
                                    </div>
                                </li>
                                @else
                                <a class="dropdown-item" style="background-color: #ffb307"
                                    href="{{route('index.login')}}">Đăng nhập</a>
                                @endif
                            </ul>
                        </ul>
                    </div>
                    <div class="text-slid-box">
                        <div id="offer-box" class="carouselTicker">
                            <ul class="offer-box">
                                <li>
                                    <i class="fab fa-opencart"></i> Giảm giá ưu đãi lên đến 80%
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Bốc thăm trúng thưởng mừng sinh nhật MINHDANG
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Thanh lí các loại mặt hàng giảm giá sốc
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Hàng ngàn sản phẩm mới về
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Chào mừng bạn đến với MINHDANG
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Sự chuyên nghiệp và uy tín là tôn chỉ của chúng tôi
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Khách hàng mới là người quyết định tương lai, phát
                                    triển của MINHDANG
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Luôn hướng tới khách hàng, lắng nghe ý kiến, nhu cầu
                                    của khách hàng
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('main_top')

    <!-- End Main Top -->

    <!-- Start Main Top -->
    <header class="main-header">
        <!-- Start Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
            <div class="container">
                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu"
                        aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="{{route('index')}}" style="font-weight:700">MINGDANG.VN</a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->

                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class="nav-item"><a class="nav-link" href="{{route('index')}}">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('index.getAbout')}}">Giới thiệu</a></li>
                        <li class="nav-item"><a href="{{route('index.getProduct')}}" class="nav-link">Sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('index.getAccount')}}">Tài khoản</a></li>
                        <li class="side-menu">
                            <a class="nav-link" href="{{route('cart.index')}}">
                                <i class="fa fa-shopping-bag"></i>
                                @if (Session::has("Cart") != null)
                                <span class="badge"
                                    id="total-quanty-show">{{ Session::get("Cart")->totalQuanty }}</span>
                                @else
                                <span class="badge" id="total-quanty-show">0</span>
                                @endif
                                <p>Giỏ hàng</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->

                <!-- Start Atribute Navigation -->
                <div class="attr-nav">
                    <ul>
                        <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->
            </div>
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Main Top -->

    <!-- Start Top Search -->
    <div class="top-search">
        <div class="container">
            <form action="{{ route('index.getSearch') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" name="input_search" id="noi-dung" placeholder="Search">
                    <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                </div>
            </form>
        </div>
    </div>
    <!-- End Top Search -->

    <!-- Begin: Content -->
    <div id="content">
        @yield('mapping-layout')
        @yield('content')
    </div>
    <!-- End: Content -->

    <!-- Start Instagram Feed  -->
    <div class="instagram-box">
        @yield('feed')
    </div>
    <!-- End Instagram Feed  -->

    <!-- Start Footer  -->
    <footer>
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-widget">
                            <h4>Thời gian làm việc</h4>
                            <ul class="list-time">
                                <li>Tất cả các ngày trong tuần</li>
                                <li>Mở cửa: 8.00am - 22.00pm</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link">
                            <h4>Thông tin</h4>
                            <ul>
                                <li> <a href="{{route('index.getAbout')}}"> Về chúng tôi</a></li>
                                <li><a
                                        href="https://www.google.com/maps/place/TRUNG+T%C3%82M+TH%C6%AF%C6%A0NG+M%E1%BA%A0I+LONG+T%C6%A0+PLAZA/@19.6376275,105.6583645,17z/data=!3m1!4b1!4m5!3m4!1s0x3136fdae128f4037:0x5f98b5d45c1063ca!8m2!3d19.6376225!4d105.6605532?hl=vi-VN">Vị
                                        trí</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link-contact">
                            <h4>Liên hệ</h4>
                            <ul>
                                <li>
                                    <p><i class="fas fa-map-marker-alt"></i>Địa chỉ: đường 3/2, Quận Ninh Kiều, TP.Cần Thơ
                                    </p>
                                </li>
                                <li>
                                    <p><i class="fas fa-phone-square"></i>Phone: <a href="tel:096 488 82 89">096 488 82
                                            89</a></p>
                                </li>
                                <li>
                                    <p><i class="fas fa-envelope"></i>Email: <a
                                            href="mailto:minhdang@gmail.com">minhdang@gmail.com</a></p>
                                </li>
                                <li>
                                    <p><i class="fab fa-facebook"></i> <a
                                            href="https://www.facebook.com">Facebook</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer  -->

    <!-- Start copyright  -->

    <!-- End copyright  -->

    <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

    @include('front.partials.chatbot')

    <!-- ALL JS FILES -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="/front/assets/js/popper.min.js"></script>
    <script src="/front/assets/js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="/front/assets/js/jquery.superslides.min.js"></script>
    <script src="/front/assets/js/bootstrap-select.js"></script>
    <script src="/front/assets/js/inewsticker.js"></script>

    <script src="/front/assets/js/bootsnav.js"></script>
    <script src="/front/assets/js/images-loded.min.js"></script>
    <script src="/front/assets/js/isotope.min.js"></script>
    <script src="/front/assets/js/owl.carousel.min.js"></script>
    <script src="/front/assets/js/baguetteBox.min.js"></script>
    <script src="/front/assets/js/form-validator.min.js"></script>
    <script src="/front/assets/js/contact-form-script.js"></script>
    <script src="/front/assets/js/custom.js"></script>

    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- CSS -->

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />

    <script>
    function addCart(id) {
        let quantity = document.getElementById('quantity').value;

        $.ajax({
            url: '/cart/add-cart/' + id,
            type: 'GET', // tạm giữ, dù POST sẽ tốt hơn
            dataType: 'json',
            data: { quantity: quantity },
        })
        .done(function (res) {

            if (res.status === 'success') {
                RenderLishCart(res.html);
                alertify.success(res.message);

            } else if (res.status === 'partial') {
                RenderLishCart(res.html);
                alertify.warning(res.message);

            } else {
                alertify.error(res.message || 'Không thể thêm sản phẩm');
            }
        })
        .fail(function (xhr) {
            alertify.error(
                xhr.responseJSON?.message || 'Lỗi hệ thống, vui lòng thử lại'
            );
        });
    }

    function DeleteItemCart(id) {
        console.log(id)
        $.ajax({
            url: '/cart/delete-cart/' + id,
            type: 'GET',
        }).done(function(response) {
            RenderLishCart(response);
            alertify.success('Đã xóa sản phẩm thành công');
        })
    }

    function UpdateItemCart(id) {
        console.log($("#select-" + id).val())
        $.ajax({
            url: '/cart/update-cart/' + id + '/' + $("#select-" + id).val(),
            type: 'GET',
        }).done(function(response) {
            if (response) {
                RenderLishCart(response);
                alertify.success('Đã cập nhật sản phẩm thành công');
            } else {
                alertify.warning('Quá số lượng sản phẩm còn lại');
            }
        })
    }

    function RenderLishCart(response) {
        $("#list-cart").empty();
        $("#list-cart").html(response);
        $("#total-quanty-show").text($("#total-quanty-cart").val());
    }
    </script>
    @yield('script')
</body>

</html>
