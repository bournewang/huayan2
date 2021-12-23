<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Goods;
use App\Store;
use App\Cart;

class ConfigController extends ApiBaseController
{
    protected function get()
    {
        return $this->sendResponse([
            'mallName' => config('app.name')
        ]);
        $data =file_get_contents(base_path('config.json'));
        return $data;
    }
}