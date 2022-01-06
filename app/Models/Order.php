<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\ExpressHelper;

class Order extends BaseModel
{
    use SoftDeletes;
    use AddressTrait;
    
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
        'order_no',
        'amount',
        'province_id',
        'city_id',
        'district_id',
        'street',
        'contact',
        'telephone',
        'status',
        'logistic_id',
        'waybill_number',
        'ship_status'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'order_no' => 'string',
        'amount' => 'float',
        'telephone' => 'string',
        'contact' => 'string',
        'province_id' => 'integer',
        'city_id'  => 'integer',
        'district_id'=> 'integer',
        'street' => 'string',    
    ];
    
    public static $rules = [
        'contact' => 'required|string|max:12',
        'telephone' => 'required|string|max:16',
        'order_no' => 'required|string|max:24',
        'amount' => 'required|numeric|min:0.01'
    ];
    
    const CREATED = 'created';
    const PAID = 'paid';
    const SHIPPED = 'shipped';
    const COMPLETE = 'complete';
    const CANCELED = 'canceled';
    
    static public function statusOptions()
    {
        return [
            self::CREATED   => __(ucfirst(self::CREATED)),
            self::PAID      => __(ucfirst(self::PAID)),
            self::SHIPPED   => __(ucfirst(self::SHIPPED)),
            self::COMPLETE  => __(ucfirst(self::COMPLETE)),
            self::CANCELED  => __(ucfirst(self::CANCELED)),
        ];
    }
    
    public function statusLabel()
    {
        return self::statusOptions()[$this->status];
    }
    
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
    
    public function logistic()
    {
        return $this->belongsTo(Logistic::class);
    }
    
    public function logisticProgress()
    {
        return $this->hasOne(LogisticProgress::class);
    }
    
    public function deliver($logistic, $num)
    {
        $this->update([
            'status' => self::SHIPPED,
            'ship_status' => 1, // no record
            'logistic_id' => $logistic->id,
            'waybill_number' => $num
        ]);
        ExpressHelper::query($logistic->code, $num, $logistic->code == 'shunfeng' ? substr($this->telephone, -4) : null);
    }
    
    public function info()
    {
        $info = parent::info();
        $info['address'] = $this->display_address();
        return $info;    
    }
    
    public function detail()
    {
        $info = $this->info();
        $goods = [];
        foreach ($this->goods as $good) {
            $goods[] = $good->info();
        }
        $info['status_label'] = $this->statusLabel();
        $info['goods'] = $goods;
        $info['total_quantity'] = count($goods);
        if ($p = $this->logisticProgress) {
            $info['express'] = $p->data;
            $info['ship_status_label'] = $p->statusLabel();
        }
        
        return $info;
    }
}
