<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Address;

class AddressController extends ApiBaseController
{
    public function index()
    {
        $data = [];
        foreach ($this->user->addresses as $addr){
            $data[] = $addr->detail();
        }
        return $this->sendResponse($data);
    }
    
    public function create(Request $request)
    {
        \Log::debug($request->all());
        $input = $request->all();
        $input['user_id'] = $this->user->id;
        $input['default'] = 0;
        if ($addr = Address::create($input)) {
            $key = $this->user->id . "current-address";
            \Cache::put($key, $addr->id);
            
            return $this->sendResponse($addr->id);
        }
    }
    
    public function show($id, Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        // if ($addr = $this->user->addresses->find($id)) {
        if ($addr = Address::find($id)) {    
            return $this->sendResponse($addr->detail());
        }
        return $this->sendResponse([]);
    }
    
    public function default(Request $request)
    {
        \Log::debug($request->all());
        // $addr = Address::create($request->all());
        if ($addr = $this->user->addresses->where('default', 1)->first()) {
            return $this->sendResponse($addr->detail());
        }
        return $this->sendResponse([]);
    }
    
    public function select($store_id, $id) 
    {
        $key = $this->user->id . "current-address";
        \Cache::put($key, $id);
        
        return $this->sendResponse($id);
    }
    
    public function current($store_id) 
    {
        $key = $this->user->id . "current-address";
        if ($id = \Cache::get($key)) {
            $addr = Address::find($id);
            return $this->sendResponse($addr->detail());
        }
        
        return $this->sendError("没有选择地址");
    }
}