<?php

namespace App\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PhotoWatermarkService
{
    public function applyToThumb(Media $photo): void
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($photo->getPath());
        $image->resize(400, 300);

        for ($x = 30; $x <= 400; $x += 120) {
            for ($y = 40; $y <= 300; $y += 80) {
                $image->text('BuscaTusFotos.com', $x, $y, function ($font) {
                    $font->size(12);
                    $font->color('rgba(255, 255, 255, 0.7)');
                    $font->angle(0);
                });
            }
        }

        $image->save($photo->getPath('thumb'));
    }
}
