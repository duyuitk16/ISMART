@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix detail-product-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('trang-chu') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="{{ url('san-pham') }}" title="">Sản phẩm</a>
                        </li>
                        <li>
                            <a href="{{ url("san-pham/$this_cat->slug") }}" title="">{{ $this_cat->name }}</a>
                        </li>
                        <li>
                            <a href="{{ url("san-pham/$this_cat->slug/$this_product->slug.html") }}"
                                title="">{{ $this_product->name }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-product-wp">
                    <div class="section-detail clearfix">
                        <div class="thumb-wp fl-left">
                            <div id="main-thumb">
                                <img id="zoomImage" width="350px"
                                    src="{{ asset("uploads/product/$this_product->thumbnail") }}">
                            </div>
                            <div id="list-thumb">
                                <a href="">
                                    <img style="width: 62px; height:62px" class="active"
                                        src="{{ asset("uploads/product/$this_product->thumbnail") }}" />
                                </a>
                                <a href="">
                                    <img style="width: 62px; height:62px"
                                        src="{{ asset('client/images/img-pro-02.png') }}" />
                                </a>
                                <a href="">
                                    <img style="width: 62px; height:62px"
                                        src=" {{ asset('client/images/img-pro-11.png') }}" />
                                </a>
                                <a href="">
                                    <img style="width: 62px; height:62px"
                                        src="{{ asset('client/images/img-pro-17.png') }}" />
                                </a>
                                <a href="">
                                    <img style="width: 62px; height:62px"
                                        src=" {{ asset('client/images/img-pro-06.png') }}" />
                                </a>
                                <a href="">
                                    <img style="width: 62px; height:62px"
                                        src="{{ asset('client/images/img-pro-08.png') }}" />
                                </a>
                                <a href="">
                                    <img style="width: 62px; height:62px"
                                        src=" {{ asset('client/images/img-pro-21.png') }}" />
                                </a>
                            </div>
                        </div>
                        <div class="thumb-respon-wp fl-left">
                            <img src="{{ asset("uploads/product/$this_product->thumbnail") }}" alt="">
                        </div>
                        <form action="{{ route('add.cart', $this_product->slug) }}" method="GET">
                            <div class="info fl-right">
                                <h3 class="product-name font-weight-bold">{{ $this_product->name }}</h3>
                                <div class="desc m-0">
                                    {!! $this_product->description !!}
                                </div>
                                <div class="num-product">
                                    <span class="title">Sản phẩm: </span>
                                    <span
                                        class="status">{{ $this_product->status == 'on' ? 'Còn hàng' : 'Hết hàng' }}</span>
                                </div>
                                <p class="price">{{ number_format($this_product->price, 0, ',', '.') }}đ</p>
                                <div id="num-order-wp">
                                    <a title="" id="minus"><i class="fa fa-minus"></i></a>
                                    <input type="number" name="num_order" value="1" id="num-order" min="1"
                                        max="{{ $this_product->amount }}">
                                    <a title="" id="plus"><i class="fa fa-plus"></i></a>
                                </div>
                                <button class="add-cart btn {{ $this_product->status == 'off' ? 'dis' : '' }}"
                                    name="btn_add_cart " value="add_cat">Thêm giỏ
                                    hàng</button>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="section" id="post-product-wp">
                    <div class="section-head">
                        <h3 class="section-title font-weight-bold">Mô tả sản phẩm</h3>
                    </div>
                    <div class="section-detail">
                        {!! $this_product->content !!}
                    </div>
                    <div class="bg-blur"></div>
                    <div id="show-hidden-content" class="btn btn-outline-dark">Xem thêm</div>
                </div>
                <div class="section" id="same-category-wp">
                    <div class="section-head">
                        <h3 class="section-title font-weight-bold">Cùng danh mục</h3>
                    </div>
                    <div class="section-detail">
                        @if ($relative_products->count() > 0)
                            <ul class="list-item">
                                @foreach ($relative_products as $product)
                                    <li>
                                        <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                            title="" class="thumb">
                                            <img style="height: 190px"
                                                src="{{ asset("uploads/product/$product->thumbnail") }}">
                                        </a>
                                        <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                            title=""
                                            class="product-name">{{ Str::of($product->name)->limit(20) }}</a>
                                        <div class="price">
                                            <span
                                                class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            <span
                                                class="old">{{ number_format($product->old_price, 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ url("gio-hang/them/$product->slug") }}" title=""
                                                class="add-cart fl-left">Thêm giỏ hàng</a>
                                            <a href="{{ url("gio-hang/them/$product->slug?buy_now=true") }}"
                                                title="" class="buy-now fl-right">Mua ngay</a>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        @else
                            <p class="text-danger">Không có sản phẩm nào cùng danh mục</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="sidebar fl-left">
                @include('layouts.client_category_product')
                @include('layouts.client_selling')
                @include('layouts.client_banner')
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
