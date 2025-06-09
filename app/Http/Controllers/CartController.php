<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function view()
{
    $cart = session('cart', []);
    $price = 0;
    $photos = collect();
    
    if (count($cart) > 0) {
        $price = \App\Services\PriceCalculator::calculate(count($cart));
        // Obtener las fotos del carrito
        $photos = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereIn('id', $cart)->get();
    }
    
    return view('cart', compact('cart', 'price', 'photos'));
}

    public function update(Request $request)
{
    $request->validate([
        'photo_id' => 'required|integer|exists:media,id',
        'action' => 'required|in:add,remove'
    ]);

    $cart = session('cart', []);
    $photoId = $request->photo_id;

    if ($request->action === 'add') {
        if (!in_array($photoId, $cart)) {
            $cart[] = $photoId;
        }
    } elseif ($request->action === 'remove') {
        $cart = array_diff($cart, [$photoId]);
    }

    session(['cart' => array_values(array_unique($cart))]);
    
    return response()->json([
        'success' => true,
        'cart_count' => count(session('cart', [])),
        'message' => $request->action === 'add' ? 'Foto añadida' : 'Foto eliminada'
    ]);
}
}