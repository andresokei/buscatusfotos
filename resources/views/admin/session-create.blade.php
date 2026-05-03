@extends('layouts.admin')

@section('title', 'Nueva sesión — Admin')

@section('content')
<div class="container-x">

    {{-- Breadcrumb --}}
    <nav class="meta mb-8">
        <a href="{{ route('admin.index') }}" class="hover:text-ink transition">Dashboard</a>
        <span class="text-zinc-300 mx-2">/</span>
        <a href="{{ route('admin.sessions') }}" class="hover:text-ink transition">Sesiones</a>
        <span class="text-zinc-300 mx-2">/</span>
        <span class="text-ink">Nueva</span>
    </nav>

    {{-- Header --}}
    <header class="mb-10 pb-8 border-b border-zinc-200">
        <p class="meta mb-3">Crear</p>
        <h1 class="text-4xl font-display text-ink">Nueva sesión</h1>
    </header>

    <form action="{{ route('admin.session.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf

        <div class="grid lg:grid-cols-2 gap-10">
            {{-- Datos --}}
            <section>
                <h2 class="meta mb-5">Datos de la sesión</h2>
                <div class="border border-zinc-200 bg-paper p-6 space-y-5">
                    <div>
                        <label for="title" class="label">Título *</label>
                        <input type="text" name="title" id="title" class="input"
                               placeholder="Ej: Sesión Playa del Sardinero"
                               value="{{ old('title') }}" required>
                        @error('title')
                            <p class="text-xs text-accent mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="label">Fecha *</label>
                        <input type="date" name="date" id="date" class="input"
                               value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <p class="text-xs text-accent mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="label">Descripción</label>
                        <textarea name="description" id="description" rows="4" class="input resize-none"
                                  placeholder="Describe la sesión, condiciones del mar, etc.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-accent mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="listed" class="flex items-start gap-3 cursor-pointer pt-2">
                        <input type="checkbox" name="listed" id="listed" value="1"
                               {{ old('listed', true) ? 'checked' : '' }}
                               class="mt-0.5 rounded-none border-zinc-300 text-ink focus:ring-0 focus:ring-offset-0">
                        <div>
                            <p class="text-sm text-ink">Visible en el sitio público</p>
                            <p class="meta mt-1 normal-case tracking-normal">Si lo desmarcas, la sesión queda oculta para los visitantes</p>
                        </div>
                    </label>
                </div>
            </section>

            {{-- Upload --}}
            <section>
                <h2 class="meta mb-5">Fotos de la sesión</h2>
                <div class="border border-zinc-200 bg-paper p-6">
                    <label for="photos" class="block border border-dashed border-zinc-300 hover:border-ink transition p-8 text-center cursor-pointer">
                        <svg class="w-8 h-8 mx-auto text-zinc-400 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p class="text-sm text-ink mb-1">Selecciona fotos o arrástralas aquí</p>
                        <p class="meta normal-case tracking-normal">JPG, PNG, GIF · máx 10 MB cada una</p>
                        <input type="file" name="photos[]" id="photos" accept="image/*" multiple class="hidden">
                    </label>

                    <div id="photo-preview" class="grid grid-cols-3 gap-2 mt-5"></div>
                </div>
            </section>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-zinc-200">
            <a href="{{ route('admin.sessions') }}" class="meta hover:text-ink transition">
                ← Cancelar
            </a>
            <button type="submit" class="btn-accent btn-lg">
                Crear sesión
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = '';

    if (e.target.files.length === 0) return;

    Array.from(e.target.files).forEach((file) => {
        if (!file.type.startsWith('image/')) return;

        const cell = document.createElement('div');
        cell.className = 'relative aspect-square bg-zinc-100 overflow-hidden';

        const reader = new FileReader();
        reader.onload = (ev) => {
            cell.innerHTML = `
                <img src="${ev.target.result}" class="w-full h-full object-cover" alt="${file.name}">
                <div class="absolute inset-x-0 bottom-0 bg-ink/80 text-paper text-[10px] tracking-wider uppercase px-2 py-1 truncate" title="${file.name}">${file.name}</div>
            `;
        };
        reader.readAsDataURL(file);
        preview.appendChild(cell);
    });
});
</script>
@endpush
