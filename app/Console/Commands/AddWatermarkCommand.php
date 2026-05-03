<?php

namespace App\Console\Commands;

use App\Services\PhotoWatermarkService;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddWatermarkCommand extends Command
{
    protected $signature = 'photos:watermark';
    protected $description = 'Add watermark to photo thumbnails';

    public function handle(PhotoWatermarkService $watermark)
    {
        $photos = Media::where('collection_name', 'photos')->get();

        foreach ($photos as $photo) {
            $watermark->applyToThumb($photo);
            $this->info('Watermark added to: ' . $photo->name);
        }

        $this->info('Watermarks added to all photos!');
    }
}
