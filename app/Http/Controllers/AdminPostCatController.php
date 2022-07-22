<?php

namespace App\Http\Controllers;

use App\PostCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminPostCatController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->status;
        if ($status == 'trash') {
            $post_cats = PostCat::onlyTrashed()->get();
            // $post_cats = $this->data_tree($post_cats);
        } else {
            // $post_cats = PostCat::where('status', 'on')->get();
            $post_cats = PostCat::all();
            // return $post_cats[0];
            $post_cats = $this->data_tree($post_cats);
        }
        $count_active = PostCat::count();
        $count_trash = PostCat::onlyTrashed()->count();
        $count = [$count_active, $count_trash];
        // return $post_cats[1];
        return view('admin.post_cat.show', compact('post_cats', 'count', 'status'));
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

    function add(Request $request)
    {
        $request->validate(
            [
                'name' => 'required| max:200',
                'belongsTo' => 'required',
                'status' => 'required',
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên danh mục',
                'belongsTo' => 'Thuộc danh mục cha',
                'status' => 'Trạng thái danh mục',
            ]
        );
        // return $request->all(); 
        PostCat::create([
            'name' => $request->name,
            'parent_id' => $request->belongsTo,
            'status' => $request->status,
            'slug' => Str::slug($request->name),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/post/cat/list')->with('status', 'Đã thêm danh mục thành công');
    }
    function delete($id)
    {
        $post_cats = PostCat::all();
        foreach ($post_cats as $post_cat) {
            if ($post_cat['parent_id'] == $id)
                return redirect('admin/post/cat/list')->with('warning', 'Không thể xóa vì còn danh mục con');
        }
        PostCat::find($id)->delete();
        return redirect('admin/post/cat/list')->with('status', 'Đã xóa thành công');
    }
    function restore($id)
    {
        $this_post_cat = PostCat::onlyTrashed()->find($id);
        $trash_post_cats = PostCat::onlyTrashed()->get();
        foreach ($trash_post_cats as $post_cat) {
            if ($this_post_cat->parent_id == $post_cat->id)
                return redirect('admin/post/cat/list')->with('warning', 'Không thể khôi phục, cần khôi phục danh mục cha chứa nó');
        }
        $this_post_cat->restore();
        return redirect('admin/post/cat/list')->with('status', 'Đã khôi phục thành công');
    }
    function forceDelete($id)
    {
        $trash_post_cats = PostCat::onlyTrashed()->get();
        foreach ($trash_post_cats as $post_cat) {
            if ($post_cat['parent_id'] == $id)
                return redirect('admin/post/cat/list')->with('warning', 'Không thể xóa vĩnh viễn vì còn danh mục con');
        }
        PostCat::onlyTrashed()->find($id)->forceDelete();
        return redirect('admin/post/cat/list')->with('status', 'Đã xóa danh mục vĩnh viễn');
    }
    function update($id)
    {
        $post_cats = PostCat::where('id', '!=', $id)->get();
        $post_cats = $this->data_tree($post_cats);
        $this_post_cat = PostCat::find($id);
        return view('admin.post_cat.update', compact('this_post_cat', 'post_cats'));
    }
    function edit(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required| max:200',
                'belongsTo' => 'required',
                'status' => 'required',
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên danh mục',
                'belongsTo' => 'Thuộc danh mục cha',
                'status' => 'Trạng thái danh mục',
            ]
        );
        // return $request->all(); 
        PostCat::find($id)->update([
            'name' => $request->name,
            'parent_id' => $request->belongsTo,
            'status' => $request->status,
            'slug' => Str::slug($request->name),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/post/cat/list')->with('status', 'Đã cập nhật danh mục thành công');
    }
}
