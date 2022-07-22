@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    @if (session('status'))
                        <p class="alert alert-success m-0">{{ session('status') }}</p>
                    @endif
                    @if (session('warning'))
                        <p class="alert alert-warning m-0">{{ session('warning') }}</p>
                    @endif
                    <div class="card-header font-weight-bold">
                        Thêm quyền
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/role/add') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên quyền</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Nhóm quyền
                    </div>
                    <div class="card-body">
                        <div class="analytic">
                            <a href="{{ url('admin/role/list?status=active') }}" class="text-primary">Hoạt
                                động<span class="text-muted">({{ $count[0] }})</span></a>
                            <a href="{{ url('admin/role/list?status=trash') }}" class="text-primary">Thùng
                                rác<span class="text-muted">({{ $count[1] }})</span></a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên quyền</th>
                                    <th scope="col">Người tạo</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($roles) > 0)
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($roles as $role)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $t }}</th>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->user->name }}</td>
                                            <td>{{ $role->created_at->format('H:i:s d-m-Y') }}</td>
                                            <td>
                                                @php
                                                    if ($status == 'trash') {
                                                        $class_icon = 'fa-solid fa-trash-can-arrow-up';
                                                    } else {
                                                        $class_icon = 'fa fa-edit';
                                                    }
                                                @endphp
                                                @php
                                                    $url_type = $status == 'trash' ? 'restore' : 'update';
                                                @endphp
                                                <a href="{{ url("admin/role/$url_type/$role->id") }}"
                                                    class="btn btn-success btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $status == 'trash' ? 'Restore' : 'Edit' }}">
                                                    <i class="{{ $class_icon }}"></i>
                                                </a>
                                                @php
                                                    $delete_type = $status == 'trash' ? 'forceDelete' : 'delete';
                                                    $delete_confirm = $status == 'trash' ? 'Bạn muốn xóa vĩnh viễn' : 'Bạn muốn xóa tạm thời';
                                                @endphp
                                                <a href="{{ url("admin/role/$delete_type/$role->id") }}"
                                                    class="btn btn-danger btn-sm rounded-0"
                                                    onclick="return confirm('{{ $delete_confirm }}')"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
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
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
