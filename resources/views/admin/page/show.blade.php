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
                <h5 class="m-0 ">Danh sách bài viết</h5>
                <div class="form-search form-inline">
                    <form action="{{ url('admin/page/list') }}" method="GET">
                        <input type="text" name="keyword" class="form-control form-search" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/page/action') }}" method="POST">
                    @csrf
                    <div class="analytic">
                        <a href="{{ url('admin/page/list?status=active') }}" class="text-primary">Công khai<span
                                class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ url('admin/page/list?status=not_active') }}" class="text-primary">Chờ duyệt<span
                                class="text-muted">({{ $count[1] }})</span></a>
                        <a href="{{ url('admin/page/list?status=trash') }}" class="text-primary">Vô hiệu hóa<span
                                class="text-muted">({{ $count[2] }})</span></a>
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
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Tên</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Người tạo</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pages->total() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($pages as $page)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $page->id }}">
                                        </td>
                                        <td scope="row">{{ $t }}</td>
                                        <td><a href="{{ url("admin/page/update/$page->id") }}">{{ $page->name }}</a>
                                        </td>
                                        @php
                                            if ($status == 'trash') {
                                                $status_client = 'Xóa tạm thời';
                                            } elseif ($status == 'not_active') {
                                                $status_client = 'Chờ duyệt';
                                            } else {
                                                $status_client = 'Công khai';
                                            }
                                        @endphp
                                        <td>{{ $status_client }}</td>
                                        @if ($page->user_id)
                                            <td>{{ $page->user->name }}</td>
                                        @else
                                            <td class="text-danger">Người tạo đã bị xóa vĩnh viễn</td>
                                        @endif
                                        <td>{{ $page->created_at->format('H:i:s d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ url("admin/page/update/$page->id") }}"
                                                class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            @php
                                                $delete_type = $status == 'trash' ? 'forceDelete' : 'delete';
                                                $delete_confirm = $status == 'trash' ? 'Bạn muốn xóa vĩnh viễn' : 'Bạn muốn xóa tạm thời';
                                            @endphp
                                            <a href="{{ url("admin/page/$delete_type/$page->id") }}"
                                                onclick="return confirm('{{ $delete_confirm }}')"
                                                class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tồn tại dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $pages->appends(['status' => $status])->links() }}
            </div>
        </div>
    </div>
@endsection
