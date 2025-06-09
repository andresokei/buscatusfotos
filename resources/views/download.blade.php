@extends('layouts.app')

@section('title', 'Descargar Fotos - BuscaTusFotos')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-check-circle me-2"></i>¡Pago Confirmado!</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-download fa-3x text-success mb-3"></i>
                        <p class="lead">Ya puedes descargar tus fotos</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                            <span class="text-muted">{{ $purchase->email }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-images me-2"></i>Fotos:</strong><br>
                            <span class="text-muted">{{ count($purchase->media_ids) }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-euro-sign me-2"></i>Total pagado:</strong><br>
                            <span class="text-muted">{{ number_format($purchase->amount, 2) }} €</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-clock me-2"></i>Válido hasta:</strong><br>
                            <span class="text-muted">{{ $purchase->expires_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información importante:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Las fotos se descargarán en alta calidad sin marca de agua</li>
                            <li>El enlace de descarga expira en 72 horas</li>
                            <li>Guarda las fotos en tu dispositivo</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('download.file', $purchase->download_token) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-download me-2"></i>Descargar Fotos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection