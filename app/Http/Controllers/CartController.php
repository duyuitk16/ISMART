<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;
use  Gloudemans\Shoppingcart\Facades\Cart;
use RealRashid\SweetAlert\Facades\Alert;

class CartController extends Controller
{
    function show()
    {
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        $amount = [];
        foreach (Cart::content() as $row)
            $amount[$row->rowId] = Product::where('slug', $row->options->slug)->first()->amount;
        $products = Cart::content();
        return view('client.cart.show', compact('products', 'product_cats', 'amount'));
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
    function add($slug, Request $request)
    {
        $product = Product::where([['slug', $slug], ['status', 'on']])->first();
        if ($product) {
            $qty = 1;
            if ($request->num_order) {
                $qty = $request->num_order;
            }
            if (Cart::count() > 0) {
                foreach (Cart::content() as $row) {
                    if ($row->options->slug == $product->slug) {
                        if (($row->qty + $qty) > $product->amount) {
                            Alert::warning('Cảnh báo', 'Số lượng trong kho không đáp ứng nhu cầu này');
                            return redirect()->back();
                        }
                    }
                }
            }
            Cart::add([
                'id' => $product->id,
                'price' => $product->price,
                'qty' => $qty,
                'name' => $product->name,
                'options' => ['thumbnail' => $product->thumbnail, 'code' => $product->code, 'cat_slug' => $product->ProductCat->slug, 'slug' => $product->slug,]
            ]);
            if ($request->buy_now)
                return  redirect('thanh-toan');
            return  redirect('gio-hang')->with('status', "Đã thêm sản phẩm '<strong>'$product->name'</strong>' vào giỏ hàng");
        }
        Alert::warning('Xin lỗi', 'Sản phẩm này đã hết hàng');
        return redirect()->back();
    }
    function delete($rowId)
    {
        $product = Cart::get($rowId);
        Cart::remove($rowId);
        return  redirect('gio-hang')->with('status', "Đã xóa sản phẩm '<strong>'$product->name'</strong>' ra khỏi giỏ hàng");
    }
    function destroy()
    {
        Cart::destroy();
        return redirect('gio-hang')->with('status', "Đã xóa giỏ hàng thành công");
    }
    function update(Request $request)
    {
        Cart::update($request->rowId, ['qty' => $request->num_order]);
        $sub_total = number_format(Cart::get($request->rowId)->total, 0, ',', '.') . 'đ';
        $total = Cart::subtotal() . 'đ';
        $num = Cart::count();
        $qty = Cart::get($request->rowId)->qty;
        return response()->json([
            'sub_total' =>  $sub_total,
            'total' =>  $total,
            'num' =>  $num,
            'qty' =>  $qty,
        ]);
    }
}
