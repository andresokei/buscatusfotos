<?php

namespace App\Services;

class PriceCalculator
{
    public static function calculate(int $photoCount): int
    {
        $prices = config('ofertas.precios');

        if ($photoCount <= 0) {
            return 0;
        }

        if ($photoCount <= 6) {
            return $prices[$photoCount] ?? $prices[6];
        }

        return $prices[6] + (($photoCount - 6) * $prices['extra']);
    }

    public static function formatPrice(int $cents): string
    {
        return number_format($cents / 100, 2) . ' EUR';
    }
}
