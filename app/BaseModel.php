<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia; 
class BaseModel extends Model implements HasMedia
{
    use BMedia;
    public function info()
    {
        $info = $this->getOriginal();
        foreach (['created_at', 'updated_at', 'deleted_at'] as $key) {
            $info[$key] = $this->$key ? $this->$key->toDateTimeString() : null;
        }
        return $info;
    }
}