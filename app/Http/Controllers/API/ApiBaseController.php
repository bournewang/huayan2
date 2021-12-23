<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Auth;

class ApiBaseController extends AppBaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $user = null;
    protected $store = null;

    public function __construct(Request $request)
    {
        if ($this->user = Auth::guard('api')->user()) {
            $this->store = $this->user->store;
        }elseif (!app()->runningInConsole()){
            // throw new ApiException('请重新登录', 999);
        }
    }

}
