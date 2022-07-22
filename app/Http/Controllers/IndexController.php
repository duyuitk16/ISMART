<?php

namespace App\Http\Controllers;

use App\Page;
use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    function show()
    {
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        // $pages = Page::where('status', 'on')->get();
        $outstanding_products = Product::where('status', 'on')->orderBy('created_at', 'DESC')->limit(20)->get();
        $parent_product_cats = ProductCat::where([['status', 'on'], ['parent_id', 0]])->get();

        foreach ($parent_product_cats as $product_cat) {
            $child[] = $product_cat->id;
            $all_product_cats = ProductCat::where('status', 'on')->get();
            searchChildren($all_product_cats, $product_cat->id, $child);

            $list_products_by[$product_cat->id] = Product::whereIn('product_cat_id', $child)->where('status', 'on')->limit(8)->get();
            unset($child);
        }
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        return view('client.home', compact('product_cats', 'outstanding_products', 'list_products_by', 'parent_product_cats', 'selling_products'));
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
    function search(Request $request)
    {
        $output = "";
        $products = Product::where('name', 'LIKE', "%" . $request->keyword . "%")->orderBy('created_at', 'DESC')->limit(5)->get();
        foreach ($products as $product) {
            $output .= '<li class="product-suggest">
            <a href="' . url("san-pham/{$product->ProductCat->slug}/{$product->slug}.html") . '" title="" class="">
                <div class="item-img">
                    <img  src="' . asset("uploads/product/$product->thumbnail") . '" alt="">
                </div>
                <div class="item-info">
                    <h3 class="product-name">' . $product->name . '</h3>
                    <strong class="price-new">' . number_format($product->price, 0, ',', '.') . 'đ</strong>
                    <strong class="price-old">' . number_format($product->old_price, 0, ',', '.') . 'đ</strong>
                </div>
            </a>
        </li>';
        }
        return response()->json($output);
    }
    function checkMail()
    {
        return view('client.mail');
    }
}
