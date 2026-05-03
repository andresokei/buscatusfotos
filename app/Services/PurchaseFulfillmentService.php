<?php

namespace App\Services;

use App\Mail\CompraFotosMailable;
use App\Models\Purchase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PurchaseFulfillmentService
{
    public function fulfillStripeCheckout($stripeSession): Purchase
    {
        $sessionId = $stripeSession['id'] ?? $stripeSession->id;
        $email = data_get($stripeSession, 'metadata.email');
        $cart = json_decode(data_get($stripeSession, 'metadata.cart', '[]'), true) ?: [];
        $amount = (data_get($stripeSession, 'amount_total', 0) / 100);

        $purchase = Purchase::firstOrNew(['stripe_session_id' => $sessionId]);
        $wasRecentlyCreated = ! $purchase->exists;

        if ($wasRecentlyCreated) {
            $purchase->fill([
                'email' => $email,
                'media_ids' => $cart,
                'amount' => $amount,
                'download_token' => Str::uuid(),
                'expires_at' => now()->addHours(config('ofertas.descarga.expiracion_horas', 72)),
                'payment_status' => 'paid',
            ]);
        } else {
            $purchase->payment_status = 'paid';
        }

        $purchase->save();

        if ($wasRecentlyCreated) {
            Mail::to($purchase->email)->send(new CompraFotosMailable($purchase));
        }

        return $purchase;
    }
}
