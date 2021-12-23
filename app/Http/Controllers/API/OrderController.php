<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use function EasyWeChat\Kernel\Support\generate_sign;
use App\Order;
use App\Address;
class OrderController extends ApiBaseController
{    
    public function index(Request $request)
    {
        $orders = $this->user->orders();
        if ($s = $request->input('status')) {
            
        }
        $orders = $orders->orderBy('id', 'desc')->paginate(config('mall.per_page', 10));
        $data = [];
        foreach ($orders as $order) {
            $data[] = $order->detail();
        }
        
        return $this->sendResponse($data);
    }

    public function create($store_id, Request $request)
    {
        if ($cart = $this->user->cart) {
            if (!$addr = Address::find($request->input('id'))){
                return $this->sendError("没有选择地址");
            }
            $order = $cart->submit($addr);
            return $this->sendResponse($order->id, 'create order success');
        }
    }
    
    public function show($store_id, $id)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__." $id");
        if ($order = Order::find($id)) {
            return $this->sendResponse($order->detail());
        }
        return $this->sendResponse([]);
    }
    
    public function place($store_id, $id, Request $request) 
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__." order $id");
        if (!$order = Order::find($id)) {
            
        }
        $app = \EasyWeChat::payment();
        $result = $app->order->unify([
            'body' => 'xxx-test-order',
            'out_trade_no' => $order->orderNo,
            'total_fee' => $order->orderAmount * 100,
            'trade_type' => 'JSAPI', 
            'sign_type' => 'MD5',
            'openid' => $this->user->openid
        ]);               
        \Log::debug($result);
        if ($result['return_code']  === 'SUCCESS' && $result['result_code'] === 'SUCCESS')
        {
            // 二次验签
            $params = [
                'appId'     => config('wechat.payment.default.app_id'),
                'timeStamp' => time(),
                'nonceStr'  => $result['nonce_str'],
                'package'   => 'prepay_id=' . $result['prepay_id'],
                'signType'  => 'MD5',
            ];

            // config('wechat.payment.default.key')为商户的key
            $params['paySign'] = generate_sign($params, config('wechat.payment.default.key'));
            
            return $this->sendResponse($params);
        } else {
            return $this->sendError($result['err_code_des'] ?? '下单失败');
        }
    }
}