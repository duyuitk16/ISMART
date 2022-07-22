<?php

namespace App\Http\Controllers;

use App\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminProductCatController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->status;
        if ($status == 'trash') {
            $product_cats = ProductCat::onlyTrashed()->get();
            // $product_cats = $this->data_tree($product_cats);
        } else {
            // $product_cats = ProductCat::where('status', 'on')->get();
            $product_cats = ProductCat::all();
            // return $product_cats[0];
            $product_cats = $this->data_tree($product_cats);
        }
        $count_active = ProductCat::count();
        $count_trash = ProductCat::onlyTrashed()->count();
        $count = [$count_active, $count_trash];
        // return $product_cats[1];
        return view('admin.product_cat.show', compact('product_cats', 'count', 'status'));
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
        ProductCat::create([
            'name' => $request->name,
            'parent_id' => $request->belongsTo,
            'status' => $request->status,
            'slug' => Str::slug($request->name),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/product/cat/list')->with('status', 'Đã thêm danh mục thành công');
    }
    function delete($id)
    {
        $product_cats = ProductCat::all();
        foreach ($product_cats as $post_cat) {
            if ($post_cat['parent_id'] == $id)
                return redirect('admin/product/cat/list')->with('warning', 'Không thể xóa vì còn danh mục con');
        }
        ProductCat::find($id)->delete();
        return redirect('admin/product/cat/list')->with('status', 'Đã xóa thành công');
    }
    function restore($id)
    {
        $this_post_cat = ProductCat::onlyTrashed()->find($id);
        $trash_product_cats = ProductCat::onlyTrashed()->get();
        foreach ($trash_product_cats as $post_cat) {
            if ($this_post_cat->parent_id == $post_cat->id)
                return redirect('admin/product/cat/list')->with('warning', 'Không thể khôi phục, cần khôi phục danh mục cha chứa nó');
        }
        $this_post_cat->restore();
        return redirect('admin/product/cat/list')->with('status', 'Đã khôi phục thành công');
    }
    function forceDelete($id)
    {
        $trash_product_cats = ProductCat::onlyTrashed()->get();
        foreach ($trash_product_cats as $post_cat) {
            if ($post_cat['parent_id'] == $id)
                return redirect('admin/product/cat/list')->with('warning', 'Không thể xóa vĩnh viễn vì còn danh mục con');
        }
        ProductCat::onlyTrashed()->find($id)->forceDelete();
        return redirect('admin/product/cat/list')->with('status', 'Đã xóa danh mục vĩnh viễn');
    }
    function update($id)
    {
        $product_cats = ProductCat::where('id', '!=', $id)->get();
        $product_cats = $this->data_tree($product_cats);
        $this_product_cat = ProductCat::find($id);
        return view('admin.product_cat.update', compact('this_product_cat', 'product_cats'));
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
        ProductCat::find($id)->update([
            'name' => $request->name,
            'parent_id' => $request->belongsTo,
            'status' => $request->status,
            'slug' => Str::slug($request->name),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/product/cat/list')->with('status', 'Đã cập nhật danh mục thành công');
    }
}
