<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
// use App\Goods;
use App\Province;
use App\City;
use App\District;

class RegionController extends ApiBaseController
{
    public function provinces($store_id, Request $request)
    {
        return $this->sendResponse(hash2array(Province::all()->pluck('name','id')->all()));
    }
    
    public function cities($store_id, Request $request)
    {
        if ($province = Province::find($request->input('province_id'))) {
            return $this->sendResponse(hash2array($province->cities->pluck('name','id')->all()));
        }
    }
    
    public function districts($store_id, Request $request)
    {
        if ($city = City::find($request->input('city_id'))) {
            return $this->sendResponse(hash2array($city->districts->pluck('name','id')->all()));
        }
    }
}
