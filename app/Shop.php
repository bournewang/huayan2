<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends BaseModel
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    
    public $table = 'shops';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'telephone',
        'province_id',
        'city_id',
        'district_id',
        'street',
        // 'contract',
        // 'photos',
    ];
    
    protected $casts = [
        'name' => 'string',
        'telephone' => 'string',
        'province_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        'street' => 'string',
        // 'contract' => 'string',
        // 'photos' => 'string',
    ];

    protected $hidden = [
    ];
    
    public function province(){return $this->belongsTo(Province::class);}
    public function city(){return $this->belongsTo(City::class);}
    public function district(){return $this->belongsTo(District::class);}

    public function display_address($with_telephone = false)
    {
        return implode(array_filter([
            $this->province->name ?? null,
            $this->city->name ?? null,
            $this->district->name ?? null,
            $this->street,
            $with_telephone ? ' '.$this->telephone : '',
        ]));
    }
}


