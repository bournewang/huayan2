<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Store;

class BannerController extends ApiBaseController
{
    public function index($store_id, Request $request)
    {
        if (!$store = Store::find($store_id)) {
            return $this->sendError("非法商户");
        }
        $data = [];
        foreach ($store->banners->where('status', 1) as $banner) {
            $data[] = $banner->show();
        }
        return $this->sendResponse($data);
    }
}
