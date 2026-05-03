<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Services\PhotoWatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SessionAdminController extends Controller
{
    public function index()
    {
        $sessions = Session::orderBy('date', 'desc')->get();

        return view('admin.sessions', compact('sessions'));
    }

    public function create()
    {
        return view('admin.session-create');
    }

    public function store(Request $request, PhotoWatermarkService $watermark)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'description' => 'nullable|string|max:2000',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1048576',
            ]);

            $slug = $this->uniqueSlug($request->title, $request->date);

            $session = Session::create([
                'title' => $request->title,
                'slug' => $slug,
                'date' => $request->date,
                'description' => $request->description,
                'listed' => $request->has('listed'),
            ]);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $mediaItem = $session->addMedia($photo)->toMediaCollection('photos');
                    $watermark->applyToThumb($mediaItem);
                }
            }

            $photoCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
            $message = 'Sesion creada correctamente';

            if ($photoCount > 0) {
                $message .= " con {$photoCount} foto" . ($photoCount > 1 ? 's' : '') . ' con marca de agua';
            }

            return redirect()->route('admin.sessions')->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function photos($id)
    {
        $session = Session::findOrFail($id);
        $photos = $session->getMedia('photos');

        return view('admin.photos', compact('session', 'photos'));
    }

    public function uploadPhoto(Request $request, PhotoWatermarkService $watermark, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1048576',
        ]);

        try {
            $session = Session::findOrFail($id);

            if ($request->hasFile('photo')) {
                $mediaItem = $session->addMediaFromRequest('photo')->toMediaCollection('photos');
                $watermark->applyToThumb($mediaItem);

                return back()->with('success', 'Foto subida correctamente');
            }

            return back()->with('error', 'No se selecciono ningun archivo');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir la foto: ' . $e->getMessage());
        }
    }

    public function deletePhoto($photoId)
    {
        try {
            $photo = Media::where('collection_name', 'photos')
                ->where('model_type', Session::class)
                ->findOrFail($photoId);

            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto eliminada correctamente',
            ]);
        } catch (\Exception $e) {
            Log::error('Error eliminando foto: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la foto',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $session = Session::findOrFail($id);
            $sessionTitle = $session->title;

            $session->clearMediaCollection('photos');
            $session->delete();

            return redirect()->route('admin.sessions')
                ->with('success', "Sesion '{$sessionTitle}' eliminada correctamente");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la sesion: ' . $e->getMessage());
        }
    }

    private function uniqueSlug(string $title, string $date): string
    {
        $slug = Str::slug($title . '-' . $date);
        $originalSlug = $slug;
        $counter = 1;

        while (Session::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
