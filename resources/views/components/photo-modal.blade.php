<div x-data="{ open: false, url: '', name: '', id: null, inCart: false }"
     @open-photo.window="open = true; url = $event.detail.url; name = $event.detail.name; id = $event.detail.id; inCart = !!$event.detail.inCart"
     @photo-added.window="if ($event.detail.id == id) inCart = true"
     x-cloak>

    <div x-show="open"
         x-transition.opacity.duration.200ms
         class="fixed inset-0 z-50 bg-ink/95 flex flex-col items-center justify-center p-6"
         @click.self="open = false"
         @keydown.escape.window="open = false">

        <button @click="open = false"
                class="absolute top-6 right-6 text-paper/70 hover:text-paper transition"
                aria-label="Cerrar">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <img :src="url" :alt="name" class="max-h-[78vh] max-w-full object-contain">

        <div class="mt-6 flex flex-col items-center gap-3">
            <p class="meta text-paper/60" x-text="name"></p>
            {{ $slot ?? '' }}
        </div>
    </div>
</div>
