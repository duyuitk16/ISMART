@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật bài viết
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url("admin/page/edit/$page->id") }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tiêu đề bài viết</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ $page->name }}">
                    </div>
                    @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="slug">Đường link</label>
                        <input class="form-control" type="text" name="slug" id="slug"
                            value="{{ $page->slug }}">
                    </div>
                    @error('slug')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="content">Nội dung bài viết</label>
                        <textarea name="content" class="form-control" id="contentPage" cols="30" rows="5">{{ $page->content }}</textarea>
                    </div>
                    @error('content')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="checkActive" id="checkNotActive"
                                value="off" {{ $page->status == 'off' ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkNotActive">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="checkActive" id="checkActive"
                                value="on" {{ $page->status == 'on' ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkActive">
                                Công khai
                            </label>
                        </div>
                    </div>
                    @error('checkActive')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/jyxjdgxqf3ui2w1sy1mjtb1dhzmmkev5et2dkngz99yn6ksj/tinymce/5/tinymce.min.js'
        referrerpolicy="origin"></script>
    <script type="text/javascript">
        var editor_config = {
            path_absolute: "http://localhost/unitop.vn/back-end/laravel/Admin_Unimart/",
            selector: 'textarea#contentPage',
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
