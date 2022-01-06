<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Logistic;

class LogisticController extends ApiBaseController
{    
    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
    }
}