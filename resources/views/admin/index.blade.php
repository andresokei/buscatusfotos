@extends('layouts.admin')

@section('title', 'Dashboard — Admin')

@section('content')
<div class="container-x">

    <header class="mb-10 pb-8 border-b border-zinc-200">
        <p class="meta mb-3">Vista general</p>
        <h1 class="text-4xl font-display text-ink">Dashboard</h1>
    </header>

    {{-- Stats --}}
    @php
        $sessionCount = \App\Models\Session::count();
        $photoCount = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('collection_name', 'photos')->count();
        $salesCount = \App\Models\Purchase::where('payment_status', 'paid')->count();
        $revenue = \App\Models\Purchase::where('payment_status', 'paid')->sum('amount');
    @endphp

    <section class="grid grid-cols-2 lg:grid-cols-4 border border-zinc-200 bg-paper mb-12">
        <div class="p-6 border-r border-b lg:border-b-0 border-zinc-200">
            <p class="meta mb-3">Sesiones</p>
            <p class="font-display text-4xl text-ink">{{ $sessionCount }}</p>
        </div>
        <div class="p-6 border-b lg:border-r lg:border-b-0 border-zinc-200">
            <p class="meta mb-3">Fotos</p>
            <p class="font-display text-4xl text-ink">{{ $photoCount }}</p>
        </div>
        <div class="p-6 border-r border-zinc-200">
            <p class="meta mb-3">Ventas</p>
            <p class="font-display text-4xl text-ink">{{ $salesCount }}</p>
        </div>
        <div class="p-6">
            <p class="meta mb-3">Ingresos</p>
            <p class="font-display text-4xl text-ink">{{ number_format($revenue, 2) }} <span class="text-xl text-zinc-400">€</span></p>
        </div>
    </section>

    {{-- Two columns --}}
    <div class="grid lg:grid-cols-2 gap-10">

        {{-- Quick actions --}}
        <section>
            <h2 class="meta mb-5">Acciones rápidas</h2>
            <div class="border border-zinc-200 bg-paper divide-y divide-zinc-200">
                <a href="{{ route('admin.sessions') }}"
                   class="flex items-center justify-between px-5 py-4 hover:bg-zinc-50 transition group">
                    <span class="text-sm text-ink">Gestionar sesiones</span>
                    <svg class="w-4 h-4 text-zinc-400 group-hover:text-accent transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
                <a href="{{ route('admin.session.create') }}"
                   class="flex items-center justify-between px-5 py-4 hover:bg-zinc-50 transition group">
                    <span class="text-sm text-ink">Crear nueva sesión</span>
                    <svg class="w-4 h-4 text-zinc-400 group-hover:text-accent transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center justify-between px-5 py-4 hover:bg-zinc-50 transition group">
                    <span class="text-sm text-ink">Ver sitio público</span>
                    <svg class="w-4 h-4 text-zinc-400 group-hover:text-accent transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                </a>
            </div>
        </section>

        {{-- Recent sales --}}
        <section>
            <h2 class="meta mb-5">Últimas ventas</h2>
            @php
                $recentSales = \App\Models\Purchase::where('payment_status', 'paid')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp
            <div class="border border-zinc-200 bg-paper">
                @forelse($recentSales as $sale)
                    <div class="flex items-center justify-between px-5 py-4 {{ !$loop->last ? 'border-b border-zinc-200' : '' }}">
                        <div class="min-w-0">
                            <p class="text-sm text-ink truncate">{{ $sale->email }}</p>
                            <p class="meta mt-1">{{ count($sale->media_ids) }} {{ Str::plural('foto', count($sale->media_ids)) }} · {{ $sale->created_at->format('d/m H:i') }}</p>
                        </div>
                        <p class="font-display text-lg text-ink ml-4 flex-shrink-0">{{ number_format($sale->amount, 2) }} €</p>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <p class="meta">Sin ventas todavía</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</div>
@endsection
