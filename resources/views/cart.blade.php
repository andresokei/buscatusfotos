@extends('layouts.app')

@section('title', 'Carrito - BuscaTusFotos')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Carrito</li>
        </ol>
    </nav>

    <!-- Pricing Info -->
@if(count($cart) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <strong>1 foto:</strong> 5,00 €
                </div>
                <div class="col-6 col-md-3">
                    <strong>2 fotos:</strong> 9,00 €
                </div>
                <div class="col-6 col-md-3">
                    <strong>3 fotos:</strong> 12,00 €
                </div>
                <div class="col-6 col-md-3">
                    <strong>6+ fotos:</strong> desde 20,00 €
                </div>
            </div>
            <div class="text-center mt-2">
                <small>¡Añade más fotos y ahorra dinero!</small>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Cart Content -->
    <div class="row">
        <div class="col-lg-8">
            <h1><i class="fas fa-shopping-cart me-2"></i>Mi Carrito</h1>
            
            @if(count($cart) > 0)
                <!-- Photos in Cart -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-images me-2"></i>Fotos seleccionadas ({{ count($cart) }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($photos as $photo)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card">
                                    <img src="{{ $photo->getUrl('thumb') }}" class="card-img-top" 
                                         alt="Foto" style="height: 150px; object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal" data-bs-target="#photoModal"
                                         data-photo-url="{{ $photo->getUrl('thumb') }}"
                                         data-photo-name="{{ $photo->name }}">
                                    <div class="card-body p-2">
                                        <button class="btn btn-outline-secondary btn-sm w-100 remove-from-cart" 
                                                data-photo-id="{{ $photo->id }}" title="Eliminar del carrito">
                                            <i class="fas fa-times me-1"></i>Quitar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="card">
                        <div class="card-body py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h3>Tu carrito está vacío</h3>
                            <p class="text-muted">Explora nuestras sesiones y añade fotos a tu carrito</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Ver Sesiones
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(count($cart) > 0)
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Fotos:</span>
                        <strong id="cart-items-count">{{ count($cart) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total:</span>
                        <strong class="text-primary" id="cart-total">{{ \App\Services\PriceCalculator::formatPrice($price) }}</strong>
                    </div>
                    <hr>
                    
                    <form action="{{ route('checkout.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Tu email:
                            </label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   placeholder="tu@email.com" required>
                            <div class="form-text">Te enviaremos las fotos a este email</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal para ver foto grande -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">
                    <i class="fas fa-image me-2"></i>Vista previa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-1">
                <img id="modalPhoto" src="" alt="Foto grande" class="img-fluid" style="max-height: 60vh; width: auto;">
            </div>
            <div class="modal-footer">
                <div class="w-100 text-center">
                    <small class="text-muted">Esta foto está en tu carrito</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    
    // Función para eliminar del carrito
    function removeFromCart(photoId, button) {
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Eliminando...';
        
        fetch('/carrito', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                photo_id: photoId,
                action: 'remove'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar la foto del DOM
                button.closest('.col-md-4').remove();
                
                // Actualizar contador del carrito
                document.getElementById('cart-count').textContent = data.cart_count;
                document.getElementById('cart-items-count').textContent = data.cart_count;
                
                // Si no quedan fotos, recargar la página
                if (data.cart_count === 0) {
                    location.reload();
                } else {
                    // Actualizar el precio
                    updateCartTotal(data.cart_count);
                }
            } else {
                throw new Error('Error al eliminar foto');
            }
        })
        .catch(error => {
            alert('Error al eliminar foto del carrito');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    // Event listeners para botones de eliminar
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const photoId = this.dataset.photoId;
            if (confirm('¿Eliminar esta foto del carrito?')) {
                removeFromCart(photoId, this);
            }
        });
    });
    
    // Event listener para abrir modal
    document.querySelectorAll('.card-img-top').forEach(img => {
        img.addEventListener('click', function() {
            const photoUrl = this.dataset.photoUrl;
            const photoName = this.dataset.photoName;
            
            document.getElementById('modalPhoto').src = photoUrl;
            document.getElementById('photoModalLabel').innerHTML = '<i class="fas fa-image me-2"></i>' + photoName;
        });
    });
    
    // Función para actualizar el total del carrito
    // Función para actualizar el total del carrito
function updateCartTotal(count) {
    const prices = {1: 5.00, 2: 9.00, 3: 12.00, 4: 15.00, 5: 17.50, 6: 20.00, extra: 3.00};
    let total = 0;
    
    if (count <= 6) {
        total = prices[count];
    } else {
        total = prices[6] + ((count - 6) * prices.extra);
    }
    
    document.getElementById('cart-total').textContent = total.toFixed(2) + ' €';
}
});
</script>
@endsection