<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZipStream\ZipStream;


class DownloadController extends Controller
{

    public function download($token)
{
    $purchase = Purchase::where('download_token', $token)
        ->where('payment_status', 'paid')
        ->where('expires_at', '>', now())
        ->first();

    if (!$purchase) {
        abort(404, 'Enlace de descarga no válido');
    }

    $medias = Media::whereIn('id', $purchase->media_ids)->get();
    
    if ($medias->count() === 1) {
        // Descarga directa para 1 foto
        $media = $medias->first();
        return response()->download($media->getPath(), $media->file_name);
    }
    
    // ZIP para múltiples fotos
    return $this->downloadZip($medias, $purchase);
}

private function downloadZip($medias, $purchase)
{
    $zipName = 'fotos_' . $purchase->id . '.zip';
    
    return response()->streamDownload(function() use ($medias) {
        $zip = new ZipStream(
            outputStream: fopen('php://output', 'w'),
            sendHttpHeaders: false
        );
        
        foreach ($medias as $media) {
            $zip->addFileFromPath($media->file_name, $media->getPath());
        }
        
        $zip->finish();
    }, $zipName);
}


    public function show($token)
    {
        $purchase = Purchase::where('download_token', $token)
            ->where('payment_status', 'paid')
            ->where('expires_at', '>', now())
            ->first();

        if (!$purchase) {
            abort(404, 'Enlace de descarga no válido o expirado');
        }

        return view('download', compact('purchase'));
    }
}