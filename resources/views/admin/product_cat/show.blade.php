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
                        Thêm danh mục
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/product/cat/add') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="form-group">
                                <label for="belongsTo">Danh mục cha</label>
                                <select class="form-control" id="belongsTo" name="belongsTo">
                                    <option value="">Chọn danh mục</option>
                                    <option value="0" {{ old('belongsTo') == '0' ? 'selected' : '' }}>Không thuộc danh mục
                                        cha nào</option>
                                    @foreach ($product_cats as $product_cat)
                                        @php
                                            if ($product_cat->level > 0) {
                                                $tmp = '';
                                                for ($i = 1; $i <= $product_cat->level; $i++) {
                                                    $tmp .= '--';
                                                }
                                                $product_cat->name = Str::of($tmp)->append($product_cat->name);
                                            }
                                        @endphp
                                        <option value="{{ $product_cat->id }}"
                                            {{ old('belongsTo') == $product_cat->id ? 'selected' : '' }}>
                                            {{ $product_cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('belongsTo')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="form-group">
                                <label for="">Trạng thái</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="active" value="on"
                                        {{ old('status') == 'on' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">
                                        Công khai
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="not_active" value="off"
                                        {{ old('status') == 'off' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="not_active">
                                        Chờ duyệt
                                    </label>
                                </div>
                            </div>
                            @error('status')
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
                        Danh mục
                    </div>
                    <div class="card-body">
                        <div class="analytic">
                            <a href="{{ url('admin/product/cat/list?status=active') }}" class="text-primary">Hoạt
                                động<span class="text-muted">({{ $count[0] }})</span></a>
                            <a href="{{ url('admin/product/cat/list?status=trash') }}" class="text-primary">Thùng
                                rác<span class="text-muted">({{ $count[1] }})</span></a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Người tạo</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($product_cats) > 0)
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($product_cats as $product_cat)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $t }}</th>
                                            @php
                                                if ($product_cat->level > 0) {
                                                    $tmp = '';
                                                    for ($i = 1; $i <= $product_cat->level; $i++) {
                                                        $tmp .= '--';
                                                    }
                                                    $product_cat->name = Str::of($tmp)->append($product_cat->name);
                                                }
                                            @endphp
                                            <td>{{ $product_cat->name }}</td>
                                            <td>{{ $product_cat->status == 'on' ? 'Công khai' : 'Chờ duyệt' }}</td>
                                            @if ($product_cat->user_id)
                                                <td>{{ $product_cat->user->name }}</td>
                                            @else
                                                <td class="text-danger">Người tạo đã bị xóa vĩnh viễn</td>
                                            @endif
                                            <td>{{ $product_cat->created_at->format('H:i:s d-m-Y') }}</td>
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
                                                <a href="{{ url("admin/product/cat/$url_type/$product_cat->id") }}"
                                                    class="btn btn-success btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $status == 'trash' ? 'Restore' : 'Edit' }}">
                                                    <i class="{{ $class_icon }}"></i>
                                                </a>
                                                @php
                                                    $delete_type = $status == 'trash' ? 'forceDelete' : 'delete';
                                                    $delete_confirm = $status == 'trash' ? 'Xóa viễn vĩnh có thể làm thay đổi các thông tin liên quan đến danh mục này' : 'Bạn muốn xóa tạm thời';
                                                @endphp
                                                <a href="{{ url("admin/product/cat/$delete_type/$product_cat->id") }}"
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
