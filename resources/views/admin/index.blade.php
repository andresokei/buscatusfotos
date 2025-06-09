@extends('layouts.admin')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
            <p class="text-muted">Panel de administración de BuscaTusFotos.com</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Sesiones</h5>
                            <h3>{{ \App\Models\Session::count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-photo-video fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Fotos</h5>
                            <h3>{{ \Spatie\MediaLibrary\MediaCollections\Models\Media::where('collection_name', 'photos')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-images fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Ventas</h5>
                            <h3>{{ \App\Models\Purchase::where('payment_status', 'paid')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Ingresos</h5>
                            <h3>{{ number_format(\App\Models\Purchase::where('payment_status', 'paid')->sum('amount'), 2) }} €</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-euro-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.sessions') }}" class="btn btn-primary">
                            <i class="fas fa-photo-video me-2"></i>Gestionar Sesiones
                        </a>
                        <button class="btn btn-outline-secondary" onclick="window.open('{{ route('home') }}', '_blank')">
                            <i class="fas fa-external-link-alt me-2"></i>Ver Sitio Web
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Últimas Ventas</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentSales = \App\Models\Purchase::where('payment_status', 'paid')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($recentSales as $sale)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted">{{ $sale->email }}</small><br>
                                <small>{{ count($sale->media_ids) }} fotos</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format($sale->amount, 2) }} €</strong><br>
                                <small class="text-muted">{{ $sale->created_at->format('d/m H:i') }}</small>
                            </div>
                        </div>
                        @if(!$loop->last) <hr class="my-2"> @endif
                    @empty
                        <p class="text-muted mb-0">No hay ventas recientes</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection