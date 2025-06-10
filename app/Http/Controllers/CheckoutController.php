<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PriceCalculator;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompraFotosMailable;
use App\Models\Purchase;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
   public function createSession(Request $request)
{
    $request->validate([
        'email' => 'required|email|max:255'
    ]);

    $cart = session('cart', []);
    
    if (empty($cart)) {
        return redirect()->route('cart.view')->with('error', 'El carrito está vacío');
    }

    $amount = PriceCalculator::calculate(count($cart));
    
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => count($cart) . ' fotos de surf',
                        'description' => 'Fotos de BuscaTusFotos.com',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => env('APP_URL') . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('APP_URL') . '/carrito',
            'customer_email' => $request->email,
            'metadata' => [
                'cart' => json_encode($cart),
                'email' => $request->email,
            ],
        ]);
        
        return redirect($session->url);
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al crear el pago: ' . $e->getMessage());
    }
}
    public function simulatePayment(Request $request)
{
    $cart = session('cart', []);
    $amount = PriceCalculator::calculate(count($cart));
    
    // Crear la compra
    $purchase = Purchase::create([
        'email' => $request->email,
        'media_ids' => $cart,
        // 'session_id' => 1,
        'amount' => $amount / 100, // Convertir céntimos a euros
        'download_token' => Str::uuid(),
        'expires_at' => now()->addHours(72),
        'payment_status' => 'paid'
    ]);
    
    // Enviar email
    Mail::to($purchase->email)->send(new CompraFotosMailable($purchase));
    
    // Limpiar carrito
    session()->forget('cart');
    
    return redirect()->route('download.show', $purchase->download_token)
        ->with('success', 'Pago procesado correctamente. Se ha enviado un email con el enlace de descarga.');
}

public function success(Request $request)
{
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
    try {
        $sessionId = $request->get('session_id');
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        
        if ($session->payment_status === 'paid') {
            // Crear la compra
            $cart = json_decode($session->metadata->cart, true);
            $purchase = Purchase::create([
                'email' => $session->metadata->email,
                'media_ids' => $cart,
                // 'session_id' => 1,
                'amount' => $session->amount_total / 100,
                'download_token' => Str::uuid(),
                'expires_at' => now()->addHours(72),
                'payment_status' => 'paid'
            ]);
            
            // Enviar email
            Mail::to($purchase->email)->send(new CompraFotosMailable($purchase));
            
            // Limpiar carrito
            session()->forget('cart');
            
            return redirect()->route('download.show', $purchase->download_token)
                ->with('success', 'Pago realizado correctamente. Se ha enviado un email con el enlace de descarga.');
        }
        
    } catch (\Exception $e) {
        return redirect()->route('cart.view')->with('error', 'Error al verificar el pago: ' . $e->getMessage());
    }
    
    return redirect()->route('cart.view')->with('error', 'Pago no completado');
}
}