 <!-- jQuery -->
 <script src="/dashboard/bower_components/jquery/dist/jquery.min.js"></script>

 <!-- Bootstrap Core JavaScript -->
 <script src="/dashboard/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

 <!-- Metis Menu Plugin JavaScript -->
 <script src="/dashboard/bower_components/metisMenu/dist/metisMenu.min.js"></script>

 <!-- Custom Theme JavaScript -->
 <script src="/dashboard/dist/js/sb-admin-2.js"></script>

 <!--check editer -->
 <script type="text/javascript" src="/dashboard/ckeditor/ckeditor.js"></script>

 <script type="text/javascript">
    CKEDITOR.replace( 'editor1' );
</script>
 <!-- DataTables JavaScript -->
 <script src="/dashboard/bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
 <script src="/dashboard/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js">
 </script>


 <!-- Page-Level Demo Scripts - Tables - Use for reference -->

 <!-- chart -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"
     integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ=="
     crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>



 <script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        responsive: true
    });
});

$('.btn-view').click(function(e) {
    var url = $(this).attr('data-url');
    $('#modal-view').modal('show');
    e.preventDefault();
    $.ajax({
        //phương thức get
        type: 'get',
        url: url,
        success: function(response) {
            data = response.data
            $('.hoa_don_id').text(data.id);
            $('#ngay_dat').text(data.created_date);
            $('#khach_hang').text(data.customer.name);
            $('#table-body').html('');
            totalPrice = 0;
            data.order_detail.forEach((element, index) => {
                if (element.product != null) {
                    html = `
                                            <tr>
                                                <td>${index+1}</td>
                                                <td>${element.product.name_pr}</td>
                                                <td>${element.quanty}</td>
                                                <td>${element.price} VNĐ</td>
                                                <td>${element.price*element.quanty} VNĐ</td>
                                            </tr>
                                        `
                } else {
                    html = `
                                            <tr>
                                                <td>${index+1}</td>
                                                <td>${element.quanty}</td>
                                                <td>${element.price} VNĐ</td>
                                                <td>${element.total_money} VNĐ</td>
                                            </tr>
                                        `
                }
                $('#table-body').append(html);
                totalPrice += element.price * element.quanty
            });

            $('#tong_tien').text(totalPrice + ' VNĐ');
        },
        error: function(error) {
            alert("Lỗi lấy dữ liệu!")
        }
    })
});
 </script>

 <script>
function ConfirmDelete() {
    var x = confirm("Bạn có muốn xoá?");
    if (x)
        return true;
    else
        return false;
}
 </script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    $(".alert").fadeTo(4500, 800).slideUp(800, function() {
        $(".alert").slideUp(800);
    });
</script>


<script>

const loadChart = () => {
    let url = '/admin/statistic/chart'
    console.log(url);
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function (response) {
            showChart(response.times, response.values);
        },
    })
}
loadChart()

const loadChart2 = () => {
    let url = '/admin/statistic/chart-2'
    console.log(url);
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function (response) {
            showChart2(response.times, response.values);
        },
    })
    
}
loadChart2()

const loadChart3 = () => {
    let url = '/admin/statistic/chart-3'
    console.log(url);
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function (data) {
            showChart3(data.times, data.values);
        },
    })
}
loadChart3()
</script>

