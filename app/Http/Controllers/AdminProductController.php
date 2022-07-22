<?php

namespace App\Http\Controllers;

use App\Post;
use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
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
            $products = Product::onlyTrashed()->paginate(10);
            $listAction = ['restore' => 'Khôi Phục', 'forceDelete' => 'Xóa Vĩnh Viễn'];
        } else if ($status == 'not_active') {
            $products = Product::where('status', 'off')->paginate(10);
            $listAction = [
                'active' => 'Duyệt',
                'delete' => 'Xóa Tạm Thời',
            ];
        } else {
            if ($request->keyword) {
                $keyword = $request->keyword;
            }
            $products = Product::where([['name', 'like', "%{$keyword}%"], ['status', 'on ']])->paginate(10);
        }
        $count_active = Product::where('status', 'on')->count();
        $count_not_active = Product::where('status', 'off')->count();
        $count_trash = Product::onlyTrashed()->count();
        $count = [$count_active, $count_not_active, $count_trash];
        // dd($request);
        return view('admin.product.show', compact('products', 'count', 'listAction', 'status'));
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
    function add()
    {
        $product_cats = ProductCat::all();
        $product_cats = $this->data_tree($product_cats);
        return view('admin.product.add', compact('product_cats'));
    }
    function create(Request $request)
    {
        $products = Product::all();
        $request->validate(
            [
                'name' => 'required| max:200',
                'content' => 'required',
                'description' => 'required',
                'price' => 'required',
                'belongsTo' => 'required',
                'code' => 'required|unique:products| max:200',
                'old_price' => 'required',
                'amount' => 'required',
                'thumbnail' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
                'file.max' => ':Attribute đã vượt kích thước tối đa',
                'mimes' => ':Attribute phải là ảnh',
                'unique' => ':Attribute đã tồn tại trước đó',
            ],
            [
                'name' => 'Tên sản phẩm',
                'content' => 'Nội dung',
                'description' => 'Mô tả',
                'belongsTo' => 'Danh mục cha',
                'thumbnail' => 'File upload',
                'price' => 'Giá',
                'old_price' => 'Giá cũ',
                'code' => 'Mã sản phẩm',
                'amount' => 'Số lượng',
            ]
        );
        $file = $request->thumbnail;
        foreach ($products as $product) {
            if ($product->thumbnail == $file->getClientOriginalName())
                return back()->withInput()->with(['error_thumbnail' => 'File upload đã tồn tại trước đó']);
        }
        $status = $request->amount > 0 ? 'on' : 'off';
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'content' => $request->content,
            'description' => $request->description,
            'status' => $status,
            'product_cat_id' => $request->belongsTo,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'code' => $request->code,
            'old_price' => $request->old_price,
            'slug' => Str::slug($request->name . '.' . time()),
            'thumbnail' =>  $file->getClientOriginalName(),
        ]);
        $file->move('public/uploads/product', $file->getClientOriginalName());
        return redirect('admin/product/list')->with('status', 'Đã sản phẩm viết thành công');
    }
    function update($id)
    {
        $product_cats = ProductCat::all();
        $product_cats = $this->data_tree($product_cats);
        $this_product = Product::find($id);
        return view('admin.product.update', compact('this_product', 'product_cats'));
    }

    function edit(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required| max:200',
                'content' => 'required',
                'description' => 'required',
                'price' => 'required',
                'belongsTo' => 'required',
                'old_price' => 'required',
                'amount' => 'required',
                'thumbnail' => 'mimes:jpeg,jpg,png,gif|max:10000'
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
                'file.max' => ':Attribute đã vượt kích thước tối đa',
                'mimes' => ':Attribute phải là ảnh',
                'unique' => ':Attribute đã tồn tại trước đó',
            ],
            [
                'name' => 'Tên sản phẩm',
                'content' => 'Nội dung',
                'description' => 'Mô tả',
                'belongsTo' => 'Danh mục cha',
                'thumbnail' => 'File upload',
                'old_price' => 'Giá cũ',
                'price' => 'Giá',
                'amount' => 'Số lượng',
            ]
        );
        $file = $request->thumbnail;
        $this_post = Product::find($id);
        if ($file) {
            unlink("public/uploads/product/$this_post->thumbnail");
            $this_post->update([
                'thumbnail' => $file->getClientOriginalName(),
            ]);
            $file->move('public/uploads/product', $file->getClientOriginalName());
        }
        $status = $request->amount > 0 ? 'on' : 'off';
        $this_post->update([
            'name' => $request->name,
            'price' => $request->price,
            'content' => $request->content,
            'description' => $request->description,
            'status' => $status,
            'amount' => $request->amount,
            'old_price' => $request->old_price,
            'product_cat_id' => $request->belongsTo,
            'slug' => Str::slug($request->name . '.' . time()),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/product/list')->with('status', 'Đã cập nhật sản phẩm thành công');
    }
    function delete($id)
    {
        Product::find($id)->delete();
        return redirect('admin/product/list')->with('status', 'Đã xóa thành công');
    }
    function forceDelete($id)
    {
        $this_product = Product::onlyTrashed()->find($id);
        unlink("public/uploads/product/$this_product->thumbnail");
        $this_product->forceDelete();
        return redirect('admin/product/list')->with('status', 'Đã xóa vĩnh viễn');
    }

    function action(Request $request)
    {
        $listCheck = $request->listCheck;
        if ($listCheck) {
            $action = $request->action;
            if ($action) {
                if ($action == 'delete') {
                    Product::whereIn('id', $listCheck)->delete();
                    return redirect('admin/product/list')->with('status', 'Đã xóa tạm thời');
                }
                if ($action == 'restore') {
                    Product::onlyTrashed()->whereIn('id', $listCheck)->restore();
                    return redirect('admin/product/list')->with('status', 'Đã khôi phục thành công');
                }
                if ($action == 'forceDelete') {
                    Product::onlyTrashed()->whereIn('id', $listCheck)->forceDelete();
                    return redirect('admin/product/list')->with('status', 'Đã xóa vĩnh viễn');
                }
                if ($action == 'not_active') {
                    Product::whereIn('id', $listCheck)->update([
                        'status' => 'off',
                    ]);
                    return redirect('admin/product/list')->with('status', 'Đã chuyển đến chờ duyệt');
                }
                if ($action == 'active') {
                    Product::whereIn('id', $listCheck)->update([
                        'status' => 'on',
                    ]);
                    return redirect('admin/product/list')->with('status', 'Đã duyệt thành công');
                }
            }
            return redirect('admin/product/list')->with('warning', 'Bạn cần chọn tác vụ');
        }
        return redirect('admin/product/list')->with('warning', 'Bạn cần chọn trang để thực hiện');
    }
}
