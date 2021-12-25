<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\ApiBaseController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ConfigController;
use App\Http\Controllers\API\GoodsController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\WechatController;
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


// Route::any('/wechat', 'WechatController@serve');

// Route::group(['middleware' => []], function(){
    Route::get('/config/values',        [ConfigController::class, 'get']); //->name('config.get');
    Route::get('/province/list',        [RegionController::class, 'provinces']);
    Route::get('/provinces/{province_id}/cities',   [RegionController::class, 'cities']);
    Route::get('/cities/{city_id}/districts',       [RegionController::class, 'districts']);
    Route::get('/goods/list',           [GoodsController::class, 'index']);
    Route::get('/goods/{id}',           [GoodsController::class, 'detail']);
    Route::get('/category/all',         [CategoryController::class, 'index']);
    Route::get('/banner/list',          [BannerController::class, 'index']);
    Route::post('/user/wxapp/authorize',[WechatController::class, 'login']);
    Route::post('/user/wxapp/register', [WechatController::class, 'register']);
    Route::post('/user/wxapp/login',    [WechatController::class, 'login']);
    Route::post('/wechat/notify',       [WechatController::class, 'notify']);
// });

Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/user/info',   [UserController::class, 'info']);
    Route::post('/user/modify',[UserController::class, 'modify']);
    Route::post('/user/mobile',[UserController::class, 'mobile']);
    Route::get ('/user/revenue',[UserController::class, 'revenue']);
    Route::get ('/user/qrcode',[UserController::class, 'qrcode']);
    Route::get('/cart/info',   [CartController::class, 'info']);
    Route::post('/cart/add',   [CartController::class, 'add']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::post('/order/create',        [OrderController::class, 'create']);
    Route::post('/order/{id}/place',    [OrderController::class, 'place']);
    Route::get('/order/list',   [OrderController::class, 'index']);
    Route::get('/order/{id}',   [OrderController::class, 'show']);
    Route::post('/goods/{id}/like',  [GoodsController::class, 'like']);
    Route::delete('/goods/{id}/like', [GoodsController::class, 'dislike']);
    Route::get      ('/address/list',   [AddressController::class, 'index']);
    Route::get      ('/address/default',[AddressController::class, 'default']);
    Route::post     ('/address',        [AddressController::class, 'create']);
    Route::get      ('/address/current',[AddressController::class, 'current']);
    Route::get      ('/address/{id}',   [AddressController::class, 'show']);
    Route::post     ('/address/{id}',   [AddressController::class, 'update']);
    Route::delete   ('/address/{id}',   [AddressController::class, 'delete']);
    Route::post('/address/{id}/select', [AddressController::class, 'select']);
    
    
    // direct selling
    Route::post('/sale/apply', [SaleController::class, 'apply']);
    Route::get('/sale/members',[SaleController::class, 'members']);
});