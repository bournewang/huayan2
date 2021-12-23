<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use App\Helpers\SearchHelper;
use Illuminate\Support\Facades\App;
use Response;
use Request;
use Auth;
use DB;
use App\Exceptions\ApiException;

 /**
 * @OA\Info(
 *      version="1.0.0",
 *      title="智帘API文档",
 *      description="客户端和门店端API",
 *      @OA\Contact(
 *          email="xiaopei0206@icloud.com"
 *      ),
 * )
 */

 /**
  *  @OA\Server(
  *      url=L5_SWAGGER_CONST_HOST,
  *      description="L5 Swagger OpenApi dynamic host server"
  *  )
  *
  */

 /**
  * @OA\SecurityScheme(
  *     type="apiKey",
  *     in="header",
  *     securityScheme="api_key",
  *     name="Authorization"
  * )
  */

class AppBaseController extends Controller
{
    protected $shop;

    public function sendResponse($result, $message = 'success')
    {
        if (in_array(Request::instance()->getMethod(), ['POST', 'PATCH', 'DELETE'])) {
            DB::commit();
        }
        return Response::json(self::makeResponse($message, $result));
    }

    public function sendError($error, $data = [])
    {
        if (in_array(Request::instance()->getMethod(), ['POST', 'PATCH', 'DELETE'])) {
            DB::rollBack();
        }
        return Response::json(self::makeError($error, $data));
    }


    public static function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'code'    => 0,
            'data'    => $data,
            'msg' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, $data = [])
    {
        $res = [
            'success' => false,
            'code'    => 200,
            'data'    => $data,
            'msg' => $message,
        ];

        // if (!empty($data)) {
        //     $res['data'] = $data;
        // }

        return $res;
    }

    protected function user()
    {
        if ($user = Auth::guard('api')->user()) {
            return $user;
        }elseif ($user = Auth::user()){
            return $user;
        }else{
            throw new ApiException("invalid api_token", 999);
        }
    }

    protected function shop()
    {
        if ($user = $this->user()) {
            if ($user->shop){
                return $user->shop;
            }elseif ($user->client) {
                return $user->client->shop;
            }
        }
        return null;
    }
}
