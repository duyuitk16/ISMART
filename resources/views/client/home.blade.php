@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            <div class="main-content fl-right">
                <div class="section" id="slider-wp">
                    <div class="section-detail">
                        <div class="item">
                            <img src="public/client/images/slider_1.png" alt="">
                        </div>
                        <div class="item">
                            <img src="public/client/images/slider_2.png" alt="">
                        </div>
                        <div class="item">
                            <img src="public/client/images/slider_3.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="thumb">
                                    <img src="public/client/images/icon-1.png">
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/client/images/icon-2.png">
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">1900.9999</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/client/images/icon-3.png">
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/client/images/icon-4.png">
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/client/images/icon-5.png">
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="section" id="feature-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm nổi bật</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($outstanding_products as $product)
                                <li>
                                    <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                        title="" class="thumb">
                                        <img style="height: 128px; width:189px"
                                            src="{{ asset("uploads/product/$product->thumbnail") }}">
                                    </a>
                                    <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                        title="" class="product-name">{{ Str::of($product->name)->limit(20) }}</a>
                                    <div class="price">
                                        <span
                                            class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        <span
                                            class="old">{{ number_format($product->old_price, 0, ',', '.') }}đ</span>
                                    </div>
                                    <div class="action clearfix">
                                        <a href="{{ url("gio-hang/them/$product->slug") }}" title=""
                                            class="add-cart fl-left">Thêm giỏ hàng</a>
                                        <a href="{{ url("gio-hang/them/$product->slug?buy_now=true") }}" title=""
                                            class="buy-now fl-right">Mua ngay</a>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                @foreach ($parent_product_cats as $product_cat)
                    <div class="section" id="list-product-wp">
                        <div class="section-head">
                            <h3 class="section-title">{{ $product_cat->name }}</h3>
                        </div>
                        <div class="section-detail">
                            <ul class="list-item clearfix">
                                @foreach ($list_products_by[$product_cat->id] as $product)
                                    <li>
                                        <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                            title="" class="thumb">
                                            <img style="height:190px"
                                                src="{{ asset("uploads/product/$product->thumbnail") }}">
                                        </a>
                                        <a href=" {{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                            title="" class="product-name">{{ Str::of($product->name)->limit(20) }}</a>
                                        <div class="price">
                                            <span
                                                class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            <span
                                                class="old">{{ number_format($product->old_price, 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ url("gio-hang/them/$product->slug") }}" title="Thêm giỏ hàng"
                                                class="add-cart fl-left">Thêm giỏ
                                                hàng</a>
                                            <a href="{{ url("gio-hang/them/$product->slug?buy_now=true") }}"
                                                title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="sidebar fl-left">
                @include('layouts.client_category_product', [
                    'product_cats' => $product_cats,
                ])
                @include('layouts.client_selling', [
                    'selling_products' => $selling_products,
                ])
                @include('layouts.client_banner')
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
