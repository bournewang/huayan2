<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Goods;
use App\Store;

class CategoryController extends ApiBaseController
{
    public function index($store_id, Request $request)
    {
        if (!$store = Store::find($store_id)) {
            return $this->sendError("非法商户");
        }
        $data = [];
        foreach ($store->categories as $category) {
            $data[] = $category->show();
        }
        return $this->sendResponse($data);
    }
}
