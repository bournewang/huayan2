<?php
namespace App\Models;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia; 
use Spatie\MediaLibrary\InteractsWithMedia;

trait BMedia  
{
    // use HasMedia;
    use InteractsWithMedia;
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->width(130)
            ->height(130);
            
        $this->addMediaConversion('medium')
            ->setManipulations(['w' => 1000, 'h' => 1000]);    
            
        $this->addMediaConversion('large')
            ->setManipulations(['w' => 2000, 'h' => 2000]);
    }

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('contract');
        $this->addMediaCollection('photo');
        $this->addMediaCollection('id');
        $this->addMediaCollection('license');
    }  
}