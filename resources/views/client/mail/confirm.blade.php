<div id="wrap-inner" style="font-family:Arial;background-color: #ececec;padding: 15px 0px; font-size: 14px;">
    <div class="content-confirmation" style="padding: 15px; max-width: 960px; background-color: white; margin: 0px auto;">
        <h3 style="margin-top: 0px;">[ ISMART STORE ] Cảm ơn quý khách đã đặt hàng tại ISMART STORE</h3>
        <div id="customer">
            <h3 style="color:#34495e; border-bottom: 1px solid #333">Thông tin khách hàng</h3>
            <p>
                <strong>Khách hàng: </strong>
                {{ $order->fullname }}
            </p>
            <p>
                <strong class="info">Email: </strong>
                {{ $order->email }}
            </p>
            <p>
                <strong class="info">Điện thoại: </strong>
                {{ $order->phone }}

            </p>
            <p>
                <strong class="info">Địa chỉ: </strong>
                {{ $order->address }}
            </p>
        </div>
        <div id="order-detail">
            <h3 style="color:#34495e;border-bottom: 1px solid #333;">Chi tiết đơn hàng </h3>
            <table style="width: 100%; background-color: #eeeeee;" cellspacing="0">
                <thead style="background-color: #34495e; color: white">
                    <tr class="bold">
                        <td style="border: 1px dotted #2d3436;padding: 10px 0px;;text-align:center"><strong>Mã đơn
                                hàng</strong></td>
                        <td style="border: 1px dotted #2d3436;padding: 10px 0px;text-align:center"><strong>Danh sách sản
                                phẩm</strong>
                        </td>
                        <td style="border: 1px dotted #2d3436;padding: 10px 0px;text-align:center"><strong>Phương thức
                                thanh toán</strong>
                        </td>
                        <td style="border: 1px dotted #2d3436;padding: 10px 0px;;text-align:center"><strong>Lưu
                                ý</strong></td>
                        <td style="border: 1px dotted #2d3436;padding: 10px 0px;;text-align:center"><strong>Tổng
                                đơn</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center; padding:5px; border: 1px dotted #2d3436;">{{ $order->code }}
                        </td>
                        <td style="border: 1px dotted #2d3436;padding:5px;">{!! $order->orders !!}</td>
                        <td style="text-align:center;border: 1px dotted #2d3436;padding:5px;">
                            {{ $order->checkout_method == 'onl' ? 'Thanh toán online' : 'Thanh toán tại nhà' }}</td>
                        <td style="border: 1px dotted #2d3436;padding:5px;">{{ $order->note }}</td>
                        <td class="price" style="border: 1px dotted #2d3436;padding:5px;">
                            {{ number_format($order->bill, 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ul style="list-style: none;padding:0px;text-align:center;">
            <li style="display: inline-block"><a
                    style="display: block; padding: 10px; background:#d63031;text-decoration:none;color:#ecf0f1;"
                    href="{{ url("thanh-toan/xac-nhan?confirm=$order->checkConfirm&action=delete") }}">Xóa đơn
                    hàng</a></li>
            <li style="display: inline-block"><a
                    style="display: block; padding: 10px; background:#00b894;text-decoration:none;color:#ecf0f1;"
                    href="{{ url("thanh-toan/xac-nhan?confirm=$order->checkConfirm&action=confirm") }}">Xác nhận đơn
                    hàng</a></li>
        </ul>
        <div id="info">
            <p style="text-align: right;font-style:italic;font-weight:700">Cám ơn Quý khách đã sử dụng Sản phẩm của cửa
                hàng chúng tôi.
                Xin chân
                thành cảm ơn</p>
        </div>
    </div>
</div>
