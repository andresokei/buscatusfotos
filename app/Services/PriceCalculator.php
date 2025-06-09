<?php

namespace App\Services;

class PriceCalculator
{
    public static function calculate($photoCount)
{
    $prices = config('ofertas.precios');
    
    if ($photoCount <= 6) {
            return $prices[$photoCount] ?? $prices[6];
        } else {
            // Más de 6 fotos: precio de 6 + extras
            $extras = $photoCount - 6;
            return $prices[6] + ($extras * $prices['extra']);
        }
}
    
    public static function formatPrice($cents)
    {
        return number_format($cents / 100, 2) . ' €';
    }
}