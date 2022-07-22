@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="cart-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('trang-chu') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Giỏ hàng</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="wrapper" class="wp-inner clearfix">
            <form action="{{ url('gio-hang/cap-nhat') }}" method="POST">
                @csrf
                @if (session('status'))
                    <script>
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: "{!! session('status') !!}"
                        })
                    </script>
                @endif
                @if ($products->count() > 0)
                    <div class="section" id="info-cart-wp">
                        <div class="section-detail table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Mã sản phẩm</td>
                                        <td>Ảnh sản phẩm</td>
                                        <td>Tên sản phẩm</td>
                                        <td>Giá sản phẩm</td>
                                        <td>Số lượng</td>
                                        <td colspan="2">Thành tiền</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->options->code }}</td>
                                            <td>
                                                <a href="{{ url("san-pham/{$product->options->cat_slug}/{$product->options->slug}.html") }}"
                                                    title="" class="thumb">
                                                    <img src="{{ asset("uploads/product/{$product->options->thumbnail}") }}"
                                                        alt="" style="height:100px">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ url("san-pham/{$product->options->cat_slug}/{$product->options->slug}.html") }}"
                                                    title="" class="name-product">{{ $product->name }}</a>
                                            </td>
                                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                            <td>
                                                <input type="number" min="1"
                                                    name="num_order[{{ $product->rowId }}]" value="{{ $product->qty }}"
                                                    class="num-order" data_rowId="{{ $product->rowId }}"
                                                    max="{{ $amount[$product->rowId] }}">
                                            </td>
                                            <td id="sub-total-price-{{ $product->rowId }}">
                                                {{ number_format($product->total, 0, ',', '.') }}đ
                                            <td>
                                                <a href="{{ urL("gio-hang/xoa/$product->rowId") }}" title=""
                                                    class="del-product"><i class="fa-solid fa-trash-can"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <p id="total-price" class="fl-right">Tổng giá:
                                                    <span>{{ Cart::subtotal() }}đ</span>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <div class="fl-right">
                                                    <a href="{{ url('thanh-toan') }}" title=""
                                                        id="checkout-cart">Thanh
                                                        toán</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="section" id="action-cart-wp">
                        <div class="section-detail">
                            <p class="title">Click vào <span>"THANH TOÁN"</span> để hoàn tất mua hàng.
                            </p>
                            <a href="{{ url('san-pham') }}" title="" id="buy-more">Mua tiếp</a><br />
                            <a href="{{ url('gio-hang/xoa-gio-hang') }}" title="" id="delete-cart">Xóa giỏ hàng</a>
                        </div>
                    </div>
                @else
                    <p class="text-danger">Không có sản phẩm nào trong giỏ hàng. Click <a
                            href="{{ url('san-pham') }}">vào
                            đây</a> để mua hàng</p>
                @endif
            </form>

        </div>

    </div>

    <script>
        $('.del-product').on('click', function(e) {
            e.preventDefault();
            var self = $(this);
            Swal.fire({
                title: 'Bạn muốn xóa sản phẩm này?',
                text: "Sản phẩm này sẽ được xóa ra khỏi giỏ hàng ngay lập tức!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Thoát',
                confirmButtonText: 'Xóa'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = self.attr('href');
                }
            })
        })
        $('#delete-cart').on('click', function(e) {
            e.preventDefault();
            var self = $(this);
            Swal.fire({
                title: 'Bạn muốn xóa giỏ hàng?',
                text: "Giỏ hàng của bạn sẽ bị xóa ngay lập tức",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Thoát',
                confirmButtonText: 'Xóa'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = self.attr('href');
                }
            })
        })
    </script>
    @include('sweetalert::alert')

@endsection
