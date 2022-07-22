@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật người dùng
            </div>
            <div class="card-body">
                <form action="{{ url("admin/user/edit/$user->id") }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ $user->name }}">
                    </div>
                    @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{ $user->email }}"
                            disabled>
                    </div>
                    @error('email')
                        <p class="  text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password">
                    </div>
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="password-confirm">Xác nhận mật khẩu</label>
                        <input class="form-control" type="password" name="password-confirm" id="password-confirm">
                    </div>
                    @error('password-confirm')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="role">Nhóm quyền</label>
                        <select class="form-control" id="role" name="role"
                            {{ Auth::user()->role->name == 'Administator' ? '' : 'disabled' }}>
                            <option value="">Chọn quyền</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('role')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
