<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddWatermarkCommand extends Command
{
    protected $signature = 'photos:watermark';
    protected $description = 'Add watermark to photo thumbnails';

    public function handle()
    {
        $photos = Media::where('collection_name', 'photos')->get();
        
        foreach ($photos as $photo) {
            $this->addWatermarkToPhoto($photo);
            $this->info("Watermark added to: " . $photo->name);
        }
        
        $this->info("Watermarks added to all photos!");
    }
    
private function addWatermarkToPhoto($photo)
{
    $manager = new ImageManager(new Driver());
    $image = $manager->read($photo->getPath());
    
    // Redimensionar para thumbnail
    $image->resize(400, 300);
    
    // Añadir múltiples marcas de agua de texto
    $watermarkText = 'BuscaTusFotos.com';
    
    // Crear patrón repetido cada 120px horizontal y 80px vertical
    for ($x = 30; $x <= 400; $x += 120) {
        for ($y = 40; $y <= 300; $y += 80) {
            $image->text($watermarkText, $x, $y, function($font) {
                $font->size(14);
                $font->color('#ffffff');
                $font->angle(0);
            });
        }
    }
    
    // Guardar en conversión thumb
    $thumbPath = $photo->getPath('thumb');
    $image->save($thumbPath);
}
}