<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function default(Request $request)
    {
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        if ($request->keyword) {
            $products = Product::where('name', 'like', "%{$request->keyword}%")->paginate(12);
            $count_products = Product::where('name', 'like', "%{$request->keyword}%")->count();
            return view('client.product.show', compact('products', 'product_cats', 'count_products', 'selling_products'));
        } else {
            $this_cat = ProductCat::where([['parent_id', 0], ['status', 'on']])->first();
            $child[] = $this_cat->id;
            $all_product_cats = ProductCat::where('status', 'on')->get();
            searchChildren($all_product_cats, $this_cat->id, $child);
            $products = Product::whereIn('product_cat_id', $child)->orderBy('created_at', 'DESC')->paginate(12);
            $count_products = Product::whereIn('product_cat_id', $child)->count();
            if ($request->sort == 1) {
                $products = Product::whereIn('product_cat_id', $child)->orderBy('name', 'ASC')->paginate(12);
            } elseif ($request->sort == 2) {
                $products = Product::whereIn('product_cat_id', $child)->orderBy('name', 'DESC')->paginate(12);
            } elseif ($request->sort == 3) {
                $products = Product::whereIn('product_cat_id', $child)->orderBy('price', 'DESC')->paginate(12);
            } elseif ($request->sort == 4) {
                $products = Product::whereIn('product_cat_id', $child)->orderBy('price', 'ASC')->paginate(12);
            } else {
                $products = Product::whereIn('product_cat_id', $child)->orderBy('created_at', 'DESC')->paginate(12);
            }
            return view('client.product.show', compact('products', 'product_cats', 'this_cat', 'count_products', 'selling_products'));
        }
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
    function show($name, Request $request)
    {
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        $this_cat = ProductCat::where([['slug', $name]])->first();
        $child[] = $this_cat->id;
        $all_product_cats = ProductCat::where('status', 'on')->get();
        searchChildren($all_product_cats, $this_cat->id, $child);
        $count_products =  Product::whereIn('product_cat_id', $child)->count();
        if ($request->sort == 1) {
            $products = Product::whereIn('product_cat_id', $child)->orderBy('name', 'ASC')->paginate(12);
        } elseif ($request->sort == 2) {
            $products = Product::whereIn('product_cat_id', $child)->orderBy('name', 'DESC')->paginate(12);
        } elseif ($request->sort == 3) {
            $products = Product::whereIn('product_cat_id', $child)->orderBy('price', 'DESC')->paginate(12);
        } elseif ($request->sort == 4) {
            $products = Product::whereIn('product_cat_id', $child)->orderBy('price', 'ASC')->paginate(12);
        } else {
            $products = Product::whereIn('product_cat_id', $child)->orderBy('created_at', 'DESC')->paginate(12);
        }
        return view('client.product.show', compact('products', 'product_cats', 'this_cat', 'count_products', 'selling_products'));
    }

    function detailShow($nameCat, $nameProduct)
    {
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        $this_product = Product::where('slug', $nameProduct)->first();
        $this_cat = ProductCat::where('slug', $nameCat)->first();
        $relative_products = Product::where([['product_cat_id', $this_cat->id], ['id', '!=', $this_product->id]])->orderBy('created_at', 'DESC')->limit(8)->get();
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        return view('client.product.detail.show', compact('this_cat', 'this_product', 'product_cats', 'relative_products', 'selling_products'));
    }
}
