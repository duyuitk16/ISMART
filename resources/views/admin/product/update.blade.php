@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật sản phẩm
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ url("admin/product/edit/$this_product->id") }}"
                    method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $this_product->name }}">
                            </div>
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="form-group">
                                <label for="old_price">Giá cũ</label>
                                <input class="form-control" type="number" min="0" name="old_price" id="old_price"
                                    value="{{ $this_product->old_price }}">
                            </div>
                            @error('old_price')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="description">Mô tả sản phẩm</label>
                                <textarea name="description" class="form-control" id="description" cols="30" rows="5">{{ $this_product->description }}</textarea>
                            </div>
                            @error('description')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Giá</label>
                                <input class="form-control" type="number" min="0" name="price" id="price"
                                    value="{{ $this_product->price }}">
                            </div>
                            @error('price')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="code">Mã sản phẩm</label>
                                <input class="form-control" type="text" name="code" id="code"
                                    value="{{ $this_product->code }}" disabled>
                            </div>
                            @error('code')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contentProduct">Chi tiết sản phẩm</label>
                        <textarea name="content" class="form-control" id="contentProduct" cols="30" rows="5">{{ $this_product->content }}</textarea>
                    </div>
                    @error('content')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" id="belongsTo" name="belongsTo">
                            <option value="">Chọn danh mục</option>
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
                                    {{ $this_product->product_cat_id == $product_cat->id ? 'selected' : '' }}>
                                    {{ $product_cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('belongsTo')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group">
                        <label for="amount">Số lượng</label>
                        <input class="form-control" type="number" min="0" name="amount" id="amount"
                            value="{{ $this_product->amount }}">
                    </div>
                    @error('status')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror

                    <img src="{{ url("public/uploads/product/$this_product->thumbnail") }}" alt=""
                        class="img-fluid mb-3">
                    <div class="form-group">
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control-file">
                    </div>
                    @error('thumbnail')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    @if (session('error_thumbnail'))
                        <p class="text-danger">{{ session('error_thumbnail') }}</p>
                    @endif
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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
            selector: 'textarea#contentProduct',
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
    <script type="text/javascript">
        var editor_config = {
            path_absolute: "http://localhost/unitop.vn/back-end/laravel/Admin_Unimart/",
            selector: 'textarea#description',
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
