@extends('admin.layouts.master')

@section('head')
<title>Thêm sản phẩm</title>
@endsection

@section('content')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"> THÊM SẢN PHẨM
                </h1>
            </div>
            @if(count($errors))
            <div class="alert alert-danger">
                @foreach($errors->all() as $err)
                {{$err}} <br>
                @endforeach
            </div>
            @endif
            <div class="col-lg-12" style="padding-bottom:120px">
                <form action="{{ route('admin.product.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="name_pr"
                            placeholder="Nhập tên">
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nhom_thuoc_id">Danh mục sản phẩm: </label>
                                <select class="form-control" style="width: 100%" name="category_id">
                                    <option value="" disabled selected>--- Chọn danh mục sản phẩm ---</option>
                                    @if (isset($category))
                                    @foreach($category as $item)
                                    <option value="{{$item->id}}">{{$item->name_cate}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Thương hiệu sản phẩm: </label>
                                <select name="brand_id" class="form-control" style="width: 100%">
                                    <option value="" disabled selected>--- Chọn thương hiệu sản phẩm ---</option>
                                    @if (isset($brand))
                                    @foreach($brand as $item)
                                    <option value="{{$item->id}}">{{$item->name_bra}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Số lượng</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" name="quantity"
                                    placeholder="Nhập số lượng">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Số lượng đã bán</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" name="sold" value="0"
                                    placeholder="Nhập số lượng đã bán">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Giá (VNĐ)</label>
                                <input type="number" class="form-control" id="exampleInputPassword1" name="price"
                                    placeholder="Nhập giá sản phẩm">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Giảm giá (VNĐ)</label>
                                <input type="number" class="form-control" name="discount" placeholder="Giảm giá còn..."
                                    value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hình ảnh đại diện</label>
                                    <input type="file" class="form-control" id="exampleInputEmail1" name="image"
                                        accept="image/*" placeholder="Thêm ảnh">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nhiều hình ảnh</label>
                                    <input type="file" class="form-control" name="pro_image[]" accept="image/*"
                                        placeholder="Thêm ảnh" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mô Tả</label>
                        <textarea name="description" style="width: 100%" id="editor1"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Trạng thái sản phẩm: </label>
                                <input type="hidden" name="status" value="0" />
                                <input type="checkbox" name="status" value="1"> Nổi bật
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Quà tặng</label>
                                <input class="form-control" name="gift" placeholder="Nhập quà tặng" />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="reset" class="btn btn-success">Làm mới</button>

                    <a class="btn btn-primary" href="{{ route('admin.product.index') }}"> </i>
                        Quay lại danh sách sản phẩm</a>
                    <form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    CKEDITOR.replace( 'editor1' );
</script>
@endsection