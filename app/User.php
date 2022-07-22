<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    function pages()
    {
        return $this->hasOne('App\Page');
    }
    // function posts()
    // {
    //     return $this->hasMany('App\Post');
    // }
    function PostCats()
    {
        return $this->hasMany('App\PostCat');
    }
    // function products()
    // {
    //     return $this->hasMany('App\Product');
    // }
    function ProductCats()
    {
        return $this->hasMany('App\ProductCat');
    }
    function role()
    {
        return $this->belongsTo('App\Role');
    }
}
