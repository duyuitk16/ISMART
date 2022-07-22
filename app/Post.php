<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'thumbnail', 'title', 'description', 'content', 'post_cat_id', 'user_id', 'status', 'slug'
    ];
    function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    function PostCat()
    {
        return $this->belongsTo('App\PostCat')->withTrashed();
    }
}
