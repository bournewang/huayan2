<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
// use App\Models\Goods;
use App\Models\Goods;
use App\Models\Goods;
use App\Models\Goods;

class RegionController extends ApiBaseController
{
    public function provinces(Request $request)
    {
        return (hash2array(Province::all()->pluck('name','id')->all()));
    }
    
    public function cities($province_id, Request $request)
    {
        if ($province = Province::find($province_id)) {
            return (hash2array($province->cities->pluck('name','id')->all()));
        }
    }
    
    public function districts($city_id, Request $request)
    {
        if ($city = City::find($city_id)) {
            return (hash2array($city->districts->pluck('name','id')->all()));
        }
    }
}
