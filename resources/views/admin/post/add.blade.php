@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm bài viết
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ url('admin/post/create') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tiêu đề bài viết</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ old('title') }}">
                    </div>
                    @error('title')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="description">Mô tả bài viết</label>
                        <input class="form-control" type="text" name="description" id="description"
                            value="{{ old('description') }}">
                    </div>
                    @error('description')
                        <p class=" text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="contentPost">Nội dung bài viết</label>
                        <textarea name="content" class="form-control" id="contentPost" cols="30" rows="5">{{ old('content') }}</textarea>
                    </div>
                    @error('content')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" id="belongsTo" name="belongsTo">
                            <option value="">Chọn danh mục</option>
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
                                    {{ old('belongsTo') == $post_cat->id ? 'selected' : '' }}>
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
                    <div class="form-group">
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control-file"
                            value="{{ old('thumbnail') }}">
                    </div>
                    @error('thumbnail')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    @if (session('error_thumbnail'))
                        <p class="text-danger">{{ session('error_thumbnail') }}</p>
                    @endif
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('import')
    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/jyxjdgxqf3ui2w1sy1mjtb1dhzmmkev5et2dkngz99yn6ksj/tinymce/5/tinymce.min.js'
        referrerpolicy="origin"></script>
    <script type="text/javascript">
        var editor_config = {
            path_absolute: "http://localhost/unitop.vn/back-end/laravel/Admin_Unimart/",
            selector: 'textarea#contentPost',
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            font_formats: "Open Sans=Open Sans,sans-serif;",
            toolbar: "insertfile undo redo | styleselect | fontselect|bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            content_style: "@import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap'); body { font-family: 'Open Sans', sans-serif; }",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };

        tinymce.init(editor_config);
    </script>
@endsection
