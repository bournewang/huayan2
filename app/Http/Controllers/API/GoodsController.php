<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Goods;
use App\Store;
use App\Category;

class GoodsController extends ApiBaseController
{
    public function index($store_id, Request $request)
    {
        if (!$store = Store::find($store_id)) {
            return $this->sendError("非法商户");
        }
        $goods = $store->goods();
        if ($cat = Category::find($request->input('categoryId'))) {
            $ids = $cat->children()->pluck('id')->all();
            $ids[] = $cat->id;
            $goods = $goods->whereIn('category_id', $ids);
        }
        if ($key = $request->input('k')) {
            $goods = $goods->where('name', 'like', "%$key%");
        }
        if ($hot = $request->input('hot')) {
            $goods = $goods->wherePivot('hot', $hot);
        }
        if ($recommend = $request->input('recommend')) {
            $goods = $goods->wherePivot('recommend', $recommend);
        }
        $goods = $goods->paginate($request->input('perpage', 20));
        
        $data = [];
        foreach ($goods as $good) {
            $data[] = $good->show();
        }
        return $this->sendResponse($data);
    }
    
    public function detail($store_id, $id, Request $request)
    {
        $data = [];
        // $id = $request->input('id');
        if ($goods = Goods::find($id)) {
            $data = $goods->detail();
        }
        if ($this->user) {
            $data['faved'] = !!$this->user->likes()->find($id);
        }
        return $this->sendResponse($data);
    }
    
    public function like($store, $id)
    {
        if (!$goods = Goods::find($id)) {
            // $data = $goods->
        }
        
        $this->user->likes()->syncWithoutDetaching($id);
        $this->user->save();
        
        return $this->sendResponse(null);
    }
    
    public function dislike($store, $id)
    {
        if (!$goods = Goods::find($id)) {
            // $data = $goods->
        }
        
        $this->user->likes()->detach($id);
        $this->user->save();
        
        return $this->sendResponse(null);
    }
}
