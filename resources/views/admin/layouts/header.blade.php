<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{route('admin.index')}}">Trang quản trị</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-right navbar-top-links">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>{{ Auth::user()->staff->name }}<b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{route('admin.profile')}}"><i class="fa fa-user fa-fw"></i> Thông tin
                        tài khoản</a>
                </li>
                <li class="divider"></li>
                <li><a href="{{ route('admin.logout') }}"><i class="fa fa-sign-out fa-fw"></i> Đăng xuất</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{route('admin.index')}}"><i class="fa fa-dashboard fa-fw"></i> Trang chủ</a>
                </li>
                <li>
                    <a href="{{route('admin.category.index')}}"><i class="fa fa-cubes fa-fw"></i> Quản lý danh mục</a>
                </li>
                <li>
                    <a href="{{route('admin.brand.index')}}"><i class="fa fa-share-square-o fa-fw"></i> Quản lý thương hiệu</a>
                </li>
                <li>
                    <a href="{{route('admin.product.index')}}"><i class="fa fa-cube fa-fw"></i> Quản lý sản phẩm</a>
                </li>
                <li>
                    <a href="{{route('admin.blog.index')}}"><i class="fa fa-edit fa-fw"></i> Quản lý bài viết</a>
                </li>
                <li>
                    <a href="{{route('admin.slide.index')}}"><i class="fa fa-camera fa-fw"></i> Quản lý slide</a>
                </li>
                <li>
                    <a href="{{route('admin.checkout.index')}}"><i class="fa fa-camera fa-fw"></i> Quản lý đơn đặt hàng</a>
                </li>
                <li>
                    <a href="{{route('admin.customer.index')}}"><i class="fa fa-users fa-fw"></i> Quản lý khách hàng</a>
                </li>
                @if (Auth::user()->role == 1)
                <li>
                    <a href="{{route('admin.staff.index')}}"><i class="fa fa-users fa-fw"></i> Quản lý nhân viên</a>
                </li>
                
                <li>
                    <a href="{{ route('admin.statistic.index') }}"><i class="fa fa-group fa-fw"></i> Thống kê</a>
                </li>
                @endif
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>