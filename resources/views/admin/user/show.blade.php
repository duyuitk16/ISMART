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

                <h5 class="m-0 ">Danh sách thành viên</h5>
                <div class="form-search form-inline">
                    <form action="">
                        <input type="text" class="form-control form-search" placeholder="Tìm kiếm" name="keyword"
                            value="{{ request()->keyword }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/user/action') }}" method="POST">
                    @csrf
                    <div class="analytic">
                        <a href="{{ url('admin/user/list?status=active') }}" class="text-primary">Kích
                            hoạt<span class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ url('admin/user/list?status=trash') }}" class="text-primary">Vô hiệu
                            hóa<span class="text-muted">({{ $count[1] }})</span></a>
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
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->total() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($users as $user)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $user->id }}">
                                        </td>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name }}</td>
                                        <td>{{ $user->created_at->format('H:i:s d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ url("admin/user/update/$user->id") }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            @if (Auth::id() != $user->id)
                                                @php
                                                    $delete_type = $status == 'trash' ? 'forceDelete' : 'delete';
                                                    $delete_confirm = $status == 'trash' ? 'Xóa viễn vĩnh có thể làm thay đổi các thông tin liên quan đến thành viên này' : 'Bạn muốn xóa tạm thời';
                                                @endphp
                                                <a href="{{ url("admin/user/$delete_type/$user->id") }}"
                                                    onclick="return confirm('{{ $delete_confirm }}')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $status == 'trash' ? 'forceDelete' : 'Delete' }}"><i
                                                        class="fa fa-trash"></i></a>
                                            @endif
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

                {{-- {{ $users->links('pagination.limit_page') }} --}}
                {{ $users->appends(['status' => $status])->links() }}
            </div>
        </div>
    </div>
@endsection
