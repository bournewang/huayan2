<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Goods;
use App\User;

class SaleController extends ApiBaseController
{
    public function apply($store_id, Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        $input = $request->all();
        $input['apply_status'] = User::APPLYING;
        $this->user->update($input);
        return $this->sendResponse($this->user->info());
    }
    
    public function members(Request $request)
    {
        if ($request->input('level') == 1) {
            $members = $this->user->juniors();
        }else{
            // $members = $this->user->members();
        }
        $members = $members->paginate(config('mall.per_page'));
        $data = [];
        foreach ($members as $user) {
            $info = $user->info();
            $info['orders'] = rand(2,5);
            $data[] = $info;
        }
        
        // return $this->sendResponse($members);
        return $this->sendResponse([
            'total' => $members->total(),
            'pages' => $members->lastPage(),
            'page'  => $request->input('page', 1),
            'data' => $data,
        ]);
    }
}
