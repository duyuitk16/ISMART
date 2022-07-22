<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- <link href="{{ asset('client/css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('client/reset.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.carousel.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.theme.css') }} " rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    {{-- <link href="{{ asset('client/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" /> --}}




    <link href="{{ asset('client/style.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/responsive.css') }} " rel="stylesheet" type="text/css" />
    {{-- <script src="{{ asset('client/js/jquery-2.2.4.min.js') }} " type="text/javascript"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('client/js/elevatezoom-master/jquery.elevatezoom.js') }} " type="text/javascript"></script>
    {{-- <script src="{{ asset('client/js/bootstrap/bootstrap.min.js') }} " type="text/javascript"></script> --}}
    <script src="{{ asset('client/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
    {{-- <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('client/js/image-zoom.js') }} " type="text/javascript"></script>
    <script src="{{ asset('client/js/main.js') }} " type="text/javascript"></script>

</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="{{ url('/trang-chu') }}" title="">Trang chủ</a>
                                </li>
                                <li>
                                    <a href="{{ url('/san-pham') }}" title="">Sản phẩm</a>
                                </li>
                                <li>
                                    <a href="{{ url('/bai-viet') }}" title="">Bài viết</a>
                                </li>
                                <li>
                                    <a href="{{ url('trang/gioi-thieu.html') }}" title="">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="{{ url('trang/lien-he.html') }}" title="">Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="{{ url('/trang-chu') }}" title="" id="logo" class="fl-left"><img
                                src="{{ asset('client/images/logo.png') }} " /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="GET" action="{{ url('san-pham') }}">
                                <input type="text" name="keyword" id="s" placeholder="Nhập sản phẩm cần tìm!"
                                    autocomplete="off">
                                <button type="submit" id="sm-s">Tìm Kiếm</button>
                            </form>
                            <div class="search-result">
                                <ul class="suggest-search">
                                    {{-- <li class="product-suggest">
                                        <a href="" title="" class="">
                                            <div class="item-img">
                                                <img src="{{ asset('client/images/img-pro-11.png') }}" alt="">
                                            </div>
                                            <div class="item-info">
                                                <h3 class="product-name">laptop</h3>
                                                <strong class="price-new">12đ</strong>
                                                <strong class="price-old">12đ</strong>
                                            </div>
                                        </a>
                                    </li> --}}
                                </ul>
                            </div>

                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0912.653.009</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                            {{-- <a href="{{ url('gio-hang') }}" title="giỏ hàng" id="cart-respon-wp"
                                class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num">2</span>
                            </a> --}}
                            <div id="cart-wp" class="fl-right">
                                <div id="btn-cart">
                                    <a href="{{ url('gio-hang') }}" class="d-block" style="color:#fff"><i
                                            class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                    <span id="num">
                                        {{ Cart::count() > 0 ? Cart::count() : '' }}
                                    </span>
                                </div>
                                @if (Cart::count() > 0)
                                    <div id="dropdown">
                                        <p class="desc">Có <span class="desc-num">{{ Cart::count() }}</span>
                                            <span>sản
                                                phẩm</span> trong
                                            giỏ hàng
                                        </p>
                                        <ul class="list-cart">
                                            @foreach (Cart::content() as $product)
                                                <li class="clearfix">
                                                    <a href="{{ url("san-pham/{$product->options->cat_slug}/{$product->options->slug}.html") }}"
                                                        title="" class="thumb fl-left">
                                                        <img src="{{ asset("uploads/product/{$product->options->thumbnail}") }}"
                                                            alt="">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="{{ url("san-pham/{$product->options->cat_slug}/{$product->options->slug}.html") }}"
                                                            title=""
                                                            class="product-name">{{ $product->name }}</a>
                                                        <p class="price">
                                                            {{ number_format($product->price, 0, ',', '.') }}đ</p>
                                                        <p class="qty" id="dp-num-{{ $product->rowId }}">
                                                            Số lượng:
                                                            <span>{{ $product->qty }}</span>
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="total-price clearfix">
                                            <p class="title fl-left">Tổng:</p>
                                            <p class="price fl-right" id="dp-total">{{ Cart::subtotal() }}đ
                                            </p>
                                        </div>
                                        <div class="action-cart clearfix">
                                            <a href="{{ url('gio-hang') }}" title="Giỏ hàng"
                                                class="view-cart fl-left">Giỏ
                                                hàng</a>
                                            <a href="{{ url('thanh-toan') }}" title="Thanh toán"
                                                class="checkout fl-right">Thanh
                                                toán</a>
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- END HEADER --}}

            @yield('content')


            {{-- FOOTER --}}
            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">ISMART</h3>
                            <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ
                                ràng, chính sách ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="{{ url('public/client/images/img-foot.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Thông tin cửa hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>985R+FGG, Phường 5, Thành phố Mỹ Tho, Tiền Giang</p>
                                </li>
                                <li>
                                    <p>0912.653.009 - 0944.021.419</p>
                                </li>
                                <li>
                                    <p>nguyenvuanhduy173@gmail</p>
                                </li>
                            </ul>
                        </div>
                        <div class="block menu-ft policy" id="info-shop">
                            <h3 class="title">Chính sách mua hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <a href="" title="">Quy định - chính sách</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách bảo hành - đổi trả</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách hội viện</a>
                                </li>
                                <li>
                                    <a href="" title="">Giao hàng - lắp đặt</a>
                                </li>
                            </ul>
                        </div>
                        <div class="block" id="newfeed">
                            <h3 class="title">Bảng tin</h3>
                            <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                            <div id="form-reg">
                                <form method="POST" action="">
                                    <input type="email" name="email" id="email"
                                        placeholder="Nhập email tại đây">
                                    <button type="submit" id="sm-reg">Đăng ký</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner">
                        <p id="copyright">© Bản quyền thuộc về unitop.vn | Php Master</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- @php
            foreach ($product_cats as $product_cat) {
                echo $product_cat->name . ' ' . $product_cat->level . ' ' . $product_cat->parent_id . '<br>';
            }
        @endphp --}}
        <div id="menu-respon">
            <a href="{{ url('trang-chu') }}" title="" class="logo">ISMART</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <li>
                        <a href="{{ url('trang-chu') }}" title>Trang chủ</a>
                    </li>
                    @php
                        menuRespon($product_cats, 'sub-menu');
                    @endphp
                    <li>
                        <a href="{{ url('bai-viet') }}" title>Bài viết</a>
                    </li>
                    <li>
                        <a href="{{ url('trang/lien-he.html') }}" title>Liên hệ</a>
                    </li>
                    <li>
                        <a href="{{ url('trang/gioi-thieu.html') }}" title>Giới thiệu</a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="btn-top"><img src="{{ url('public/client/images/icon-to-top.png') }}" alt="" /></div>
        <div id="fb-root"></div>

        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <script>
            $(document).ready(function(e) {
                $('#payment-link').on('click', function() {
                    // e.preventDefault();
                    Swal.fire({
                        title: 'Hiện có 2 phương thức thanh toán',
                        icon: 'info',
                        text: 'Thanh toán online và thanh toán tại nhà',
                        confirmButtonText: 'Đã rõ',
                        showClass: {
                            popup: 'animate__animated animate__bounceIn'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__bounceOut'
                        }
                    })
                    return false;
                });
            });
        </script>
</body>

</html>
