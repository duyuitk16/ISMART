<div class="section" id="selling-wp">
    <div class="section-head">
        <h3 class="section-title">Sản phẩm bán chạy</h3>
    </div>
    <div class="section-detail">
        <ul class="list-item">
            @foreach ($selling_products as $product)
                <li class="clearfix">
                    <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}" title=""
                        class="thumb fl-left">
                        <img src="{{ asset("uploads/product/$product->thumbnail") }}" alt="">
                    </a>
                    <div class="info fl-right">
                        <a href="{{ url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") }}" title=""
                            class="product-name">{{ Str::of($product->name)->limit(30) }}</a>
                        <div class="price">
                            <span class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                            <span class="old">{{ number_format($product->old_price, 0, ',', '.') }}đ</span>
                        </div>
                        <a href="{{ url("gio-hang/them/$product->slug?buy_now=true") }}" title=""
                            class="buy-now">Mua ngay</a>
                    </div>
                </li>
            @endforeach

        </ul>
    </div>
</div>
