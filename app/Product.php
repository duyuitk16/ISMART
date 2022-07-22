<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'thumbnail', 'amount', 'name', 'description', 'price', 'content', 'product_cat_id', 'user_id', 'status', 'slug', 'old_price', 'code'
    ];
    function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    function ProductCat()
    {
        return $this->belongsTo('App\ProductCat')->withTrashed();
    }
}
