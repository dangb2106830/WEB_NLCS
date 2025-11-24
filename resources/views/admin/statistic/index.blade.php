@extends('admin.layouts.master')

@section('head')
<title>Thống kê</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Thống kê</h1>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading" style="background-color: darkorange;">
                            <div class="row">
                                <div class="huge" style="font-size: 20px;text-align: center;">Doanh thu
                                    {{number_format($profit)}} VNĐ </div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading" style="background-color: yellowgreen;">
                            <div class="row">
                                <div class="huge" style="font-size: 20px;text-align: center;">Số lượng hàng đã bán:
                                    {{number_format($sold)}} </div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Thống kê tổng tiền hoá đơn theo ngày</b>
                        </div>
                        <div class="panel-body">
                            <div class="chartWrapper">
                                <div class="chartAreaWrapper">
                                    <canvas id="myChart"></canvas>
                                    <script src="{{asset('dashboard/js/chart.js')}}"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Thống kê số lượng sản phẩm theo Loại sản phẩm</b>
                        </div>
                        <div class="panel-body">
                            <div class="chartWrapper">
                                <div class="chartAreaWrapper">
                                    <canvas id="myChart2"></canvas>
                                    <script src="{{asset('dashboard/js/chart.js')}}"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Thống kê số lượng đơn hàng theo tháng</b>
                        </div>
                        <div class="panel-body">
                            <div class="chartWrapper">
                                <div class="chartAreaWrapper">
                                    <canvas id="myChart4"></canvas>
                                    <script src="{{asset('dashboard/js/chart.js')}}"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Thống kê số lượng sản phẩm theo thương hiệu sản
                            phẩm</b>
                        </div>
                        <div class="panel-body">
                            <div class="chartWrapper">
                                <div class="chartAreaWrapper">
                                    <canvas id="myChart3"></canvas>
                                    <script src="{{asset('dashboard/js/chart.js')}}"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
const ctx = document.getElementById('myChart4').getContext('2d');
var data = <?= json_encode($data); ?>;
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
            'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ],
        datasets: [{
            label: 'Thống kê đặt hàng',

            data: data,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection