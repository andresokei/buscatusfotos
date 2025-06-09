@extends('layouts.app')

@section('title', 'Checkout - BuscaTusFotos')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.view') }}">Carrito</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-credit-card me-2"></i>Resumen de Compra</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong><i class="fas fa-images me-2"></i>Fotos:</strong>
                        </div>
                        <div class="col-6 text-end">
                            {{ count($cart) }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong><i class="fas fa-envelope me-2"></i>Email:</strong>
                        </div>
                        <div class="col-6 text-end">
                            {{ $email }}
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <h5><i class="fas fa-euro-sign me-2"></i>Total:</h5>
                        </div>
                        <div class="col-6 text-end">
                            <h5 class="text-primary">{{ \App\Services\PriceCalculator::formatPrice($amount) }}</h5>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Pago seguro con Stripe</strong><br>
                        Tus datos están protegidos con encriptación SSL
                    </div>
                    
                    <form action="{{ route('checkout.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('cart.view') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver al carrito
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-lock me-2"></i>Pagar con Stripe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection