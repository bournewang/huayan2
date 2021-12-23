<?php

namespace App;
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
    use BMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'name', 
        'openid',
        'nickname',
        'avatar',
        'gender',
        'mobile',
        'province',
        'city',
        'county',
        'qrcode',
        'id_no',
        'id_img1',
        'id_img2',
        // 'superiors',
        'senior_id',
        'email', 
        'password',
        'level', 
        'dd',
        'dds',  // number of dd 
        'ppv',  // personal point value 
        'gpv',  // all other sales' personal point value in your group;
        'tgpv', // gpv + ppv
        'pgpv', // tgpv (exclude qualified directors' tgpv)
        'agpv', // Accumulative Group Point Value  since your first month join
        'income_ratio',
        'retail_income',
        'level_bonus',
        'leader_bonus',
        'hlb', // has leader bonus
        'lbpv', // leader bonus point value
        // 'tlbpv', // total leader bonus point value
        'width_bonus',
        'depth_bonus',
        'total_income',
        'apply_status',
        // 'sharing',
        'api_token'
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
    
    public function orgData()
    {
        $data = [
            'name' => $this->name . "/".__('TGPV').":".money($this->tgpv), 
            'sales' => implode("<br/>", [
                __('PGPV').":".money($this->pgpv),
                __('Personal Sale').": ".money($this->ppv),
                __('Retail Income').": ".money($this->retail_income),
                __('Level Bonus').": ". money($this->level_bonus) . "/".$this->income_ratio."%",
                __('Leader Bonus').": ".money($this->leader_bonus),
            ]),
            // 'ppv' => $this->ppv,
            // 'pgpv' => $this->pgpv
        ];
        $children = [];
        foreach ($this->juniors as $user) {
            $children[] = $user->orgData();
        }
        if (!empty($children))
        $data['children'] = $children;
        return $data;
    }

    public function dds()
    {
        return $this->juniors->where('dd', 1);
    }
    
    public function nondds()
    {
        return $this->juniors->where('dd', 0);
    }
    
    public function grant()
    {
        return $this->update(['apply_status' => self::GRANT]);
    }
    
    public function reject()
    {
        return $this->update(['apply_status' => self::REJECT]);
    }
    
    public function refreshToken()
    {
        $this->update(['api_token' => \Str::random(32)]);
    }
    
    public function info()
    {
        if (!$this->qrcode) {
            if ($url = UserHelper::qrcode($this)) {
                $this->update(['qrcode' => $url]);
            }
        }
        $data = $this->getOriginal();
        $data['qrcode'] = !$this->qrcode ? null : url(\Storage::url($this->qrcode));
        return $data;
    }
    
    // personal bussiness value
    public function revenue($year, $index)
    {
        return $this->revenues()->where('year', $year)->where('index', $index)->first();
    }
    
    public function initSales()
    {
        $data = [
            'ppv' => 0, 
            'gpv' => 0,
            'tgpv' => 0,
            'pgpv' => 0,
            'hlb' => 0,
            'lbpv' => 0,
            'tlbpv' => 0,
            'dds' => 0,
            'retail_income' => 0,
            'level_bonus'   => 0,
            'leader_bonus'  => 0,
            'total_income'  => 0,
            'clearing_status' => 0
        ];
        $this->update($data);
    }
    
    public function tgpv()
    {
        $gpv = 0;
        $gpv0 = 0; // exclude dd
        foreach ($this->juniors as $user) {
            $v = $user->tgpv();
            $gpv += $v;
            if (!$user->dd) { // not dd
                $gpv0 += $v;   
            }
        }
        $tgpv = $gpv + $this->ppv;
        $pgpv = $gpv0 + $this->ppv;
        $ratio = $this->store->ratio($tgpv);
        $min_dd = $this->store->minDD();
        $this->update(['gpv' => $gpv, 'tgpv' => $tgpv, 'pgpv' => $pgpv, 'income_ratio' => $ratio, 'dd' => ($tgpv >= $min_dd)]);
        return $tgpv;
    }
        
    public function make_dds()
    {
        $dds = 0;
        foreach ($this->juniors as $user) {
            if ($user->dd) {
                $dds++;
            }
            $user->make_dds();
        }
        $this->update([
            'dds' => $dds,
            'hlb' => $dds > 0 && ($this->pgpv >= $this->store->leader_bonus['pgpv'])
        ]);
    }
    
    public function make_leader_base()
    {
        $base = 0;
        if ($this->hlb) {
            $base = $this->dds()->sum('tgpv');
        }elseif ($this->juniors->count() < 1){
        }else {
            foreach ($this->juniors as $user) {
                // if ($this->hlb && $user->dd) {
                $base += $user->make_leader_base();
                // }
            }
        }
        $this->update(['lbpv' => $base]);
        return $base;
    }
    
    public function canHasLeaderBonus()
    {
        return $this->pgpv >= $this->store->leader_bonus['pgpv'] && $this->dds()->first();
    }
    
    public function income()
    {
        // 1, retail profit
        $retail_income = $this->ppv *$this->income_ratio / 100;
        
        // 2, level bonus
        $level_bonus = 0;
        foreach ($this->nondds() as $user) {
            $user_ratio = $this->store->ratio($user->tgpv);
            if ($this->income_ratio > $user_ratio) {
                $level_bonus += $user->tgpv * ($this->income_ratio - $user_ratio) / 100;
            }
        }
        
        // 3, leader bonus
        $leader_bonus = 0;
        $rate = $this->store->leader_bonus['bonus'] / 100;
        $min_dd = $this->store->minDD();
        \Log::debug(__CLASS__.'->'.__FUNCTION__." $this->id $this->name leader_bonus rate: $rate");
        // \Log::debug("min dd: $min_dd");
        if ($this->pgpv >= $this->store->leader_bonus['pgpv'] && $this->dds()->first()) {
            foreach ($this->dds() as $user) {
                \Log::debug("    check junior $user->id $user->name pgpv: $user->pgpv, leader_bonus+=".(max($user->pgpv, $min_dd) * $rate));
                // if ($user->tgpv < $min_dd) continue;
                // max($user->tgpv - $user->tlbpv, $min_dd)
                // FIXME
                $leader_bonus += max($user->pgpv - $user->lbpv, $min_dd) * $rate;
            }
            // make sure my leader has a minimum leader bonus whlie my group pv (except dds) can't reach minimum dd
            if ($this->senior && $this->pgpv < $min_dd) {
                \Log::debug("    senior->canHasLeaderBonus && this->pgpv < min_dd, leader_bonus -= ".(($min_dd - $this->pgpv) * $rate));
                $leader_bonus -= ($min_dd - $this->pgpv) * $rate;
            }
            \Log::debug("    leader_bonus = $leader_bonus");
        } else {
            \Log::debug("  not elgiable");
        }
        
        $this->update([
            'retail_income' => $retail_income, 
            'level_bonus' => $level_bonus, 
            'leader_bonus' => $leader_bonus,
            'total_income' => ($retail_income + $level_bonus + $leader_bonus)
        ]);
        // return $income;
    }
}
