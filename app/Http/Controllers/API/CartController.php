<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Goods;
use App\Store;
use App\Cart;

class CartController extends ApiBaseController
{
    protected function getCart()
    {
        // $this
        if (!$cart = $this->user->cart) {
            $cart = Cart::create([
                'store_id' => $this->user->store_id,
                'user_id' => $this->user->id,
                'total_quantity' => 0,
                'total_price' => 0,
            ]);
        }
        return $cart;
    }
    
    // 'goodsId' => '1',
    // 'quantity' => '3',
    public function add($store_id, Request $request)
    {
        if ($cart = $this->getCart()) {
            $cart->add(
                Goods::find($request->input('goodsId')),
                $request->input('quantity')
            );
            return $this->sendResponse([], 'add to cart success');
        }
    }
    
    // 'goodsId' => '1',
    // 'quantity' => '3',
    public function update($store_id, Request $request)
    {
        if ($cart = $this->getCart()) {
            $cart->change(
                Goods::find($request->input('goodsId')),
                $request->input('quantity')
            );
            return $this->sendResponse($cart->detail());
        }
    }
    
    public function info()
    {
        if ($cart = $this->user->cart) {
            return $this->sendResponse($cart->detail());
        }
        return $this->sendResponse([]);
    }
}