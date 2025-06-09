<?php

namespace App\Http\Controllers;

use App\Models\Session;

class SessionController extends Controller
{
    public function show($slug)
    {
        $session = Session::where('slug', $slug)->firstOrFail();
        $photos = $session->getMedia('photos');
        
        return view('session', compact('session', 'photos'));
    }
}