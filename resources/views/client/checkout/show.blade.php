@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="checkout-page">
        <form method="POST" action="{{ url('thanh-toan/xu-li') }}" name="form-checkout">
            @csrf
            <div class="section" id="breadcrumb-wp">
                <div class="wp-inner">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <a href="{{ url('trang-chu') }}" title="">Trang chủ</a>
                            </li>
                            <li>
                                <a href="{{ url('thanh-toan') }}" title="">Thanh toán</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="wrapper" class="wp-inner clearfix">
                @if (session('info'))
                    <script>
                        Swal.fire({
                            // position: 'top-end',
                            icon: 'info',
                            title: 'Thông báo',
                            html: "{!! session('info') !!}",
                            showConfirmButton: true,
                        })
                    </script>
                @endif
                @if (session('warning'))
                    <script>
                        Swal.fire({
                            // position: 'top-end',
                            icon: 'info',
                            title: 'Thông báo',
                            html: "{!! session('warning') !!}",
                            showConfirmButton: true,
                        })
                    </script>
                @endif
                @if (session('success'))
                    <script>
                        Swal.fire({
                            // position: 'top-end',
                            icon: 'success',
                            title: 'Thông báo',
                            html: "{!! session('success') !!}",
                            showConfirmButton: true,
                        })
                    </script>
                @endif
                <div class="section" id="customer-info-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin khách hàng</h1>
                    </div>
                    <div class="section-detail">
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="fullname">Họ tên</label>
                                <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}">
                                @error('fullname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-col fl-right">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}">
                                @error('email')
                                    <p class=" text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}">
                                @error('address')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-col fl-right">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <label for="note">Ghi chú</label>
                                <textarea name="note" id="note">{{ old('note') }}</textarea>
                                @error('note')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
                <div class="section" id="order-review-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin đơn hàng</h1>
                    </div>
                    @if (Cart::count() > 0)
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $product)
                                    <tr class="cart-item">
                                        <td class="product-name">{{ $product->name }}<strong class="product-quantity">x
                                                {{ $product->qty }}</strong>
                                        </td>
                                        <td class="product-total">{{ number_format($product->total, 0, ',', '.') }}đ
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{ Cart::subtotal() }}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p class="text-danger">Không có sản phẩm nào. Click <a href="{{ url('san-pham') }}">vào
                                đây</a>
                            để tạo đơn hàng mới
                        </p>
                    @endif
                    <div class="section-detail">
                        <div id="payment-checkout-wp">
                            <ul id="payment_methods">
                                <li>
                                    <input type="radio" id="direct-payment" name="checkout_method" value="onl"
                                        {{ old('checkout_method') == 'onl' ? 'checked' : '' }}>
                                    <label for="direct-payment">Thanh toán online</label>
                                </li>
                                <li>
                                    <input type="radio" id="payment-home" name="checkout_method" value="off"
                                        {{ old('checkout_method') == 'off' ? 'checked' : '' }}>
                                    <label for="payment-home">Thanh toán tại nhà</label>
                                </li>
                            </ul>
                            @error('checkout_method')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="place-order-wp clearfix">
                            <input type="submit" id="order-now" value="Đặt hàng" name="btn-order">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $("#order-now").on("click", function(e) {
            e.preventDefault();
            var $invoiceForm = $(this).parents('form');
            if (!$invoiceForm[0].checkValidity()) {
                $invoiceForm[0].reportValidity()
            } else {
                Swal.fire({
                    title: "Bạn quyết định đặt hàng",
                    text: "Bạn chắc chắn với những thông tin đã điền?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3f5da6",
                    confirmButtonText: "Đặt ngay",
                    cancelButtonText: 'Thoát',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $invoiceForm.submit();
                    }
                });
            }
        });
    </script>
    @include('sweetalert::alert')

@endsection
