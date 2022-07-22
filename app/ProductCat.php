<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCat extends Model
{
    use SoftDeletes;
    function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    protected $fillable = [
        'name', 'user_id', 'status', 'parent_id', 'slug'
    ];
}
