@extends('admin.layouts.master')

@section('head')
<title>Sửa sản phẩm</title>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"> SỬA SẢN PHẨM
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
                <form action="{{ route('admin.product.update', $product->id)}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="ten_sp">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="ten_sp" name="name_pr" placeholder="Nhập tên"
                            value="{{ $product->name_pr }}">
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Danh mục sản phẩm: </label>
                                <select name="category">
                                    <option value="" disabled selected>--- Chọn danh mục sản phẩm ---</option>
                                    @foreach($category as $item)
                                    <option value="{{$item->id}}" @if($product -> category_id == $item->id) selected
                                        @endif>
                                        {{$item->name_cate}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Thương hiệu sản phẩm: </label>
                                <select name="brand" id="">
                                    <option value="" disabled selected>--- Chọn thương hiệu sản phẩm ---</option>
                                    @foreach($brand as $item)
                                    <option value="{{$item->id}}" @if($product -> brand_id == $item->id) selected
                                        @endif>
                                        {{$item->name_bra}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Số lượng</label>
                                <input type="number" class="form-control" id="so_luong" name="quantity"
                                    placeholder="Nhập số lượng" value="{{ $product->quantity }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Số lượng đã bán</label>
                                <input type="number" class="form-control" id="da_ban" name="sold"
                                    placeholder="Nhập số lượng đã bán" value="{{ $product->sold }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Giá (VNĐ)</label>
                                <input type="number" class="form-control" id="gia" name="price"
                                    placeholder="Nhập giá sản phẩm" value="{{ $product->price }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Giảm giá (VNĐ)</label>
                                <input type="number" class="form-control" name="discount" placeholder="Nhập mã giảm giá"
                                    value="{{ $product->discount }}" />
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
                                    <input type="file" class="form-control" id="hinhanh" name="pro_image[]"
                                        placeholder="Thêm ảnh" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mô Tả</label>
                        <textarea id="editor" name="description" style="width:100%">{!!$product->description!!}</textarea>
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
                                <input class="form-control" name="gift" placeholder="Nhập quà tặng"
                                    value="{{ $product->gift }}" />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <button type="reset" class="btn btn-success">Làm mới</button>
                    <form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
    CKEDITOR.replace( 'editor' );
</script>
@endsection