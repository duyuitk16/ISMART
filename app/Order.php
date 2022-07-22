<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'fullname', 'email', 'phone', 'note', 'checkout_method', 'orders', 'bill', 'status', 'checkConfirm', 'address', 'code'
    ];
}
