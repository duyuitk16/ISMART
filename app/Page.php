<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'content', 'user_id', 'slug'
    ];
    function user()
    {
        return  $this->belongsTo('App\User')->withTrashed();
    }
}
