<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Store;
use App\User;
use Log;

class WechatController extends AppBaseController
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎光临本店！！";
        });

        return $app->server->serve();
    }
    
    public function login($store_id, Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        if (!$code = $request->input('code')) {
            throw new ApiException("no code");
        }
        $mpp = \EasyWeChat::miniProgram();
        $data = $mpp->auth->session($code);
        \Log::debug($data);
        \Cache::put("wx.session.".$data['session_key'], $data['openid'], 60*5);
        if (isset($data['openid'])) {
            if ($user = User::where('openid', $data['openid'])->first()) {
                $user->refreshToken();
                \Auth::login($user);
                return $this->sendResponse($user->info());
            }
        }
        return $this->sendError('no user', [
            'session_key' => $data['session_key']
        ]);
    }
    
    public function register($store_id, Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        if (!$session_key = $request->input('session_key')) {
            // throw new ApiException("no code");
        }
        $mpp = \EasyWeChat::miniProgram();
        $iv = $request->get('iv');
        $encryptedData = $request->get('encryptedData');
        $data = $mpp->encryptor->decryptData($session_key, $iv, $encryptedData);
        \Log::debug("decrypt data: ");
        \Log::debug($data);    
        
        if ($openid = ($data['openId'] ?? null)) {
            if (!$user = User::where('openid', $openid)->first()) {
                \Log::debug("try to create user: ");
                $user = User::create([
                    'store_id'  => $store_id,
                    'openid'    => $openid, 
                    'email'     => $openid."@test.com",
                    'name'      => $data['nickName'] ?? null, 
                    'nickname'  => $data['nickName'] ?? null, 
                    'avatar'    => $data['avatarUrl'] ?? null, 
                    'province'  => $data['province'] ?? null, 
                    'city'      => $data['city'] ?? null, 
                    'password'  => bcrypt($openid)
                ]);
                $user->refreshToken();
                \Auth::login($user);
                \Log::debug("user: $user->id");
                return $this->sendResponse($user->info());
            }
        }
        return $this->sendError("==");
    }
    
    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
    }
}
