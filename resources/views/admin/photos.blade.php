@extends('layouts.admin')

@section('title', 'Fotos · ' . $session->title . ' — Admin')

@section('content')
<div class="container-x" x-data="{ flash: null, flashType: 'success' }">

    {{-- Breadcrumb --}}
    <nav class="meta mb-8">
        <a href="{{ route('admin.index') }}" class="hover:text-ink transition">Dashboard</a>
        <span class="text-zinc-300 mx-2">/</span>
        <a href="{{ route('admin.sessions') }}" class="hover:text-ink transition">Sesiones</a>
        <span class="text-zinc-300 mx-2">/</span>
        <span class="text-ink">{{ $session->title }}</span>
    </nav>

    {{-- Header --}}
    <header class="mb-10 pb-8 border-b border-zinc-200 flex items-end justify-between gap-4">
        <div>
            <p class="meta mb-3">{{ $session->date->format('d.m.Y') }} · <span id="photoCountText">{{ $photos->count() }} {{ Str::plural('foto', $photos->count()) }}</span></p>
            <h1 class="text-4xl font-display text-ink">{{ $session->title }}</h1>
        </div>
        <a href="{{ route('session.show', $session->slug) }}" target="_blank" class="btn-ghost">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
            </svg>
            Ver en sitio
        </a>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div x-data="{show: true}" x-show="show" class="mb-6 border-l-2 border-emerald-600 bg-paper px-5 py-4 flex items-start justify-between gap-4">
            <p class="text-sm text-ink">{{ session('success') }}</p>
            <button @click="show = false" class="text-zinc-400 hover:text-ink transition" aria-label="Cerrar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{show: true}" x-show="show" class="mb-6 border-l-2 border-accent bg-paper px-5 py-4 flex items-start justify-between gap-4">
            <p class="text-sm text-ink">{{ session('error') }}</p>
            <button @click="show = false" class="text-zinc-400 hover:text-ink transition" aria-label="Cerrar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif

    {{-- Dynamic flash from delete --}}
    <div x-show="flash" x-transition class="mb-6 border-l-2 px-5 py-4 flex items-start justify-between gap-4 bg-paper"
         :class="flashType === 'success' ? 'border-emerald-600' : 'border-accent'">
        <p class="text-sm text-ink" x-text="flash"></p>
        <button @click="flash = null" class="text-zinc-400 hover:text-ink transition" aria-label="Cerrar">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    {{-- Upload --}}
    <section class="mb-12">
        <h2 class="meta mb-5">Subir foto</h2>
        <div class="border border-zinc-200 bg-paper p-6">
            <form action="{{ route('admin.session.upload', $session->id) }}"
                  method="POST" enctype="multipart/form-data"
                  class="flex flex-col sm:flex-row gap-4 items-stretch">
                @csrf
                <input type="file" name="photo" accept="image/*" required
                       class="input flex-1 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:bg-zinc-100 file:text-ink hover:file:bg-zinc-200">
                <button type="submit" class="btn-primary sm:w-auto whitespace-nowrap">
                    Subir
                </button>
            </form>
            <p class="meta mt-3 normal-case tracking-normal">JPG, PNG, GIF · máx 10 MB</p>
        </div>
    </section>

    {{-- Photos grid --}}
    @if($photos->count() > 0)
        <section>
            <h2 class="meta mb-5">Fotos subidas</h2>
            <div id="photos-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($photos as $photo)
                    <article class="photo-cell group relative bg-zinc-100 border border-zinc-200" data-photo-id="{{ $photo->id }}">
                        <a href="{{ $photo->getUrl() }}" target="_blank" class="block aspect-square overflow-hidden">
                            <img src="{{ $photo->getUrl() }}" alt="{{ $photo->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">
                        </a>

                        <div class="px-2 py-2 bg-paper border-t border-zinc-200">
                            <p class="text-xs text-ink truncate" title="{{ $photo->name }}">{{ $photo->name }}</p>
                            <p class="meta normal-case tracking-normal mt-0.5">{{ $photo->human_readable_size }}</p>
                        </div>

                        <button type="button"
                                class="delete-photo absolute top-2 right-2 w-7 h-7 bg-paper/95 border border-zinc-200 hover:border-accent hover:text-accent text-ink opacity-0 group-hover:opacity-100 focus:opacity-100 transition flex items-center justify-center"
                                data-photo-id="{{ $photo->id }}"
                                data-photo-name="{{ $photo->name }}"
                                aria-label="Eliminar foto">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </article>
                @endforeach
            </div>
        </section>
    @else
        <div class="text-center py-20 border border-zinc-200 bg-paper">
            <p class="meta mb-3">Vacío</p>
            <p class="font-display text-xl text-zinc-400">Sin fotos todavía</p>
            <p class="meta mt-3 normal-case tracking-normal">Sube la primera con el formulario de arriba</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const root = document.querySelector('[x-data]')?.__x?.$data;

    function showFlash(type, message) {
        const container = document.querySelector('[x-data]');
        if (container && container._x_dataStack) {
            const data = container._x_dataStack[0];
            data.flash = message;
            data.flashType = type;
            setTimeout(() => { if (data.flash === message) data.flash = null; }, 5000);
        } else {
            alert(message);
        }
    }

    function updatePhotoCount() {
        const count = document.querySelectorAll('.photo-cell').length;
        const el = document.getElementById('photoCountText');
        if (el) {
            el.textContent = count + ' ' + (count === 1 ? 'foto' : 'fotos');
        }
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-photo');
        if (!btn) return;

        const photoId = btn.dataset.photoId;
        const photoName = btn.dataset.photoName;

        if (!confirm('¿Eliminar la foto "' + photoName + '"?\n\nEsta acción no se puede deshacer.')) return;

        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M22 12a10 10 0 0 1-10 10"/></svg>';

        fetch('/admin/fotos/' + photoId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Error al eliminar');
            const cell = btn.closest('.photo-cell');
            if (cell) cell.remove();
            updatePhotoCount();
            showFlash('success', 'Foto eliminada correctamente');
        })
        .catch(err => {
            showFlash('error', 'Error al eliminar: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = original;
        });
    });
});
</script>
@endpush
