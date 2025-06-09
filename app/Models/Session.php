<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Session extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'title', 'slug', 'date', 'description', 'listed'
    ];

    protected $casts = [
        'date' => 'date',
        'listed' => 'boolean',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('photos');
            
        $this->addMediaConversion('watermarked')
            ->width(800)
            ->height(600)
            ->performOnCollections('photos')
            ->nonQueued();
    }
}