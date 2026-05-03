<?php

namespace App\Http\Controllers;

use App\Services\PurchaseFulfillmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleStripe(Request $request, PurchaseFulfillmentService $fulfillment)
    {
        $payload = $request->getContent();
        $signature = $request->header('stripe-signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );

            Log::info('Stripe webhook recibido', ['type' => $event['type']]);

            match ($event['type']) {
                'checkout.session.completed' => $this->handleCheckoutCompleted($event['data']['object'], $fulfillment),
                'payment_intent.succeeded' => Log::info('Pago exitoso', ['payment_intent' => $event['data']['object']['id']]),
                'payment_intent.payment_failed' => Log::error('Pago fallido', [
                    'payment_intent' => $event['data']['object']['id'],
                    'failure_reason' => $event['data']['object']['last_payment_error']['message'] ?? 'Unknown',
                ]),
                default => Log::info('Evento no manejado', ['type' => $event['type']]),
            };

            return response()->json(['status' => 'success']);
        } catch (\UnexpectedValueException|\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Firma de webhook invalida', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Error en webhook', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Webhook error'], 500);
        }
    }

    private function handleCheckoutCompleted($session, PurchaseFulfillmentService $fulfillment): void
    {
        $purchase = $fulfillment->fulfillStripeCheckout($session);

        Log::info('Compra procesada via webhook', [
            'purchase_id' => $purchase->id,
        ]);
    }
}
