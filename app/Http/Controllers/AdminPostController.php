<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostCat;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class AdminPostController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        $keyword = '';
        $listAction = [
            'delete' => 'Xóa Tạm Thời',
            'not_active' => 'Chuyển sang chờ duyệt'
        ];
        $status = $request->status;
        if ($status == 'trash') {
            $posts = Post::onlyTrashed()->paginate(10);
            $listAction = ['restore' => 'Khôi Phục', 'forceDelete' => 'Xóa Vĩnh Viễn'];
        } else if ($status == 'not_active') {
            $posts = Post::where('status', 'off')->paginate(10);
            $listAction = [
                'active' => 'Duyệt',
                'delete' => 'Xóa Tạm Thời',
            ];
        } else {
            if ($request->keyword) {
                $keyword = $request->keyword;
            }
            $posts = Post::where([['title', 'like', "%{$keyword}%"], ['status', 'on ']])->paginate(10);
        }
        $count_active = Post::where('status', 'on')->count();
        $count_not_active = Post::where('status', 'off')->count();
        $count_trash = Post::onlyTrashed()->count();
        $count = [$count_active, $count_not_active, $count_trash];
        // dd($request);
        return view('admin.post.show', compact('posts', 'count', 'listAction', 'status'));
    }
    function add()
    {
        $post_cats = PostCat::all();
        $post_cats = $this->data_tree($post_cats);
        return view('admin.post.add', compact('post_cats'));
    }
    function data_tree($data, $parent_id = 0, $level = 0)
    {
        $result = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == $parent_id) {
                $item['level'] = $level;
                $result[] = $item;
                $child = $this->data_tree($data, $item['id'], $level + 1);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }
    function create(Request $request)
    {
        $posts = Post::all();
        $request->validate(
            [
                'title' => 'required| max:200',
                'content' => 'required',
                'description' => 'required| max:300',
                'status' => 'required',
                'belongsTo' => 'required',
                'thumbnail' => 'mimes:jpeg,jpg,png,gif|required|unique:posts|max:10000'
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
                'file.max' => ':Attribute đã vượt kích thước tối đa',
                'mimes' => ':Attribute phải là ảnh',
                'unique' => ':Attribute đã tồn tại trước đó',
            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung',
                'description' => 'Mô tả',
                'status' => 'Trạng thái',
                'belongsTo' => 'Danh mục cha',
                'thumbnail' => 'File upload',
            ]
        );
        $file = $request->thumbnail;
        foreach ($posts as $post) {
            if ($post->thumbnail == $file->getClientOriginalName())
                return back()->withInput()->with(['error_thumbnail' => 'File upload đã tồn tại trước đó']);
        }
        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'description' => $request->description,
            'status' => $request->status,
            'post_cat_id' => $request->belongsTo,
            'slug' => Str::slug($request->title . '.' . time()),
            'user_id' => Auth::id(),
            'thumbnail' =>  $file->getClientOriginalName(),
        ]);
        $file->move('public/uploads/post', $file->getClientOriginalName());
        return redirect('admin/post/list')->with('status', 'Đã thêm bài viết thành công');
    }
    function update($id)
    {
        $post_cats = PostCat::all();
        $post_cats = $this->data_tree($post_cats);
        $this_post = Post::find($id);
        return view('admin.post.update', compact('this_post', 'post_cats'));
    }
    function edit(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required| max:200',
                'content' => 'required',
                'description' => 'required| max:300',
                'status' => 'required',
                'belongsTo' => 'required',
                'thumbnail' => 'mimes:jpeg,jpg,png,gif|max:10000'
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
                'file.max' => ':Attribute đã vượt kích thước tối đa',
                'mimes' => ':Attribute phải là ảnh',
            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung',
                'description' => 'Mô tả',
                'status' => 'Trạng thái',
                'belongsTo' => 'Danh mục cha',
                'thumbnail' => 'File upload',
            ]
        );
        $file = $request->thumbnail;
        $this_post = Post::find($id);
        if ($file) {
            unlink("public/uploads/post/$this_post->thumbnail");
            $this_post->update([
                'thumbnail' => $file->getClientOriginalName(),
            ]);
            $file->move('public/uploads/post', $file->getClientOriginalName());
        }
        $this_post->update([
            'title' => $request->title,
            'content' => $request->content,
            'description' => $request->description,
            'status' => $request->status,
            'post_cat_id' => $request->belongsTo,
            'slug' => Str::slug($request->title . '.' . time()),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/post/list')->with('status', 'Đã cập nhật bài viết thành công');
    }
    function delete($id)
    {
        Post::find($id)->delete();
        return redirect('admin/post/list')->with('status', 'Đã xóa thành công');
    }
    function forceDelete($id)
    {
        $this_post = Post::onlyTrashed()->find($id);
        unlink("public/uploads/post/$this_post->thumbnail");
        $this_post->forceDelete();
        return redirect('admin/post/list')->with('status', 'Đã xóa vĩnh viễn');
    }
    function action(Request $request)
    {
        $listCheck = $request->listCheck;
        if ($listCheck) {
            $action = $request->action;
            if ($action) {
                if ($action == 'delete') {
                    Post::whereIn('id', $listCheck)->delete();
                    return redirect('admin/post/list')->with('status', 'Đã xóa tạm thời');
                }
                if ($action == 'restore') {
                    Post::onlyTrashed()->whereIn('id', $listCheck)->restore();
                    return redirect('admin/post/list')->with('status', 'Đã khôi phục thành công');
                }
                if ($action == 'forceDelete') {
                    Post::onlyTrashed()->whereIn('id', $listCheck)->forceDelete();
                    return redirect('admin/post/list')->with('status', 'Đã xóa vĩnh viễn');
                }
                if ($action == 'not_active') {
                    Post::whereIn('id', $listCheck)->update([
                        'status' => 'off',
                    ]);
                    return redirect('admin/post/list')->with('status', 'Đã chuyển đến chờ duyệt');
                }
                if ($action == 'active') {
                    Post::whereIn('id', $listCheck)->update([
                        'status' => 'on',
                    ]);
                    return redirect('admin/post/list')->with('status', 'Đã duyệt thành công');
                }
            }
            return redirect('admin/post/list')->with('warning', 'Bạn cần chọn tác vụ');
        }
        return redirect('admin/post/list')->with('warning', 'Bạn cần chọn trang để thực hiện');
    }
}
