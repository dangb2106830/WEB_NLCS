<div class="row">
    <div class="col-lg-12">
        <div class="table-main table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Hủy</th>
                    </tr>
                </thead>
                <tbody>
                    @if (Session::has('Cart') != null)
                        @foreach (Session::get('Cart')->products as $item)
                            <tr>
                                <td class="thumbnail-img">
                                    <img class="img-fluid"
                                        src="{{ asset('uploads/images/product/' . $item['productInfo']->image) }}"
                                        alt="" />
                                </td>
                                <td class="name-pr" style="font-size: 20px;">
                                    {{ $item['productInfo']->name_pr }}
                                </td>
                                <td class="price-pr">
                                    @if ($item['productInfo']->discount > 0)
                                    <p><del style="color: chocolate;">{{number_format($item['productInfo']->price) }} VNĐ</p>
                                        <p>Giảm giá còn: {{ number_format($item['productInfo']->discount) }} VNĐ</p>
                                    @else
                                        <p>{{ number_format($item['productInfo']->price) }} VNĐ</p>
                                    @endif
                                </td>
                                <td><select name="quanty" id="select-{{ $item['productInfo']->id }}"
                                        data-idselect="{{ $item['productInfo']->id }}"
                                        onchange="UpdateItemCart({{ $item['productInfo']->id }})">
                                        @for ($i = 1; $i <= $item['productInfo']->quantity; $i++)
                                            <option value="{{ $i }}"
                                                @if ($i == $item['quanty']) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                </td>

                                <td class="remove-pr">
                                    <a href="#">
                                        <i class="fas fa-times" style="color: red; cursor: pointer;"
                                            onclick="DeleteItemCart({{ $item['productInfo']->id }})"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th colspan="6" style="text-align: center;">Bạn chưa đặt sản phẩm nào!</th>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row my-5">
    <div class="col-lg-8 col-sm-12">
    </div>
    <div class="col-lg-4 col-sm-12">
        @if (Session::has('Cart') != null && Session::get('Cart')->products)
            <?php $i = 1; ?>
            @foreach (Session::get('Cart')->products as $item)
                @if ($i < 2)
                    <div class="order-box">
                        <h3 style="    font-size: 30px;">Thanh toán</h3>
                        <div class="d-flex">
                            <h4>Số lượng sản phẩm</h4>
                            <div class="ml-auto font-weight-bold">
                                @if (isset(Session::get('Cart')->totalQuanty))
                                    {{ Session::get('Cart')->totalQuanty }}
                                @else
                                    0
                                @endif

                            </div>
                        </div>
                        <div class="d-flex gr-total">
                            <h5>Tổng tiền</h5>
                            <div class="ml-auto h5">
                                @if (isset(Session::get('Cart')->totalQuanty))
                                    {{ number_format(Session::get('Cart')->totalPrice) }} VNĐ
                                @else
                                    0
                                @endif
                            </div>
                        </div>

                    </div>
                @endif
                <?php $i++; ?>
            @endforeach
        @endif
    </div>
    <div class="col-12 d-flex shopping-box"><a href="{{ route('index.getCheckOut') }}"
            class="ml-auto btn hvr-hover">Thanh
            toán</a>
    </div>
