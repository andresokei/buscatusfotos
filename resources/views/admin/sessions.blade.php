@extends('layouts.admin')

@section('title', 'Sesiones — Admin')

@section('content')
<div class="container-x">

    {{-- Header --}}
    <div class="flex items-end justify-between mb-10 pb-8 border-b border-zinc-200 gap-4">
        <div>
            <p class="meta mb-3">Gestión</p>
            <h1 class="text-4xl font-display text-ink">Sesiones</h1>
        </div>
        <a href="{{ route('admin.session.create') }}" class="btn-accent">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nueva sesión
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div x-data="{show: true}" x-show="show" class="mb-8 border-l-2 border-emerald-600 bg-paper px-5 py-4 flex items-start justify-between gap-4">
            <p class="text-sm text-ink">{{ session('success') }}</p>
            <button @click="show = false" class="text-zinc-400 hover:text-ink transition" aria-label="Cerrar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif

    @if($sessions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sessions as $session)
                @php $photoCount = $session->getMedia('photos')->count(); @endphp
                <article class="border border-zinc-200 bg-paper flex flex-col">
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <h2 class="text-lg font-display text-ink leading-tight">{{ $session->title }}</h2>
                            <span class="meta inline-flex items-center gap-1.5 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full {{ $session->listed ? 'bg-emerald-500' : 'bg-zinc-300' }}"></span>
                                {{ $session->listed ? 'Visible' : 'Oculta' }}
                            </span>
                        </div>

                        <div class="space-y-1.5 mb-4">
                            <p class="meta">{{ $session->date->format('d.m.Y') }}</p>
                            <p class="meta">{{ $photoCount }} {{ Str::plural('foto', $photoCount) }}</p>
                        </div>

                        @if($session->description)
                            <p class="text-sm text-zinc-500 line-clamp-2">{{ Str::limit($session->description, 100) }}</p>
                        @endif
                    </div>

                    <div class="border-t border-zinc-200 divide-y divide-zinc-200">
                        <a href="{{ route('admin.session.photos', $session->id) }}"
                           class="flex items-center justify-between px-5 py-3 hover:bg-zinc-50 transition group">
                            <span class="meta text-ink">Gestionar fotos</span>
                            <svg class="w-3.5 h-3.5 text-zinc-400 group-hover:text-accent transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <div class="grid grid-cols-2 divide-x divide-zinc-200">
                            <a href="{{ route('session.show', $session->slug) }}" target="_blank"
                               class="text-center px-3 py-2.5 meta hover:text-ink hover:bg-zinc-50 transition">
                                Ver en sitio
                            </a>
                            <button type="button"
                                    onclick="confirmDelete({{ $session->id }}, @js($session->title))"
                                    class="text-center px-3 py-2.5 meta hover:text-accent hover:bg-zinc-50 transition">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="text-center py-24 max-w-md mx-auto">
            <p class="meta mb-4">Vacío</p>
            <h2 class="font-display text-2xl text-ink mb-3">No hay sesiones todavía</h2>
            <p class="text-zinc-500 mb-8">Crea tu primera sesión para empezar a subir fotos.</p>
            <a href="{{ route('admin.session.create') }}" class="btn-accent">Crear sesión</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(sessionId, sessionTitle) {
    if (!confirm('¿Eliminar la sesión "' + sessionTitle + '"?\n\nSe eliminarán también todas sus fotos. Esta acción no se puede deshacer.')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/sesiones/' + sessionId;

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';

    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';

    form.appendChild(csrf);
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
