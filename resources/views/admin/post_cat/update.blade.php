@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (session('status'))
                        <p class="alert alert-success m-0">{{ session('status') }}</p>
                    @endif
                    @if (session('warning'))
                        <p class="alert alert-warning m-0">{{ session('warning') }}</p>
                    @endif
                    <div class="card-header font-weight-bold">
                        Cập nhật danh mục
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url("admin/post/cat/edit/$this_post_cat->id") }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $this_post_cat->name }}">
                            </div>
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="form-group">
                                <label for="belongsTo">Danh mục cha</label>
                                <select class="form-control" id="belongsTo" name="belongsTo">
                                    <option value="">Chọn danh mục</option>
                                    <option value="0" {{ $this_post_cat->parent_id == '0' ? 'selected' : '' }}>Không thuộc
                                        danh
                                        mục
                                        cha nào</option>
                                    @foreach ($post_cats as $post_cat)
                                        @php
                                            if ($post_cat->level > 0) {
                                                $tmp = '';
                                                for ($i = 1; $i <= $post_cat->level; $i++) {
                                                    $tmp .= '--';
                                                }
                                                $post_cat->name = Str::of($tmp)->append($post_cat->name);
                                            }
                                        @endphp
                                        <option value="{{ $post_cat->id }}"
                                            {{ $this_post_cat->parent_id == $post_cat->id ? 'selected' : '' }}>
                                            {{ $post_cat->name }}
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
                                        {{ $this_post_cat->status == 'on' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">
                                        Công khai
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="not_active" value="off"
                                        {{ $this_post_cat->status == 'off' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="not_active">
                                        Chờ duyệt
                                    </label>
                                </div>
                            </div>
                            @error('status')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
