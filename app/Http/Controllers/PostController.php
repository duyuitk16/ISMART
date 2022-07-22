<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostCat;
use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;

class PostController extends Controller
{
    function show()
    {
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        $posts = Post::where('status', 'on')->paginate(10);
        return view('client.post.show', compact('posts', 'selling_products', 'product_cats'));
    }
    function detailShow($namePost)
    {
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        $this_post = Post::where('slug', $namePost)->first();
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        return view('client.post.detail.show', compact('selling_products', 'this_post', 'product_cats'));
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
}
