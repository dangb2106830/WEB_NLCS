<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('head')

    @include('admin.layouts.style')

    @stack('styles')
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        @include('admin.layouts.header')

        <!-- Page Content -->
        @yield('content')
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    @include('admin.layouts.script')

    
    @stack('scripts')
    @yield('script')
</body>

</html>