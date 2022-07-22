<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false
]);
// ->middleware('verified')

// Route::get('/home', 'HomeController@index')->name('home');
Route::middleware('auth')->group(function () {
    Route::get('dashboard', 'DashboardController@show');
    Route::get('admin', 'DashboardController@show');
    Route::get('dashboard/destroy/{id}', 'DashboardController@destroy')->middleware('CheckRole:Supervisor,Administator');;
    Route::get('dashboard/delete/{id}', 'DashboardController@delete')->middleware('CheckRole:Supervisor,Administator');
    //END DASHBOARD
    Route::get('admin/user/list', 'AdminUserController@show');
    Route::get('admin/user/add', 'AdminUserController@add')->middleware('CheckRole:Administator');
    Route::get('admin/user/delete/{id}', 'AdminUserController@delete')->middleware('CheckRole:Administator');
    Route::get('admin/user/forceDelete/{id}', 'AdminUserController@forceDelete')->middleware('CheckRole:Administator');
    Route::get('admin/user/update/{id}', 'AdminUserController@update');
    Route::post('admin/user/action', 'AdminUserController@action')->middleware('CheckRole:Administator');
    Route::post('admin/user/create', 'AdminUserController@create')->middleware('CheckRole:Administator');
    Route::post('admin/user/edit/{id}', 'AdminUserController@edit');
    // END USER
    Route::get('admin/page/list', 'AdminPageController@show');
    Route::get('admin/page/delete/{id}', 'AdminPageController@delete')->middleware('CheckRole:PageCreator,Administator');
    Route::get('admin/page/forceDelete/{id}', 'AdminPageController@forceDelete')->middleware('CheckRole:PageCreator,Administator');
    Route::post('admin/page/action', 'AdminPageController@action')->middleware('CheckRole:PageCreator,Administator');
    Route::get('admin/page/add', 'AdminPageController@add')->middleware('CheckRole:PageCreator,Administator');
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    Route::post('admin/page/create', 'AdminPageController@create')->middleware('CheckRole:PageCreator,Administator');
    Route::post('admin/page/edit/{id}', 'AdminPageController@edit')->middleware('CheckRole:PageCreator,Administator');
    Route::get('admin/page/update/{id}', 'AdminPageController@update')->middleware('CheckRole:PageCreator,Administator');
    //END PAGE
    Route::get('admin/post/cat/list', 'AdminPostCatController@show');
    Route::post('admin/post/cat/add', 'AdminPostCatController@add')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/cat/delete/{id}', 'AdminPostCatController@delete')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/cat/forceDelete/{id}', 'AdminPostCatController@forceDelete')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/cat/update/{id}', 'AdminPostCatController@update')->middleware('CheckRole:PostCreator,Administator');
    Route::post('admin/post/cat/edit/{id}', 'AdminPostCatController@edit')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/cat/restore/{id}', 'AdminPostCatController@restore')->middleware('CheckRole:PostCreator,Administator');
    //END POST_CAT
    Route::get('admin/post/list', 'AdminPostController@show');
    Route::get('admin/post/add', 'AdminPostController@add')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/update/{id}', 'AdminPostController@update')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/delete/{id}', 'AdminPostController@delete')->middleware('CheckRole:PostCreator,Administator');
    Route::get('admin/post/forceDelete/{id}', 'AdminPostController@forceDelete')->middleware('CheckRole:PostCreator,Administator');
    Route::post('admin/post/edit/{id}', 'AdminPostController@edit')->middleware('CheckRole:PostCreator,Administator');
    Route::post('admin/post/create', 'AdminPostController@create')->middleware('CheckRole:PostCreator,Administator');
    Route::post('admin/post/action', 'AdminPostController@action')->middleware('CheckRole:PostCreator,Administator');
    //END POST
    Route::get('admin/product/cat/list', 'AdminProductCatController@show');
    Route::post('admin/product/cat/add', 'AdminProductCatController@add')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/cat/delete/{id}', 'AdminProductCatController@delete')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/cat/forceDelete/{id}', 'AdminProductCatController@forceDelete')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/cat/update/{id}', 'AdminProductCatController@update')->middleware('CheckRole:ProductCreator,Administator');
    Route::post('admin/product/cat/edit/{id}', 'AdminProductCatController@edit')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/cat/restore/{id}', 'AdminProductCatController@restore')->middleware('CheckRole:ProductCreator,Administator');
    //END PRODUCT_CAT
    Route::get('admin/product/list', 'AdminProductController@show');
    Route::get('admin/product/add', 'AdminProductController@add')->middleware('CheckRole:ProductCreator,Administator');
    Route::post('admin/product/create', 'AdminProductController@create')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/update/{id}', 'AdminProductController@update')->middleware('CheckRole:ProductCreator,Administator');
    Route::post('admin/product/edit/{id}', 'AdminProductController@edit')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/delete/{id}', 'AdminProductController@delete')->middleware('CheckRole:ProductCreator,Administator');
    Route::get('admin/product/forceDelete/{id}', 'AdminProductController@forceDelete')->middleware('CheckRole:ProductCreator,Administator');
    Route::post('admin/product/action', 'AdminProductController@action')->middleware('CheckRole:ProductCreator,Administator');
    //END PRODUCT

    Route::get('admin/order/list', 'AdminOrderController@show');
    Route::get('admin/order/restore/{id}', 'AdminOrderController@restore')->middleware('CheckRole:Supervisor,Administator');
    Route::get('admin/order/delete/{id}', 'AdminOrderController@delete')->middleware('CheckRole:Supervisor,Administator');
    Route::get('admin/order/forceDelete/{id}', 'AdminOrderController@forceDelete')->middleware('CheckRole:Supervisor,Administator');
    Route::get('admin/order/destroy/{id}', 'AdminOrderController@destroy')->middleware('CheckRole:Supervisor,Administator');
    Route::get('admin/order/solve/{id}', 'AdminOrderController@solve')->middleware('CheckRole:Supervisor,Administator');
    Route::post('admin/order/action', 'AdminOrderController@action')->middleware('CheckRole:Supervisor,Administator');
    //END ORDER

    Route::get('admin/role/list', 'AdminRoleController@show');
    Route::post('admin/role/add', 'AdminRoleController@add')->middleware('CheckRole:Administator');
    Route::get('admin/role/delete/{id}', 'AdminRoleController@delete')->middleware('CheckRole:Administator');
    Route::get('admin/role/forceDelete/{id}', 'AdminRoleController@forceDelete')->middleware('CheckRole:Administator');
    Route::get('admin/role/restore/{id}', 'AdminRoleController@restore')->middleware('CheckRole:Administator');
    Route::get('admin/role/update/{id}', 'AdminRoleController@update')->middleware('CheckRole:Administator');
    Route::post('admin/role/edit/{id}', 'AdminRoleController@edit')->middleware('CheckRole:Administator');
    //END ROLE
});
// ===========================END ADMIN===========================
Route::get('/', 'IndexController@show');
Route::get('trang-chu', 'IndexController@show');
Route::get('trang-chu/checkMail', 'IndexController@checkMail');
Route::get('tim-kiem', 'IndexController@search');
Route::get('trang/{namePage}.html', 'PageController@show');
Route::get('bai-viet', 'PostController@show');
Route::get('bai-viet/{namePost}.html', 'PostController@detailShow');

Route::get('san-pham/', 'ProductController@default');
Route::get('san-pham/{name}', 'ProductController@show');
Route::get('san-pham/{nameCat}/{nameProduct}.html', 'ProductController@detailShow');

//Giỏ hàng
Route::get('gio-hang', 'CartController@show');
Route::get('gio-hang/them/{slug}', 'CartController@add')->name('add.cart');
Route::get('gio-hang/xoa-gio-hang', 'CartController@destroy');
Route::get('gio-hang/xoa/{rowId}', 'CartController@delete');
Route::post('gio-hang/cap-nhat', 'CartController@update');

//Thanh toán
Route::get('thanh-toan', 'CheckoutController@show');
Route::post('thanh-toan/xu-li', 'CheckoutController@order');
Route::get('thanh-toan/xac-nhan', 'CheckoutController@confirm');
