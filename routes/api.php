<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::any('/wechat', 'WechatController@serve');

Route::group(['middleware' => [], 'prefix' => 'shop/{store_id}'], function(){
    Route::get('/config/values',        'API\ConfigController@get')->name('config.get');
    Route::get('/province/list',        'API\RegionController@provinces')->name('province.index');
    Route::get('/city/list',            'API\RegionController@cities')->name('city.index');
    Route::get('/district/list',        'API\RegionController@districts')->name('district.index');
    Route::get('/goods/list',           'API\GoodsController@index')->name('goods.index');
    Route::get('/goods/{id}',           'API\GoodsController@detail')->name('goods.detail');
    Route::get('/category/all',         'API\CategoryController@index')->name('category.index');
    Route::get('/banner/list',          'API\BannerController@index')->name('banner.index');
    Route::post('/user/wxapp/authorize','WechatController@login')->name('user.wxauthorize');
    Route::post('/user/wxapp/register', 'WechatController@register')->name('user.wxregister');
    Route::post('/user/wxapp/login',    'WechatController@login')->name('user.wxlogin');
    Route::post('/wechat/notify','WechatController@notify'); //微信支付回调
});


Route::group(['middleware' => ['auth:api'], 'prefix' => 'shop/{store_id}'], function(){
    Route::get('/user/info',   'API\UserController@info')->name('user.info');
    Route::post('/user/modify','API\UserController@modify')->name('user.modify');
    Route::post('/user/mobile','API\UserController@mobile')->name('user.mobile');
    Route::get ('/user/revenue','API\UserController@revenue')->name('user.revenue');
    Route::get ('/user/qrcode','API\UserController@qrcode')->name('user.qrcode');
    Route::get('/cart/info',   'API\CartController@info')->name('cart.info');
    Route::post('/cart/add',   'API\CartController@add')->name('cart.add');
    Route::post('/cart/update', 'API\CartController@update')->name('cart.update');
    Route::post('/order/create',        'API\OrderController@create')->name('order.create');
    Route::post('/order/{id}/place',    'API\OrderController@place')->name('order.place'); //生成微信支付订单
    Route::get('/order/list',   'API\OrderController@index')->name('order.index');
    Route::get('/order/{id}',   'API\OrderController@show')->name('order.show');
    // Route::get('/goods/{id}/fav',   'API\GoodsController@fav')->name('goods.fav');
    Route::post('/goods/{id}/like',  'API\GoodsController@like')->name('goods.like');
    Route::delete('/goods/{id}/like', 'API\GoodsController@dislike')->name('goods.dislike');
    Route::get      ('/address/list',   'API\AddressController@index')  ->name('address.index');
    Route::get      ('/address/default','API\AddressController@default')->name('address.default');
    Route::post     ('/address',        'API\AddressController@create') ->name('address.create');
    Route::get      ('/address/current','API\AddressController@current')->name('address.current');
    Route::get      ('/address/{id}',   'API\AddressController@show')   ->name('address.show');
    Route::post     ('/address/{id}',   'API\AddressController@update') ->name('address.update');
    Route::delete   ('/address/{id}',   'API\AddressController@delete') ->name('address.delete');
    Route::post('/address/{id}/select', 'API\AddressController@select')->name('address.select');
    
    
    // direct selling
    Route::post('/sale/apply', 'API\SaleController@apply')->name('sale.apply');
    Route::get('/sale/members','API\SaleController@members')->name('sale.members');
});