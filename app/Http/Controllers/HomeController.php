<?php

namespace App\Http\Controllers;

use App\Models\Session;

class HomeController extends Controller
{
    public function index()
    {
        $sessions = Session::where('listed', true)
            ->orderBy('date', 'desc')
            ->get();
            
        return view('home', compact('sessions'));
    }
}