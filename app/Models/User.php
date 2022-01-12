<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia; 
use App\Helpers\UserHelper;
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use MediaTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'name', 
        'openid',
        'unionid',
        'nickname',
        'avatar',
        'gender',
        'mobile',
        'province',
        'city',
        'county',
        'qrcode',
        'id_no',
        'id_status',
        // 'superiors',
        'senior_id',
        'type',
        'email', 
        'password',
        // 'level', 
        // 'dd',
        // 'dds',  // number of dd 
        // 'ppv',  // personal point value 
        // 'gpv',  // all other sales' personal point value in your group;
        // 'tgpv', // gpv + ppv
        // 'pgpv', // tgpv (exclude qualified directors' tgpv)
        // 'agpv', // Accumulative Group Point Value  since your first month join
        // 'income_ratio',
        // 'retail_income',
        // 'level_bonus',
        // 'leader_bonus',
        // 'hlb', // has leader bonus
        // 'lbpv', // leader bonus point value
        // 'width_bonus',
        // 'depth_bonus',
        // 'total_income',
        // 'apply_status',
        // 'sharing',
        'api_token'
    ];

    public static $rules = [
        'name' => 'required|string',
        'gender' => 'integer',
        'mobile' => 'required',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'level' => 'integer',
        // 'sharing' => 'integer',
        'senior_id' => 'integer',
    ];
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            // self::beforesave($instance);
            if (!$instance->email) {
                $instance->email = $instance->mobile.'@huayan.com';
                $instance->password = \bcrypt($instance->email);
            }
        });
    }
    const APPLYING = 'applying';
    const GRANT = 'grant';
    const REJECT = 'reject';
    public static function applyOptions()
    {
        return [
            self::APPLYING => __(ucfirst(self::APPLYING)),
            self::GRANT => __(ucfirst(self::GRANT)),
            self::REJECT => __(ucfirst(self::REJECT)),
        ];
    }
    
    const MALE = 1;
    const FEMALE = 2;
    public static function genderOptions()
    {
        return [
            self::MALE => __('Male'),
            self::FEMALE => __('Female')
        ];
    }
    public function genderLabel()
    {
        return self::genderOptions()[$this->gender] ?? '-';
    }
    
    const CUSTOMER = 'customer';
    const SALESMAN = 'salesman';
    const MANAGER = 'manager';
    const CLERK = 'clerk';
    const EXPERT = 'expert';
    
    public static function typeOptions()
    {
        return [
            self::CUSTOMER => __(ucfirst(self::CUSTOMER)),
            self::SALESMAN => __(ucfirst(self::SALESMAN)),
            self::MANAGER => __(ucfirst(self::MANAGER)),
            self::CLERK => __(ucfirst(self::CLERK)),
            self::EXPERT => __(ucfirst(self::EXPERT)),
        ];
    }
    
    
    public function typeLabel()
    {
        return __(ucfirst($this->type));
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
    
    public function likes()
    {
        return $this->belongsToMany(Goods::class);
    }
    
    public function senior()
    {
        return $this->belongsTo(User::class, 'senior_id');
    }
    
    public function juniors()
    {
        return $this->hasMany(User::class, 'senior_id');
    }
    
    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
    
    public function isRoot()
    {
        return $this->id == 1;
    }
    
    public function members()
    {
        $members = $this->juniors;
        foreach ($this->juniors as $user) {
            $members = $members->concat($user->members());
        }
        return $members;
    }
    
    public function refreshToken()
    {
        $this->update(['api_token' => \Str::random(32)]);
    }
    
    public function info()
    {
        $attrs = [
            'id',
            'type',
            'name', 
            'openid',
            'unionid',
            'nickname',
            'avatar',
            'gender',
            'mobile',
            'province',
            'city',
            'county',
            'api_token'
        ];
        foreach ($attrs as $attr){
            $data[$attr] = $this->$attr;
        }
        $data['qrcode'] = !$this->qrcode ? null : url(\Storage::url($this->qrcode));
        return $data;
    }
    
    public function detail()
    {
        return $this->info();
    }
    
    // personal bussiness value
    public function revenue($year, $index)
    {
        return $this->revenues()->where('year', $year)->where('index', $index)->first();
    }
    
    public function getCart()
    {
        if (!$cart = $this->cart) {
            $cart = Cart::create([
                'store_id' => $this->store_id,
                'user_id' => $this->id,
                'total_quantity' => 0,
                'total_price' => 0,
            ]);
        }
        return $cart;
    }
}
