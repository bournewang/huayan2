<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Models\Order;
use Log;

class MappController extends ApiBaseController
{
    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        return json_encode(['success' => true]);
    }
}