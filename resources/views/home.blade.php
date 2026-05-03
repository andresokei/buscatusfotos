@extends('layouts.app')

@section('title', 'Inicio — BuscaTusFotos')

@section('content')
<div class="container-x">

    {{-- Hero --}}
    <section class="text-center max-w-3xl mx-auto mb-24 lg:mb-32">
        <p class="meta mb-6">Fotografía de surf</p>
        <h1 class="text-5xl md:text-7xl font-display font-normal text-ink leading-[1.05] mb-8">
            Encuentra tus fotos<br>
            <span class="italic text-accent">en las olas</span>
        </h1>
        <p class="text-lg text-zinc-500 max-w-xl mx-auto mb-10">
            Busca, compra y descarga tus mejores momentos surfeando.
        </p>

        <a href="https://ig.me/m/andresokei" target="_blank" rel="noopener" class="btn-ghost">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
            Escríbeme por Instagram
        </a>
    </section>

    {{-- Sessions --}}
    <section>
        <div class="flex items-end justify-between mb-10 border-b border-zinc-200 pb-6">
            <h2 class="text-2xl md:text-3xl font-display">Sesiones disponibles</h2>
            <span class="meta hidden md:inline">{{ $sessions->count() }} {{ Str::plural('sesión', $sessions->count()) }}</span>
        </div>

        @if($sessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
                @foreach($sessions as $session)
                    @php
                        $coverPhoto = $session->getMedia('photos')->first();
                        $photoCount = $session->getMedia('photos')->count();
                        $hasPhotos = $photoCount > 0;
                    @endphp

                    <a href="{{ $hasPhotos ? route('session.show', $session->slug) : '#' }}"
                       class="group block {{ $hasPhotos ? '' : 'pointer-events-none' }}">

                        <div class="relative overflow-hidden bg-zinc-100 aspect-[4/3] mb-5">
                            @if($coverPhoto)
                                <img src="{{ $coverPhoto->getUrl('thumb') }}"
                                     alt="{{ $session->title }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-400">
                                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                        <circle cx="12" cy="13" r="4"/>
                                    </svg>
                                </div>
                            @endif
                            @if(!$hasPhotos)
                                <div class="absolute top-3 left-3 bg-paper text-ink meta px-2 py-1">Próximamente</div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 mb-2">
                            <span class="meta">{{ $session->date->format('d.m.Y') }}</span>
                            <span class="text-zinc-300">·</span>
                            <span class="meta">{{ $photoCount }} {{ Str::plural('foto', $photoCount) }}</span>
                        </div>
                        <h3 class="text-xl font-display text-ink group-hover:text-accent transition-colors">
                            {{ $session->title }}
                        </h3>
                        @if($session->description)
                            <p class="text-sm text-zinc-500 mt-2 line-clamp-2">{{ Str::limit($session->description, 90) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-24">
                <p class="font-display text-2xl text-zinc-400 mb-3">No hay sesiones todavía</p>
                <p class="meta">Vuelve pronto</p>
            </div>
        @endif
    </section>

</div>
@endsection
