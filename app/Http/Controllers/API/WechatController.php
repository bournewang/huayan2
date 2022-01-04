<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use Log;

class WechatController extends ApiBaseController
{
    /**
     * Login api
     *
     * @OA\Post(
     *  path="/api/wxapp/login",
     *  tags={"Auth"},
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="code",description="code",type="string")
     *           )
     *       )
     *   ),  
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function login(Request $request)
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
    
    /**
     * Register api
     *
     * @OA\Post(
     *  path="/api/wxapp/register",
     *  tags={"Auth"},
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="session_key",description="session key from login api response",type="string"),
     *               @OA\Property(property="iv",description="iv from wx.login",type="string"),
     *               @OA\Property(property="encryptedData",description="encryptedData from wx.login",type="string"),
     *               @OA\Property(property="store_id",description="store id from init",type="integer"),
     *               @OA\Property(property="referer_id",description="referer id from init",type="integer"),
     *           )
     *       )
     *   ),  
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function register(Request $request)
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
                    'store_id'  => $request->input('store_id', null),
                    'senior_id' => $request->input('referer_id', null),
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
        return $this->sendError("no openId in decrypt data");
    }
    
    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
    }
}
