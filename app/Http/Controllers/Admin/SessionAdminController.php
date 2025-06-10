<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class SessionAdminController extends Controller
{
    public function destroy($id)
{
    try {
        $session = Session::findOrFail($id);
        
        // Eliminar todas las fotos asociadas
        $session->clearMediaCollection('photos');
        
        // Eliminar la sesión
        $sessionTitle = $session->title;
        $session->delete();
        
        return redirect()->route('admin.sessions')
            ->with('success', "Sesión '{$sessionTitle}' eliminada correctamente");
            
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar la sesión: ' . $e->getMessage());
    }
}
    public function create()
{
    return view('admin.session-create');
}

public function store(Request $request)
{
    try {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string|max:2000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1048576'
        ]);

        $slug = Str::slug($request->title . '-' . $request->date);
        
        // Asegurar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while (Session::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Crear la sesión
        $session = Session::create([
            'title' => $request->title,
            'slug' => $slug,
            'date' => $request->date,
            'description' => $request->description,
            'listed' => $request->has('listed')
        ]);

        // Subir fotos si existen
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $mediaItem = $session->addMedia($photo)
                    ->toMediaCollection('photos');
                
                // Generar marca de agua automáticamente
                $this->addWatermarkToPhoto($mediaItem);
            }
        }

        $photoCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
        $message = "Sesión creada correctamente";
        if ($photoCount > 0) {
            $message .= " con {$photoCount} foto" . ($photoCount > 1 ? 's' : '') . " (con marca de agua)";
        }

        return redirect()->route('admin.sessions')->with('success', $message);
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

private function addWatermarkToPhoto($photo)
{
    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
    $image = $manager->read($photo->getPath());
    
    // Redimensionar para thumbnail
    $image->resize(400, 300);
    
    // Añadir múltiples marcas de agua de texto
    $watermarkText = 'BuscaTusFotos.com';
    
    // Patrón repetido cada 120px horizontal y 80px vertical
    for ($x = 30; $x <= 400; $x += 120) {
        for ($y = 40; $y <= 300; $y += 80) {
            $image->text($watermarkText, $x, $y, function($font) {
                $font->size(12);
                $font->color('rgba(255, 255, 255, 0.7)');
                $font->angle(0);
            });
        }
    }
    
    // Guardar en conversión thumb
    $thumbPath = $photo->getPath('thumb');
    $image->save($thumbPath);
}
public function index()
    {
        $sessions = Session::orderBy('date', 'desc')->get();
        return view('admin.sessions', compact('sessions'));
    }

    public function photos($id)
    {
        $session = Session::findOrFail($id);
        $photos = $session->getMedia('photos');
        return view('admin.photos', compact('session', 'photos'));
    }

    public function uploadPhoto(Request $request, $id)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1048576' 
    ]);

    try {
        $session = Session::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            $session->addMediaFromRequest('photo')
                ->toMediaCollection('photos');
                
            return back()->with('success', 'Foto subida correctamente');
        }
        
        return back()->with('error', 'No se seleccionó ningún archivo');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al subir la foto: ' . $e->getMessage());
    }
}
}