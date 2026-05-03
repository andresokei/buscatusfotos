<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZipArchive;

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

        $medias = Media::whereIn('id', $purchase->media_ids)->get()->values();

        if ($medias->isEmpty()) {
            abort(404, 'No se encontraron fotos para esta compra');
        }

        foreach ($medias as $media) {
            if (! is_file($media->getPath())) {
                abort(404, 'No se encontro una de las fotos de esta compra');
            }
        }

        if ($medias->count() === 1) {
            $media = $medias->first();

            $this->markAsDownloaded($purchase, $request);

            return response()->download($media->getPath(), $media->file_name, [
                'Content-Type' => $media->mime_type ?: 'application/octet-stream',
            ]);
        }

        $zipPath = $this->createZip($medias, $purchase);

        $this->markAsDownloaded($purchase, $request);

        return response()->download($zipPath, 'fotos_' . $purchase->id . '.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    private function markAsDownloaded(Purchase $purchase, Request $request): void
    {
        $purchase->increment('download_count');
        $purchase->forceFill([
            'last_downloaded_at' => now(),
            'last_download_ip' => $request->ip(),
        ])->save();
    }

    private function createZip($medias, Purchase $purchase): string
    {
        $zipDirectory = storage_path('app/downloads');

        if (! is_dir($zipDirectory)) {
            mkdir($zipDirectory, 0775, true);
        }

        $zipPath = $zipDirectory . DIRECTORY_SEPARATOR . 'fotos_' . $purchase->id . '_' . Str::random(12) . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'No se pudo crear el archivo ZIP');
        }

        $usedNames = [];

        foreach ($medias as $media) {
            $zip->addFile($media->getPath(), $this->uniqueArchiveName($media->file_name, $usedNames));
        }

        $zip->close();

        return $zipPath;
    }

    private function uniqueArchiveName(string $fileName, array &$usedNames): string
    {
        $baseName = basename($fileName);
        $name = $baseName;
        $counter = 2;

        while (isset($usedNames[$name])) {
            $extension = pathinfo($baseName, PATHINFO_EXTENSION);
            $stem = pathinfo($baseName, PATHINFO_FILENAME);
            $name = $extension
                ? "{$stem}_{$counter}.{$extension}"
                : "{$stem}_{$counter}";
            $counter++;
        }

        $usedNames[$name] = true;

        return $name;
    }
}
