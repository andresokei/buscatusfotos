@extends('layouts.app')

@section('title', 'Carrito — BuscaTusFotos')

@section('content')
<div class="container-x">

    {{-- Breadcrumb --}}
    <nav class="meta mb-10">
        <a href="{{ route('home') }}" class="hover:text-ink transition">Inicio</a>
        <span class="text-zinc-300 mx-2">/</span>
        <span class="text-ink">Carrito</span>
    </nav>

    {{-- Flash messages --}}
    @if(session('error'))
        <div x-data="{show: true}" x-show="show" class="mb-8 border-l-2 border-accent bg-zinc-50 px-5 py-4 flex items-start justify-between gap-4">
            <p class="text-sm text-ink">{{ session('error') }}</p>
            <button @click="show = false" class="text-zinc-400 hover:text-ink transition" aria-label="Cerrar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif
    @if(session('success'))
        <div x-data="{show: true}" x-show="show" class="mb-8 border-l-2 border-emerald-600 bg-zinc-50 px-5 py-4 flex items-start justify-between gap-4">
            <p class="text-sm text-ink">{{ session('success') }}</p>
            <button @click="show = false" class="text-zinc-400 hover:text-ink transition" aria-label="Cerrar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif

    {{-- Header --}}
    <header class="mb-10 pb-8 border-b border-zinc-200">
        <p class="meta mb-3">Tu selección</p>
        <h1 class="text-4xl md:text-5xl font-display text-ink">Carrito</h1>
    </header>

    @if(count($cart) > 0)
        {{-- Pricing reminder --}}
        <div class="mb-12 grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-zinc-200 border border-zinc-200 text-center">
            <div class="p-4">
                <p class="meta">1 foto</p>
                <p class="font-display mt-1">6 EUR</p>
            </div>
            <div class="p-4">
                <p class="meta">3 fotos</p>
                <p class="font-display mt-1">13 EUR</p>
            </div>
            <div class="p-4">
                <p class="meta">6 fotos</p>
                <p class="font-display mt-1">22 EUR</p>
            </div>
            <div class="p-4">
                <p class="meta">Extras</p>
                <p class="font-display mt-1">3 EUR/foto</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-12">
            {{-- Photos --}}
            <div class="lg:col-span-2">
                <div class="flex items-end justify-between mb-6">
                    <h2 class="text-xl font-display">Fotos seleccionadas</h2>
                    <span class="meta">{{ count($cart) }} {{ Str::plural('foto', count($cart)) }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($photos as $photo)
                        <div class="group relative bg-zinc-100 cart-item">
                            <button type="button"
                                    class="block w-full aspect-square overflow-hidden cursor-zoom-in"
                                    data-open-photo
                                    data-url="{{ route('photo.thumb', $photo) }}"
                                    data-name="{{ $photo->name }}"
                                    data-id="{{ $photo->id }}">
                                <img src="{{ route('photo.thumb', $photo) }}"
                                     alt="{{ $photo->name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            </button>

                            <div class="absolute inset-x-0 bottom-0 p-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                <button class="remove-from-cart btn btn-sm bg-paper/95 text-ink border-paper/95 hover:bg-accent hover:text-paper hover:border-accent w-full"
                                        data-photo-id="{{ $photo->id }}">
                                    Quitar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Summary --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="border border-zinc-200 p-6">
                    <p class="meta mb-6">Resumen</p>

                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm">Fotos</span>
                        <span id="cart-items-count" class="font-display text-lg">{{ count($cart) }}</span>
                    </div>
                    <div class="flex items-end justify-between mb-6 pt-4 border-t border-zinc-200">
                        <span class="text-sm">Total</span>
                        <span id="cart-total" class="font-display text-3xl">{{ \App\Services\PriceCalculator::formatPrice($price) }}</span>
                    </div>

                    <form action="{{ route('checkout.create') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="label">Tu email</label>
                            <input type="email" name="email" id="email" class="input" placeholder="tu@email.com" required>
                            <p class="text-xs text-zinc-400 mt-2">Te enviaremos las fotos a este email</p>
                        </div>
                        <button type="submit" class="btn-accent btn-lg w-full">
                            Proceder al pago
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    @else
        {{-- Empty cart --}}
        <div class="text-center py-24 max-w-md mx-auto">
            <p class="meta mb-4">Vacío</p>
            <h2 class="font-display text-3xl text-ink mb-3">Aún no has elegido fotos</h2>
            <p class="text-zinc-500 mb-10">Explora las sesiones y añade tus mejores momentos en las olas.</p>
            <a href="{{ route('home') }}" class="btn-primary">Ver sesiones</a>
        </div>
    @endif
</div>

<x-photo-modal>
    <span class="meta text-paper/60">En tu carrito</span>
</x-photo-modal>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartCount = document.getElementById('cart-count');

    function updateBadge(count) {
        if (cartCount) {
            cartCount.textContent = count;
            if (count === 0) cartCount.classList.add('hidden');
        }
    }

    function removeFromCart(photoId, button) {
        const original = button.innerHTML;
        button.disabled = true;
        button.textContent = 'Quitando…';

        fetch('/carrito', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ photo_id: photoId, action: 'remove' })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('remove fail');
            const item = button.closest('.cart-item');
            if (item) item.remove();

            updateBadge(data.cart_count);
            const itemsCount = document.getElementById('cart-items-count');
            if (itemsCount) itemsCount.textContent = data.cart_count;

            if (data.cart_count === 0) {
                location.reload();
            } else {
                document.getElementById('cart-total').textContent = data.price;
            }
        })
        .catch(() => {
            alert('Error al eliminar foto del carrito');
            button.disabled = false;
            button.innerHTML = original;
        });
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-from-cart');
        if (!btn || btn.disabled) return;
        if (!confirm('¿Quitar esta foto del carrito?')) return;
        removeFromCart(btn.dataset.photoId, btn);
    });

    document.querySelectorAll('[data-open-photo]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            window.dispatchEvent(new CustomEvent('open-photo', {
                detail: {
                    url: trigger.dataset.url,
                    name: trigger.dataset.name,
                    id: parseInt(trigger.dataset.id, 10),
                    inCart: true
                }
            }));
        });
    });
});
</script>
@endpush
