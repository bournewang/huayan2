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
    Route::get('/provinces',             [RegionController::class, 'provinces']);
    Route::get('/provinces/{province_id}/cities',   [RegionController::class, 'cities']);
    Route::get('/cities/{city_id}/districts',       [RegionController::class, 'districts']);
    Route::get('/goods',                [GoodsController::class, 'index']);
    Route::get('/goods/{id}',           [GoodsController::class, 'detail']);
    Route::get('/categories',           [CategoryController::class, 'index']);
    Route::get('/banners',              [BannerController::class, 'index']);
    // Route::post('/user/wxapp/authorize',[WechatController::class, 'login']);
    // Route::post('/user/wxapp/register', [WechatController::class, 'register']);
    // Route::post('/user/wxapp/login',    [WechatController::class, 'login']);
    // Route::post('/wechat/notify',       [WechatController::class, 'notify']);
// });

Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/user/info',   [UserController::class, 'info']);
    Route::post('/user/modify',[UserController::class, 'modify']);
    Route::post('/user/mobile',[UserController::class, 'mobile']);
    Route::get ('/user/revenue',[UserController::class, 'revenue']);
    Route::get ('/user/qrcode',[UserController::class, 'qrcode']);
    
    Route::get   ('/cart',                       [CartController::class, 'show']);
    Route::post  ('/cart/{goods_id}', [CartController::class, 'add']);
    Route::patch ('/cart/{goods_id}', [CartController::class, 'update']);
    Route::delete('/cart/{goods_id}', [CartController::class, 'delete']);
    
    Route::get ('/orders',              [OrderController::class, 'index']);
    Route::get ('/orders/{id}',         [OrderController::class, 'show']);    
    Route::post('/orders',              [OrderController::class, 'create']);
    Route::post('/orders/{id}/place',   [OrderController::class, 'place']);
    
    Route::post  ('/goods/{id}/like',   [GoodsController::class, 'like']);
    Route::delete('/goods/{id}/like',   [GoodsController::class, 'dislike']);
    
    Route::get      ('/address',        [AddressController::class, 'index']);
    Route::get      ('/address/default',[AddressController::class, 'default']);
    Route::post     ('/address',        [AddressController::class, 'create']);
    Route::get      ('/address/current',[AddressController::class, 'current']);
    Route::get      ('/address/{id}',   [AddressController::class, 'show']);
    Route::patch    ('/address/{id}',   [AddressController::class, 'update']);
    Route::delete   ('/address/{id}',   [AddressController::class, 'delete']);
    Route::post('/address/{id}/select', [AddressController::class, 'select']);
    
    // direct selling
    Route::post('/sale/apply', [SaleController::class, 'apply']);
    Route::get('/sale/members',[SaleController::class, 'members']);
});