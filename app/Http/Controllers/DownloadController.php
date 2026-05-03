<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZipStream\ZipStream;

class DownloadController extends Controller
{
    public function show($token)
    {
        $purchase = Purchase::where('download_token', $token)->first();

        if (!$purchase || !$purchase->canDownload()) {
            abort(404, 'Enlace de descarga no valido o expirado');
        }

        return view('download', compact('purchase'));
    }

    public function download(Request $request, $token)
    {
        $purchase = Purchase::where('download_token', $token)->first();

        if (!$purchase || !$purchase->canDownload()) {
            abort(404, 'Enlace de descarga no valido');
        }

        $medias = Media::whereIn('id', $purchase->media_ids)->get();

        if ($medias->isEmpty()) {
            abort(404, 'No se encontraron fotos para esta compra');
        }

        $purchase->increment('download_count');
        $purchase->forceFill([
            'last_downloaded_at' => now(),
            'last_download_ip' => $request->ip(),
        ])->save();

        if ($medias->count() === 1) {
            $media = $medias->first();

            return response()->download($media->getPath(), $media->file_name);
        }

        return $this->downloadZip($medias, $purchase);
    }

    private function downloadZip($medias, Purchase $purchase)
    {
        $zipName = 'fotos_' . $purchase->id . '.zip';

        return response()->streamDownload(function () use ($medias) {
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
}
