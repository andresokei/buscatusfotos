<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function thumb(Media $media): BinaryFileResponse
    {
        $this->ensureIsSessionPhoto($media);

        if (! $this->canViewThumb($media)) {
            abort(404);
        }

        $path = $media->getPath('thumb');

        if (! is_file($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $media->mime_type ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function original(Request $request, Media $media): BinaryFileResponse
    {
        $this->ensureIsSessionPhoto($media);

        if (! $request->session()->get('admin_logged_in')) {
            abort(404);
        }

        $path = $media->getPath();

        if (! is_file($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $media->mime_type ?: 'application/octet-stream',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function ensureIsSessionPhoto(Media $media): void
    {
        if ($media->collection_name !== 'photos' || $media->model_type !== Session::class) {
            abort(404);
        }
    }

    private function canViewThumb(Media $media): bool
    {
        if (request()->session()->get('admin_logged_in')) {
            return true;
        }

        $session = $media->model;

        return $session !== null && (bool) $session->listed === true;
    }
}
