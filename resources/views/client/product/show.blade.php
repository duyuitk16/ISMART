@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix category-product-page">
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
                        @if (!request()->keyword)
                            <li>
                                <a href="{{ url("san-pham/{$this_cat->slug}") }}" title="">{{ $this_cat->name }}</a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="list-product-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title fl-left">
                            {{ !request()->keyword ? $this_cat->name : 'Kết quả tìm kiếm...' }}
                        </h3>
                        <div class="filter-wp fl-right">
                            <p class="desc">Hiển thị {{ $products->count() }} trên {{ $count_products }} sản
                                phẩm</p>
                            @if (!request()->keyword)
                                <div class="form-filter">
                                    <form method="get" action="">
                                        <select name="sort">
                                            <option value="">Sắp xếp</option>
                                            <option value="1">Từ A-Z</option>
                                            <option value="2">Từ Z-A</option>
                                            <option value="3">Giá cao xuống thấp</option>
                                            <option value="4">Giá thấp lên cao</option>
                                        </select>
                                        <button type="submit">Lọc</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="section-detail">
                        @if ($products->count() > 0)
                            <ul class="list-item clearfix">
                                @foreach ($products as $product)
                                    <li>
                                        <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                            title="" class="thumb">
                                            <img style="height: 190px"
                                                src="{{ asset("uploads/product/$product->thumbnail") }}">
                                        </a>
                                        <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}"
                                            title="" class="product-name">{{ Str::of($product->name)->limit(30) }}</a>
                                        <div class="price">
                                            <span
                                                class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            <span
                                                class="old">{{ number_format($product->old_price, 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ url("gio-hang/them/$product->slug") }}" title="Thêm giỏ hàng"
                                                class="add-cart fl-left {{ $product->status == 'off' ? 'dis' : '' }}">Thêm
                                                giỏ
                                                hàng</a>
                                            <a href="{{ url("gio-hang/them/$product->slug?buy_now=true") }}"
                                                title="Mua ngay"
                                                class="buy-now fl-right {{ $product->status == 'off' ? 'dis' : '' }}">Mua
                                                ngay</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-danger">Không có dữ liệu. Click <a href="{{ url('trang-chu') }}">vào
                                    đây</a>
                                để trở lại trang chủ
                            </p>
                        @endif
                    </div>
                </div>
                @if (request()->keyword)
                    <div class="fl-right"> {{ $products->appends(['keyword' => request()->keyword])->links() }}
                    </div>
                @elseif(request()->sort)
                    <div class="fl-right"> {{ $products->appends(['sort' => request()->sort])->links() }}
                    </div>
                @else
                    <div class="fl-right"> {{ $products->links() }}</div>
                @endif

            </div>
            <div class="sidebar fl-left">
                @include('layouts.client_category_product')
                @include('layouts.client_selling')
                {{-- @include('layouts.client_banner') --}}
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
