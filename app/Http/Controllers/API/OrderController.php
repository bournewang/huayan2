<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use function EasyWeChat\Kernel\Support\generate_sign;
use App\Models\Order;
use App\Models\Address;
class OrderController extends ApiBaseController
{    
    /**
     * Get order list 获取订单列表
     *
     * @OA\Get(
     *  path="/api/orders",
     *  tags={"Order"},
     *  @OA\Parameter(name="status",  in="query",required=false,explode=true,@OA\Schema(type="string"),description="order status"),
     *  @OA\Parameter(name="perpage", in="query",required=false,explode=true,@OA\Schema(type="integer"),description="order number per page"),
     *  @OA\Parameter(name="page",    in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page no"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */    
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

    /**
     * Place an order 提交订单
     *
     * @OA\Post(
     *   path="/api/orders",
     *   tags={"Order"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="address_id",
     *                   description="Address id",
     *                   type="integer"
     *               )
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function create(Request $request)
    {
        if ($cart = $this->user->cart) {
            if (!$addr = Address::find($request->input('id'))){
                return $this->sendError("没有选择地址");
            }
            $order = $cart->submit($addr);
            return $this->sendResponse($order->id, 'create order success');
        }
    }
    
    /**
     * Get order detail 获取订单详情
     *
     * @OA\Get(
     *  path="/api/orders/{id}",
     *  tags={"Order"},
     *  @OA\Parameter(name="id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="order id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function show($id)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__." $id");
        if ($order = Order::find($id)) {
            return $this->sendResponse($order->detail());
        }
        return $this->sendResponse([]);
    }
    
    /**
     * submit to wechat payment order 创建微信支付订单
     *
     * @OA\Put(
     *  path="/api/orders/{id}/place",
     *  tags={"Order"},
     *  @OA\Parameter(name="id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="order id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function place($id, Request $request) 
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