@extends('layouts.app')

@section('title', $session->title . ' — BuscaTusFotos')

@section('content')
<div class="container-x">

    {{-- Breadcrumb --}}
    <nav class="meta mb-10">
        <a href="{{ route('home') }}" class="hover:text-ink transition">Inicio</a>
        <span class="text-zinc-300 mx-2">/</span>
        <span class="text-ink">{{ $session->title }}</span>
    </nav>

    {{-- Session header --}}
    <header class="mb-12 pb-10 border-b border-zinc-200">
        <p class="meta mb-3">{{ $session->date->format('d.m.Y') }}</p>
        <h1 class="text-4xl md:text-6xl font-display text-ink mb-4">{{ $session->title }}</h1>
        @if($session->description)
            <p class="text-lg text-zinc-500 max-w-2xl">{{ $session->description }}</p>
        @endif
    </header>

    {{-- Pricing --}}
    <section class="mb-14">
        <p class="meta mb-4">Precios</p>
        <div class="grid grid-cols-2 md:grid-cols-4 border border-zinc-200">
            <div class="p-5 border-r border-b md:border-b-0 border-zinc-200">
                <p class="meta mb-2">1 foto</p>
                <p class="text-2xl font-display">5 €</p>
                <p class="text-xs text-zinc-400 mt-1">5 €/u</p>
            </div>
            <div class="p-5 border-b md:border-r md:border-b-0 border-zinc-200">
                <p class="meta mb-2">2 fotos</p>
                <p class="text-2xl font-display">9 €</p>
                <p class="text-xs text-zinc-400 mt-1">4,50 €/u</p>
            </div>
            <div class="p-5 border-r border-zinc-200">
                <p class="meta mb-2">3 fotos</p>
                <p class="text-2xl font-display">12 €</p>
                <p class="text-xs text-zinc-400 mt-1">4 €/u</p>
            </div>
            <div class="p-5">
                <p class="meta mb-2">6+ fotos</p>
                <p class="text-2xl font-display">desde 20 €</p>
                <p class="text-xs text-zinc-400 mt-1">3,33 €/u</p>
            </div>
        </div>
    </section>

    {{-- Photos gallery --}}
    @if($photos->count() > 0)
        <section>
            <div class="flex items-end justify-between mb-8">
                <h2 class="text-2xl font-display">Fotos disponibles</h2>
                <span class="meta">{{ $photos->count() }} {{ Str::plural('foto', $photos->count()) }}</span>
            </div>
            <p class="meta mb-6 text-zinc-400">Haz clic en cualquier foto para verla más grande</p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($photos as $photo)
                    <div class="group relative bg-zinc-100">
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
                            <button class="add-to-cart btn btn-sm bg-ink/95 text-paper border-ink/95 hover:bg-accent hover:border-accent w-full"
                                    data-photo-id="{{ $photo->id }}">
                                Añadir
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @else
        <div class="text-center py-24 border border-zinc-200">
            <p class="font-display text-2xl text-zinc-400 mb-6">Sin fotos en esta sesión</p>
            <a href="{{ route('home') }}" class="btn-ghost btn-sm">← Volver a inicio</a>
        </div>
    @endif
</div>

<x-photo-modal>
    <button type="button"
            class="btn btn-accent add-to-cart"
            x-bind:data-photo-id="id"
            x-show="!inCart">
        Añadir al carrito
    </button>
    <span class="meta text-paper/60" x-show="inCart">Ya en el carrito</span>
</x-photo-modal>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartCount = document.getElementById('cart-count');

    function setAdded(button) {
        button.disabled = true;
        button.textContent = 'Añadida';
        button.classList.remove('bg-ink/95', 'border-ink/95', 'hover:bg-accent', 'hover:border-accent', 'bg-accent', 'border-accent');
        button.classList.add('bg-emerald-700', 'border-emerald-700', 'text-paper');
    }

    function refreshCartBadge(count) {
        if (!cartCount) return;
        cartCount.textContent = count;
        if (count > 0) {
            cartCount.classList.remove('hidden');
            cartCount.classList.add('inline-flex', 'items-center', 'justify-center', 'min-w-[20px]', 'h-5', 'px-1.5', 'rounded-full', 'bg-accent', 'text-paper', 'text-[10px]', 'font-medium');
        }
    }

    function addToCart(photoId, button) {
        const original = button.innerHTML;
        button.disabled = true;
        button.textContent = 'Añadiendo…';

        fetch('/carrito', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ photo_id: photoId, action: 'add' })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('add fail');
            refreshCartBadge(data.cart_count);
            document.querySelectorAll(`.add-to-cart[data-photo-id="${photoId}"]`).forEach(setAdded);
            window.dispatchEvent(new CustomEvent('photo-added', { detail: { id: parseInt(photoId, 10) } }));
        })
        .catch(() => {
            alert('Error al añadir foto al carrito');
            button.disabled = false;
            button.innerHTML = original;
        });
    }

    // Delegated handler so dynamically-bound buttons (modal) also work
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.add-to-cart');
        if (!btn || btn.disabled) return;
        const id = btn.dataset.photoId;
        if (!id) return;
        e.stopPropagation();
        addToCart(id, btn);
    });

    // Open photo modal via dispatched event
    document.querySelectorAll('[data-open-photo]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const id = parseInt(trigger.dataset.id, 10);
            const cardBtn = document.querySelector(`.add-to-cart[data-photo-id="${id}"]`);
            const inCart = !!(cardBtn && cardBtn.disabled);
            window.dispatchEvent(new CustomEvent('open-photo', {
                detail: {
                    url: trigger.dataset.url,
                    name: trigger.dataset.name,
                    id: id,
                    inCart: inCart
                }
            }));
        });
    });
});
</script>
@endpush
