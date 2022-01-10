<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia; 
class BaseModel extends Model implements HasMedia
{
    use MediaTrait;
    public function info()
    {
        if ($this->info_fields) {
            $info = [];
            foreach ($attrs as $attr){
                $info[$attr] = $this->$attr;
            }
        } else {
            $info = $this->getOriginal();
        }
        foreach (['created_at', 'updated_at', 'deleted_at'] as $key) {
            $info[$key] = $this->$key ? $this->$key->toDateTimeString() : null;
        }
        return $info;
    }
}