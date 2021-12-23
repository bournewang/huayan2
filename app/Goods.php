<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends BaseModel
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    
    public $table = 'goods';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopId',
        'id',
        'name',
        'qty', 
        'category_id',
        'type',
        'brand',
        'saleFlag',
        'price',
        'img',
        'img_s',
        'img_m',
        'pv',
        'saleCount',
        'customs_id',
        'detail',
        'commission'
    ];
    
    protected $casts = [
        'shopId' => 'string',
        // 'id' => 'string',
        'name' => 'string',
        'qty' => 'string', 
        'category_id' => 'string',
        'type' => 'string',
        'brand' => 'string',
        'saleFlag' => 'string',
        'price' => 'string',
        'img' => 'string',
        'img_s' => 'string',
        'img_m' => 'string',
        'pv' => 'integer',
        'saleCount' => 'integer',
        'customs_id' => 'string',
        'detail' => 'string',
        'commission' => 'integer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'shopId',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function stores()
    {
        return $this->belongsToMany(Store::class)->withPivot('recommend', 'hot');
    }
    
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity', 'price', 'subtotal');
    }
    
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price', 'subtotal');
    }
    
    public function info()
    {
        $data = parent::info();
        foreach (['img', 'img_s', 'img_m'] as $key) {
            $data[$key] = $this->imgUrl($key);
        }
        return $data;
    }
    
    public function imgUrl($key = 'img')
    {
        return !$this->$key ? null : url(\Storage::url($this->$key));
    }
    
    public function commission()
    {
        return $this->commission ?? $this->category->commission;
    }
    
    public function show()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "views" => $this->pv,
            // "detail" => $this->detail,
            "afterSale" => "0,1,2",
            "barCode" => "",
            "categoryId" => $this->category_id,
            "characteristic" => $this->name,
            "commission" => $this->commission,
            "commissionSettleType" => 0,
            "commissionType" => 0,
            "commissionUserType" => 0,
            "dateAdd" => "2017-10-30 10:51:08",
            "dateEndPingtuan" => "2019-12-31 00:00:00",
            "dateStart" => "2017-10-30 10:44:34",
            "dateUpdate" => "2021-07-13 16:10:54",
            "fxType" => 2,
            "gotScore" => 0,
            "gotScoreType" => 0,
            "hasAddition" => false,
            "hasTourJourney" => false,
            "hidden" => 0,
            "kanjia" => true,
            "kanjiaPrice" => 82,
            "limitation" => false,
            "logisticsId" => 386,
            "maxCoupons" => 1,
            "miaosha" => false,
            "minBuyNumber" => 1,
            "minPrice" => $this->price,
            "minScore" => 0,
            "numberFav" => 2,
            "numberGoodReputation" => 2,
            "numberOrders" => 4,
            "numberSells" => 3,
            "originalPrice" => round(intval($this->price * 1.5 / 10)*10, 2),
            "overseas" => false,
            "paixu" => 0,
            "pic" => $this->imgUrl(),// "https://cdn.it120.cc/apifactory/2019/07/11/3bfecdf0-16c5-4710-a8e7-dd6880543ece.gif",
            "pingtuan" => false,
            "pingtuanPrice" => 0,
            "purchaseNotes" => "",
            "recommendStatus" => 0,
            "recommendStatusStr" => "普通",
            "seckillBuyNumber" => 0,
            "sellEnd" => false,
            "sellStart" => true,
            "shopId" => 6041,
            "status" => 0,
            "statusStr" => "上架",
            "storeAlert" => false,
            "storeAlertNum" => 10,
            "stores" => 699997,
            "stores0Unsale" => false,
            "type" => 0,
            "unit" => "份",
            "unusefulNumber" => 0,
            "usefulNumber" => 0,
            "userId" => 951,
            "vetStatus" => 1,
            "videoId" => "c4c6e38eeb3a428e80f1a8b32c6de587",
            "weight" => 0
            ];      
    }
    
    public function detail()
    {
        return [
            "pics2" =>  [
              "https://dcdn.it120.cc/2019/12/06/ebf49ac6-4521-4bcc-92fd-8bbbd4131167.jpg"
            ],
            "skuList" =>  [
            ],
            "subPics" =>  [],
            "logistics" =>  [
              "isFree" =>  false,
              "feeType" =>  0,
              "feeTypeStr" =>  "按件数",
              "details" =>  [
                [
                  "addAmount" =>  9,
                  "addNumber" =>  1,
                  "firstAmount" =>  8,
                  "firstNumber" =>  1,
                  "type" =>  0,
                  "userId" =>  951
                ]
              ]
            ],
            "extJson" =>  [],
            "category" =>  $this->category ? $this->category->show() : [],
            "pics" =>  [
              [
                "goodsId" =>  235853,
                "id" =>  3734152,
                // "pic" =>  "https://dcdn.it120.cc/2019/12/06/ebf49ac6-4521-4bcc-92fd-8bbbd4131167.jpg",
                "pic" => $this->imgUrl(),
                "userId" =>  951
              ]
            ],
            "content" =>  $this->detail,
            "properties1" =>  [
              [
                "childsCurGoods" =>  [
                  [
                    "dateAdd" =>  "2017-09-16 08:22:42",
                    "id" =>  1699,
                    "name" =>  "淡灰小船",
                    "paixu" =>  0,
                    "propertyId" =>  869,
                    "remark" =>  "",
                    "userId" =>  951
                  ]
                ],
                "dateAdd" =>  "2017-09-12 20:58:56",
                "id" =>  869,
                "name" =>  "花色",
                "paixu" =>  0,
                "userId" =>  951
              ],
              [
                "childsCurGoods" =>  [
                  [
                    "dateAdd" =>  "2017-09-12 21:03:49",
                    "id" =>  1588,
                    "name" =>  "红色",
                    "paixu" =>  0,
                    "propertyId" =>  871,
                    "remark" =>  "",
                    "userId" =>  951
                  ],
                  [
                    "dateAdd" =>  "2017-09-12 21:04:01",
                    "id" =>  1590,
                    "name" =>  "蓝色",
                    "paixu" =>  0,
                    "propertyId" =>  871,
                    "remark" =>  "",
                    "userId" =>  951
                  ]
                ],
                "dateAdd" =>  "2017-09-12 21:03:40",
                "id" =>  871,
                "name" =>  "颜色",
                "paixu" =>  0,
                "userId" =>  951
              ]
            ],
            "basicInfo" =>  $this->show()
        ];
    }
}
