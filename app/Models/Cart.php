<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DB;

class Cart extends BaseModel
{
    //
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public $table = 'carts';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'store_id',
        'user_id',
        'total_quantity',
        'total_price',
    ];    
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'total_quantity' => 'float',
        'total_price' => 'float',
    ];
    
    public static $rules = [
        'user_id' => 'integer|required',
        'total_quantity' => 'float|nullable',
        'total_price' => 'float|nullable',
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
    
    public function add($goods, $quantity = 1)
    {
        if ($exists = $this->goods()->find($goods->id)) {
            $quantity += $exists->pivot->quantity;
            $pivot = [
                'price' => $goods->price,
                'quantity' => $quantity,
                'subtotal' => $quantity * $goods->price
            ];
            $this->goods()->updateExistingPivot($goods, $pivot);
        }else{
            $pivot = [
                'price' => $goods->price,
                'quantity' => $quantity,
                'subtotal' => $quantity * $goods->price
            ];
            $this->goods()->attach($goods, $pivot);
        }
        $this->updated_at = Carbon::now(); // trigger observer
        $this->save(); 
    }
    
    public function change($goods, $quantity)
    {
        if ($exists = $this->goods()->find($goods->id)) {
            if ($quantity == 0) {
                $this->goods()->detach($goods);
            } else {
                $pivot = [
                    'price' => $goods->price,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $goods->price
                ];
                $this->goods()->updateExistingPivot($goods, $pivot);
            }
        }
        $this->updated_at = Carbon::now(); // trigger observer
        $this->save(); 
    }
    
    public function remove($goods)
    {
        if ($goods = $this->goods()->find($goods->id)) {
            $this->goods()->detach($goods);
            $this->updated_at = Carbon::now(); // trigger observer
            $this->save();
        }
    }
    
    public function clear()
    {
        $this->goods()->sync([]);
        $this->updated_at = Carbon::now();
        $this->save();
    }

    public function submit(Address $address=null, $date = null)
    {
        DB::beginTransaction();
        
        $order = Order::create([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'orderNo' => rand(100000,999999),
            'payNo' => null,
            'orderAmount' => $this->total_price,
            'orderTime' => null,
            'payTime' => null,
            'buyerRegNo' => $this->user->id,
            'buyerName' => $this->user->name,
            'buyerTelephone' => $this->user->telephone,
            'buyerIdNumber' => $this->user->id_no,
            'created_at' => $date,
            'consignee' => $address->consignee ?? null,
            'consigneeTelephone' => $address->telephone ?? null,
            'consigneeAddress' => $address->street ?? null,
            'receiverProvince' => $address->province->name ?? null,
            'receiverCity' => $address->city->name ?? null,
            'receiverCounty' => $address->district->name ?? null,
            // 'payRequest' => null,
            // 'payResponse',
            // 'orderInfoList',
        ]);
        
        // if ($order) {
        foreach ($this->goods as $good) {
            $subtotal = $good->pivot->subtotal;
            $order->goods()->attach($good->id, [
                'price' => $good->pivot->price,
                'quantity' => $good->pivot->quantity,
                'subtotal' => $subtotal,
            ]);
        }
        $order->save();
        $order->refresh();
        $this->clear();
        DB::commit();
        
        return $order;
    }
    
    public function detail()
    {
        $data = [];
        foreach ($this->goods as $good) {
            $data[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'img' => $good->imgUrl(),
                'price' => $good->pivot->price,
                'quantity' => $good->pivot->quantity
            ];
        }
        $info = parent::info();
        $info['items'] = $data;
        $info['total_price'] = round($this->total_price, 2);
        return $info;
    }
}
