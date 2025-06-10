<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompraFotosMailable;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleStripe(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');
        
        try {
            // Verificar la firma del webhook
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
            
            // Log del evento recibido
            Log::info('Stripe webhook recibido', ['type' => $event['type']]);
            
            // Manejar diferentes tipos de eventos
            switch ($event['type']) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event['data']['object']);
                    break;
                    
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event['data']['object']);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event['data']['object']);
                    break;
                    
                default:
                    Log::info('Evento no manejado', ['type' => $event['type']]);
            }
            
            return response()->json(['status' => 'success'], 200);
            
        } catch (\UnexpectedValueException $e) {
            // Firma inválida
            Log::error('Firma de webhook inválida', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
            
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Firma inválida
            Log::error('Error de verificación de firma', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
            
        } catch (\Exception $e) {
            Log::error('Error en webhook', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook error'], 500);
        }
    }
    
    private function handleCheckoutCompleted($session)
    {
        try {
            // Verificar si ya existe una compra con este session_id
            $existingPurchase = Purchase::where('stripe_session_id', $session['id'])->first();
            
            if ($existingPurchase) {
                Log::info('Compra ya procesada', ['session_id' => $session['id']]);
                return;
            }
            
            // Crear la compra
            $cart = json_decode($session['metadata']['cart'], true);
            $purchase = Purchase::create([
                'email' => $session['metadata']['email'],
                'media_ids' => $cart,
                'amount' => $session['amount_total'] / 100, // Convertir de céntimos
                'download_token' => Str::uuid(),
                'expires_at' => now()->addHours(72),
                'payment_status' => 'paid',
                'stripe_session_id' => $session['id']
            ]);
            
            // Enviar email
            Mail::to($purchase->email)->send(new CompraFotosMailable($purchase));
            
            Log::info('Compra procesada via webhook', [
                'purchase_id' => $purchase->id,
                'email' => $purchase->email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error procesando checkout completed', [
                'session_id' => $session['id'],
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Pago exitoso', ['payment_intent' => $paymentIntent['id']]);
        // Aquí puedes agregar lógica adicional si necesitas
    }
    
    private function handlePaymentFailed($paymentIntent)
    {
        Log::error('Pago fallido', [
            'payment_intent' => $paymentIntent['id'],
            'failure_reason' => $paymentIntent['last_payment_error']['message'] ?? 'Unknown'
        ]);
        // Aquí puedes agregar lógica para manejar pagos fallidos
    }
}