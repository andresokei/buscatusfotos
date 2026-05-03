@extends('layouts.app')

@section('title', 'Descargar fotos — BuscaTusFotos')

@section('content')
<div class="container-x">
    <div class="max-w-2xl mx-auto">

        {{-- Confirmation header --}}
        <div class="bg-ink text-paper px-8 py-10 mb-8 flex items-center gap-5">
            <div class="w-12 h-12 rounded-full border border-paper/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div>
                <p class="meta text-paper/60 mb-1">Pago confirmado</p>
                <h1 class="text-2xl md:text-3xl font-display text-paper">¡Gracias por tu compra!</h1>
            </div>
        </div>

        {{-- Purchase details --}}
        <div class="border border-zinc-200 mb-8">
            <div class="grid grid-cols-2 divide-x divide-y divide-zinc-200">
                <div class="p-5">
                    <p class="meta mb-2">Email</p>
                    <p class="text-sm text-ink break-all">{{ $purchase->email }}</p>
                </div>
                <div class="p-5">
                    <p class="meta mb-2">Fotos</p>
                    <p class="font-display text-xl">{{ count($purchase->media_ids) }}</p>
                </div>
                <div class="p-5">
                    <p class="meta mb-2">Total pagado</p>
                    <p class="font-display text-xl">{{ number_format($purchase->amount, 2) }} €</p>
                </div>
                <div class="p-5">
                    <p class="meta mb-2">Válido hasta</p>
                    <p class="text-sm text-ink">{{ $purchase->expires_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="mb-10 space-y-3 text-sm text-zinc-600">
            <p class="meta text-ink mb-3">Información importante</p>
            <ul class="space-y-2">
                <li class="flex items-start gap-3">
                    <span class="text-zinc-300 mt-0.5">·</span>
                    <span>Las fotos se descargan en alta calidad sin marca de agua.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-zinc-300 mt-0.5">·</span>
                    <span>El enlace expira en {{ config('ofertas.descarga.expiracion_horas', 72) }} horas.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-zinc-300 mt-0.5">·</span>
                    <span>Descargas disponibles: {{ max(0, config('ofertas.descarga.max_intentos', 3) - $purchase->download_count) }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-zinc-300 mt-0.5">·</span>
                    <span>Guarda las fotos en tu dispositivo después de descargarlas.</span>
                </li>
            </ul>
        </div>

        <a href="{{ route('download.file', $purchase->download_token) }}" class="btn-accent btn-lg w-full inline-flex">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Descargar fotos
        </a>
    </div>
</div>
@endsection
