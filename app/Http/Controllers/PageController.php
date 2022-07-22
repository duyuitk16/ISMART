<?php

namespace App\Http\Controllers;

use App\Page;
use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;

class PageController extends Controller
{
    function show($namePage)
    {
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        $selling_products = Product::where('status', 'on')->orderBy('amount', 'ASC')->limit(10)->get();
        $page = Page::where([['slug', $namePage], ['status', 'on']])->first();
        return view('client.page.show', compact('page', 'selling_products', 'product_cats'));
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
