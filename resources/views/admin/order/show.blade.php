@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <p class="alert alert-success m-0">{{ session('status') }}</p>
            @endif
            @if (session('warning'))
                <p class="alert alert-warning m-0">{{ session('warning') }}</p>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách đơn hàng</h5>
                <div class="form-search form-inline">
                    <form action="" action="GET">
                        <input type="text" class="form-control form-search" name="keyword" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/order/action') }}" method="post">
                    @csrf
                    <div class="analytic">
                        <a href="{{ url('admin/order/list?status=complete') }}" class="text-primary">Hoàn thành<span
                                class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ url('admin/order/list?status=solve') }}" class="text-primary">Đang xử lí<span
                                class="text-muted">({{ $count[1] }})</span></a>
                        <a href="{{ url('admin/order/list?status=confirm') }}" class="text-primary">Chờ xác nhận<span
                                class="text-muted">({{ $count[2] }})</span></a>
                        <a href="{{ url('admin/order/list?status=delete') }}" class="text-primary">Đơn bị hủy<span
                                class="text-muted">({{ $count[3] }})</span></a>
                        <a href="{{ url('admin/order/list?status=trash') }}" class="text-primary">Vô hiệu hóa<span
                                class="text-muted">({{ $count[4] }})</span></a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="action">
                            <option value="">Chọn</option>
                            @foreach ($listAction as $v => $action)
                                <option value="{{ $v }}">{{ $action }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
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
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $order->id }}">
                                        </td>
                                        <td>{{ $t }}</td>
                                        <td>{{ $order->code }}</td>
                                        <td>
                                            {{ $order->fullname }} <br>
                                            {{ $order->phone }}
                                        </td>
                                        <td><a href="" class="show-modal">{!! $order->orders !!}</a>
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
                                        @if ($order->status == 'completing')
                                            <td><span class="badge badge-success">Hoàn thành</span></td>
                                        @elseif ($order->status == 'confirming')
                                            <td><span class="badge badge-danger">Chờ xác nhận</span></td>
                                        @elseif ($order->status == 'solving')
                                            <td><span class="badge badge-warning">Đang xử lí</span></td>
                                        @else
                                            <td><span class="badge badge-dark">Đã hủy</span></td>
                                        @endif
                                        <td>{{ $order->created_at->format('H:i:s d-m-Y') }} </td>
                                        <td>
                                            @if ($order == 'trash')
                                                <a href="{{ url("admin/order/restore/$order->id") }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Restore"><i
                                                        class="fa-solid fa-rotate-left"></i></a>
                                            @endif
                                            @if ($order->status == 'deleting')
                                                <a href="{{ url("admin/order/solve/$order->id") }}"
                                                    class="mb-1 btn btn-warning btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Solving"><i
                                                        class="fa-regular fa-circle-check"></i></a>
                                            @else
                                                <a href="{{ url("admin/order/destroy/$order->id") }}"
                                                    class="mb-1 btn btn-dark btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Destroy"
                                                    onclick="return confirm('Bạn muốn hủy đơn')"><i
                                                        class="fa-solid fa-ban"></i></a>
                                            @endif
                                            @php
                                                $delete_type = $status == 'trash' ? 'forceDelete' : 'delete';
                                                $delete_confirm = $status == 'trash' ? 'Bạn muốn xóa vĩnh viễn' : 'Bạn muốn vô hiệu hóa';
                                            @endphp
                                            <a href="{{ url("admin/order/$delete_type/$order->id") }}"
                                                onclick="return confirm('{{ $delete_confirm }}')"
                                                class="mb-1 btn btn-danger btn-sm rounded-0" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    style="width: 14px" class="fa fa-trash"></i></a>
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
                </form>
                {{ $orders->appends(['status' => $status])->links() }}
                <a class="btn btn-outline-light" data-toggle="modal" data-target="#demo-modal" id="tick">Modal</a>
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
