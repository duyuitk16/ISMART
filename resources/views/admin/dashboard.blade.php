@extends('layouts.admin')
@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header"><a class="font-weight-bold" style=" color: inherit;"
                            href="
                                                                        {{ url('admin/order/list') }}">ĐƠN
                            HÀNG
                            THÀNH CÔNG</a></div>
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $count[0] }}</h5>
                        <p class="card-text">Đơn hàng giao dịch thành công</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header"><a class="font-weight-bold" style=" color: inherit;"
                            href="
                                                        {{ url('admin/order/list?status=solve') }}">ĐANG
                            XỬ LÝ</a></div>
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $count[1] }}</h5>
                        <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header font-weight-bold">DOANH SỐ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ number_format($total, 0, ',', '.') }}đ</h5>
                        <p class="card-text">Doanh số hệ thống</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header"><a class="font-weight-bold" style=" color: inherit;"
                            href="
                                            {{ url('admin/order/list?status=delete') }}">ĐƠN
                            HÀNG HỦY</a></div>
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $count[2] }}</h5>
                        <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end analytic  -->
        <div class="card">
            @if (session('status'))
                <p class="alert alert-success m-0">{{ session('status') }}</p>
            @endif
            @if (session('warning'))
                <p class="alert alert-warning m-0">{{ session('warning') }}</p>
            @endif
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Giá trị</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($orders->total() > 0)
                            @php
                                $t = 0;
                            @endphp
                            @foreach ($orders as $order)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td>{{ $t }}</td>
                                    <td>{{ $order->code }}</td>
                                    <td>
                                        {{ $order->fullname }} <br>
                                        {{ $order->phone }}
                                    </td>
                                    <td><a href='' class="show-modal">{!! $order->orders !!}</a>
                                        <div class="modal">
                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" style="color: #EAB543">Chi tiết đơn hàng
                                                        </h5>
                                                        <button class="close" data-dismiss='modal'>
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Mã đơn hàng</th>
                                                                    <th>Người đặt</th>
                                                                    <th>Số điện thoại</th>
                                                                    <th>Địa chỉ</th>
                                                                    <th>Danh sách sản phẩm</th>
                                                                    <th>Ngày giờ đặt hàng</th>
                                                                    <th>Ghi chú</th>
                                                                    <th>Tổng đơn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>{{ $order->code }}</td>
                                                                    <td>{{ $order->fullname }}</td>
                                                                    <td>{{ $order->phone }}</td>
                                                                    <td>{{ $order->address }}</td>
                                                                    <td>{!! $order->orders !!}</td>
                                                                    <td>{{ $order->created_at->format('H:i:s d-m-Y') }}
                                                                    </td>
                                                                    <td>{{ $order->note }}</td>
                                                                    <td>{{ number_format($order->bill, 0, ',', '.') }}đ
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($order->bill, 0, ',', '.') }}đ</td>
                                    <td><span
                                            class="badge {{ $order->status == 'completing' ? 'badge-success' : 'badge-warning' }} ">{{ $order->status == 'completing' ? 'Hoàn thành' : 'Đang xử lí' }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('H:i:s d-m-Y') }} </td>
                                    <td>
                                        <a href="{{ url("dashboard/destroy/$order->id") }}"
                                            class="btn btn-dark btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Destroy"
                                            onclick="return confirm('Bạn muốn hủy đơn')"><i class="fa-solid fa-ban"></i></a>
                                        <a href="{{ url("dashboard/delete/$order->id") }}"
                                            onclick="return confirm('Bạn muốn vô hiệu hóa')"
                                            class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="bg-white">Không tồn tại dữ liệu</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".show-modal").click(function(e) {
                e.preventDefault();
                $(this).next().modal();
            });
        });
    </script>
@endsection
