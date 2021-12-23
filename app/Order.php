<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    
    public $table = 'orders';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'user_id',
        'orderNo',
        'payNo',
        'orderAmount',
        'orderTime',
        'payTime',
        'buyerRegNo',
        'buyerName',
        'buyerTelephone',
        'buyerIdNumber',
        'consignee',
        'consigneeTelephone',
        'consigneeAddress',
        'receiverProvince',
        'receiverCity',
        'receiverCounty',
        'payRequest',
        'payResponse',
        'orderInfoList',
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'orderNo' => 'string',
        'payNo' => 'string',
        'orderAmount' => 'float',
        'orderTime' => 'datetime',
        'payTime' => 'datetime',
        'buyerRegNo' => 'string',
        'buyerName' => 'string',
        'buyerTelephone' => 'string',
        'buyerIdNumber' => 'string',
        'consignee' => 'string',
        'consigneeTelephone' => 'string',
        'consigneeAddress' => 'string',
        'receiverProvince' => 'string',
        'receiverCity' => 'string',
        'receiverCounty' => 'string',
        'payRequest' => 'string',
        'payResponse' => 'string',
        'orderInfoList' => 'string',        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'shopId',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function goods()
    {
        return $this->belongsToMany(Goods::class)->withPivot('quantity', 'price', 'subtotal');
    }
    
    public function info()
    {
        $info = parent::info();
        $info['address'] = implode("", 
            array_filter([$this->receiverProvince, 
                $this->receiverCity, 
                $this->receiverCounty, 
                $this->consigneeAddress, 
                // $this->consignee, 
                // $this->consigneeTelephone
            ]));
        return $info;    
    }
    
    public function detail()
    {
        $info = $this->info();
        $goods = [];
        foreach ($this->goods as $good) {
            $goods[] = $good->info();
        }
        $info['goods'] = $goods;
        $info['total_quantity'] = count($goods);
        
        return $info;
    }
}
