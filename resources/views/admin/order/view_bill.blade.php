<div class="modal fade" id="modal-view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Chi tiết hoá đơn  <span class="hoa_don_id"></span></h4>
            </div>
            <p>
            <h4 style="margin-left: 30px;">Mã hoá đơn: <span class="hoa_don_id" style="color:red;"></span></h4>
            </p>
            <p>
            <h4 style="margin-left: 30px;">Ngày đặt: <span id="ngay_dat" style="color:red;"></span></h4>
            </p>
            <p>
            <h4 style="margin-left: 30px;">Khách hàng: <span id="khach_hang" style="color:red;"></span></h4>
            </p>
            <p>
                <h4 style="margin-left: 30px;">Tổng tiền: <span id="tong_tien" style="color:red;"></span></h4>
                </p>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="table-admin">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
