@extends('layouts.app')

@section('title', 'Inicio - BuscaTusFotos')

@section('content')
<div class="container">
    <!-- Hero Section -->
<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-4 mb-3">Encuentra tus fotos de surf</h1>
        <p class="lead text-muted">Busca, compra y descarga tus mejores momentos en las olas</p>
    </div>
</div>


    <!-- Sessions Grid -->
<div class="row">
    <div class="col-12 mb-4">
        <h2><i class="fas fa-photo-video me-2"></i>Sesiones disponibles</h2>
    </div>
    
    @forelse($sessions as $session)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
            @php
                $coverPhoto = $session->getMedia('photos')->first();
            @endphp
            
            @if($coverPhoto)
                <img src="{{ $coverPhoto->getUrl('thumb') }}" class="card-img-top" 
                     alt="{{ $session->title }}" style="height: 250px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                     style="height: 250px;">
                    <div class="text-center text-muted">
                        <i class="fas fa-camera fa-3x mb-2"></i>
                        <p class="mb-0">Sin fotos aún</p>
                    </div>
                </div>
            @endif
            
            <div class="card-body">
                <h5 class="card-title">{{ $session->title }}</h5>
                <div class="mb-2">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>{{ $session->date->format('d/m/Y') }}
                    </small>
                </div>
                <div class="mb-2">
                    <small class="text-muted">
                        <i class="fas fa-images me-1"></i>{{ $session->getMedia('photos')->count() }} fotos
                    </small>
                </div>
                @if($session->description)
                    <p class="card-text text-muted small">{{ Str::limit($session->description, 80) }}</p>
                @endif
            </div>
            
            <div class="card-footer bg-transparent">
                @if($session->getMedia('photos')->count() > 0)
                    <a href="{{ route('session.show', $session->slug) }}" class="btn btn-primary w-100">
                        <i class="fas fa-eye me-2"></i>Ver fotos
                    </a>
                @else
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-clock me-2"></i>Próximamente
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>No hay sesiones disponibles aún.
        </div>
    </div>
    @endforelse
</div>
</div>
@endsection