<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Helpers\ValidatorHelper;

class StoreController extends ApiBaseController
{    
    /**
     * create an store 业务员开发/创建新门店
     *
     * @OA\Post(
     *   path="/api/stores",
     *   tags={"Store"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="name", type="string", description="store name"),
     *               @OA\Property(property="company_name", type="string", description="company name"),
     *               @OA\Property(property="license_no", type="string", description="license_no"),
     *               @OA\Property(property="account_no", type="string", description="account_no"),
     *               @OA\Property(property="contact", type="string", description="contact person"),
     *               @OA\Property(property="mobile", type="string", description="mobile"),
     *               @OA\Property(property="province_id", type="string", description="province_id"),
     *               @OA\Property(property="city_id", type="string", description="city_id"),
     *               @OA\Property(property="street", type="string", description="street"),
     *               @OA\Property(property="contract[]", type="array", description="contract img urls", collectionFormat="multi", @OA\Items(type="string")),
     *               @OA\Property(property="id_card[]", type="array", description="manager id_card img urls", collectionFormat="multi", @OA\Items(type="string")),
     *               @OA\Property(property="photo[]", type="array", description="photo img urls", collectionFormat="multi", @OA\Items(type="string")),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
     public function create(Request $request)
     {
         $validated = $request->validate(Store::$rules);
         $data = $request->all();
         $data['salesman_id'] = $this->user->id;
         if (!$phone = ($data['mobile']??null)) {
            return $this->sendError("请填写店长手机") ;
         }
         if (!$manager = User::where('mobile', $phone)->first()) {
             return $this->sendError("该用户没有注册 $phone");
         }
         $data['manager_id'] = $manager->id;
         $data['status'] = (new Store)->pending;
         if ($error = ValidatorHelper::validate(Store::$rules, $data)) {
             return $this->sendError($error);
         }
         $store = Store::create($data);
         foreach (['contract', 'license', 'photo'] as $collect) {
             if (!$array = $request->input($collect)) continue;
             foreach ($array as $img){
                 $path = public_path(str_replace(config('app.url'), '', $img));
                 $store->addMedia($path)->toMediaCollection($collect);
             }
         }
         if ($array = $request->input('id_card')) {
             foreach ($array as $img){
                 $path = public_path(str_replace(config('app.url'), '', $img));
                 $manager->addMedia($path)->toMediaCollection('id_card');
             }
         }
         return $this->sendResponse(['store_id' => $store]);
     }
     
     /**
      * Store list api 获取该业务员开发/创建的门店
      *
      * @OA\Get(
      *  path="/api/stores",
      *  tags={"Store"},
      *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
      *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
      *  @OA\Parameter(name="k",             in="query",required=false,explode=true,@OA\Schema(type="string"), description="key words"),
      *  @OA\Response(response=200,description="successful operation")
      * )
      */  
      public function index(Request $request)
      {
          $stores = Store::where('id', '>', 0);
          if ($key = $request->input('k')) {
              $stores->where('name', 'like', "%$key%");
          }
          if ($s = $request->input('status')) {
              $stores->where('status', $s);
          }
          
          $total = $stores->count();
          $perpage = $request->input('perpage', 20);
          $data = [
              'total' => $total,
              'pages' => ceil($total/$perpage),
              'page' => $request->input('page', 1),
              'items' => []
          ];
          $stores = $stores->paginate($perpage);
          foreach ($stores as $store) {
              $data['items'][] = $store->detail();
          }
          return $this->sendResponse($data);
      }
}