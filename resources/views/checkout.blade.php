@extends('layouts.app')

@section('title', 'Checkout — BuscaTusFotos')

@section('content')
<div class="container-x">

    {{-- Breadcrumb --}}
    <nav class="meta mb-10">
        <a href="{{ route('home') }}" class="hover:text-ink transition">Inicio</a>
        <span class="text-zinc-300 mx-2">/</span>
        <a href="{{ route('cart.view') }}" class="hover:text-ink transition">Carrito</a>
        <span class="text-zinc-300 mx-2">/</span>
        <span class="text-ink">Checkout</span>
    </nav>

    <div class="max-w-xl mx-auto">
        <header class="mb-10 pb-8 border-b border-zinc-200">
            <p class="meta mb-3">Casi listo</p>
            <h1 class="text-4xl md:text-5xl font-display text-ink">Resumen de compra</h1>
        </header>

        <div class="border border-zinc-200 divide-y divide-zinc-200 mb-8">
            <div class="flex items-center justify-between px-6 py-4">
                <span class="meta">Fotos</span>
                <span class="font-display text-lg">{{ count($cart) }}</span>
            </div>
            <div class="flex items-center justify-between px-6 py-4">
                <span class="meta">Email</span>
                <span class="text-sm text-ink">{{ $email }}</span>
            </div>
            <div class="flex items-end justify-between px-6 py-6 bg-zinc-50">
                <span class="meta">Total</span>
                <span class="font-display text-4xl text-ink">{{ \App\Services\PriceCalculator::formatPrice($amount) }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 mb-8 text-zinc-500">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <p class="text-xs">Pago seguro con Stripe · Datos protegidos con SSL</p>
        </div>

        <form action="{{ route('checkout.create') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="btn-accent btn-lg w-full">
                Pagar con Stripe
            </button>
            <a href="{{ route('cart.view') }}" class="btn-ghost w-full">
                ← Volver al carrito
            </a>
        </form>
    </div>
</div>
@endsection
