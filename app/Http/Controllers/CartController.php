<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Services\PriceCalculator;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CartController extends Controller
{
    public function view()
    {
        $photos = $this->cartPhotos();
        $cart = $photos->pluck('id')->all();

        session(['cart' => $cart]);

        $price = $photos->isEmpty() ? 0 : PriceCalculator::calculate($photos->count());

        return view('cart', compact('cart', 'price', 'photos'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'photo_id' => 'required|integer',
            'action' => 'required|in:add,remove',
        ]);

        $cart = $this->cartPhotos()->pluck('id')->all();
        $photoId = (int) $request->photo_id;

        if ($request->action === 'add') {
            if (! $this->isPurchasablePhoto($photoId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto no disponible',
                ], 422);
            }

            $cart[] = $photoId;
        } else {
            $cart = array_diff($cart, [$photoId]);
        }

        $cart = array_values(array_unique(array_map('intval', $cart)));
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'price' => PriceCalculator::formatPrice(PriceCalculator::calculate(count($cart))),
            'message' => $request->action === 'add' ? 'Foto añadida' : 'Foto eliminada',
        ]);
    }

    private function cartPhotos()
    {
        $cart = array_values(array_unique(array_map('intval', session('cart', []))));

        if (empty($cart)) {
            return collect();
        }

        return Media::whereIn('id', $cart)
            ->where('collection_name', 'photos')
            ->where('model_type', Session::class)
            ->get();
    }

    private function isPurchasablePhoto(int $photoId): bool
    {
        return Media::where('id', $photoId)
            ->where('collection_name', 'photos')
            ->where('model_type', Session::class)
            ->exists();
    }
}
