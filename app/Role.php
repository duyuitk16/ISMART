<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'user_id',
    ];
    function user()
    {
        return $this->belongsTo('App\User');
    }
}
