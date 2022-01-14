<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\User;
use App\Helpers\StoreHelper;

class SalesController extends ApiBaseController
{
    /**
     * Orders in a store(for manager/clerk and referer) 门店顾客的消费数据，店长查看本店的数据，店员/推荐人则返回自己推荐顾客的数据
     *
     * @OA\Get(
     *  path="/api/sales",
     *  tags={"Sales"},
     *  @OA\Parameter(name="prev_month",    in="query",required=false,explode=true,@OA\Schema(type="integer"),description="previous month sales data"),
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */       
    public function index(Request $request)
    {
        $data = StoreHelper::salesStatsBySenior($this->user, date('Y-m'), $request->input('perpage', 20));
        return $this->sendResponse($data);
    }

    /**
     * User's orders 用户的消费订单
     *
     * @OA\Get(
     *  path="/api/sales/{user_id}",
     *  tags={"Sales"},
     *  @OA\Parameter(name="user_id",    in="path",required=true,explode=true,@OA\Schema(type="integer"),description="user id"),
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */  
    public function show($id, Request $request)
    {
        if (!$user = User::find($id) ){
            return $this->sendError("没有找到该顾客");
        }
        if ($user->store_id != $this->user->store_id) {
            return $this->sendError("您只能查看自己门店的数据");
        }
        $data = StoreHelper::salesStatsBySenior($user, date('Y-m'), $request->input('perpage', 20));
        return $this->sendResponse($data);
    }
}
