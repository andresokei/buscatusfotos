@extends('layouts.app')

@section('title', $session->title . ' - BuscaTusFotos')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">{{ $session->title }}</li>
        </ol>
    </nav>
    <!-- Pricing Section -->
<div class="row mb-5">
    <div class="col-12 text-center mb-4">
        <!-- <h2><i class="fas fa-tags me-2"></i>Nuestros Precios</h2> -->
        <p class="text-muted">Descuentos por volumen - ¡cuantas más fotos, más ahorras!</p>
    </div>
    
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-primary">1 foto</h5>
                            <h4>5,00 €</h4>
                            <small class="text-muted">5,00 €/foto</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-primary">2 fotos</h5>
                            <h4>9,00 €</h4>
                            <small class="text-success">4,50 €/foto</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <div class="badge bg-success mb-2">Más popular</div>
                            <h5 class="text-primary">3 fotos</h5>
                            <h4>12,00 €</h4>
                            <small class="text-success">4,00 €/foto</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-primary">6+ fotos</h5>
                            <h4>desde 20,00 €</h4>
                            <small class="text-success">3,33 €/foto</small>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        7+ fotos: 20,00 € + 3,00 € por cada foto adicional
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Session Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1><i class="fas fa-camera me-2"></i>{{ $session->title }}</h1>
            <p class="text-muted mb-2">
                <i class="fas fa-calendar me-2"></i>{{ $session->date->format('d/m/Y') }}
            </p>
            @if($session->description)
                <p class="lead">{{ $session->description }}</p>
            @endif
        </div>
    </div>

    <!-- Photos Grid -->
    @if($photos->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h3><i class="fas fa-images me-2"></i>Fotos disponibles ({{ $photos->count() }})</h3>
                <p class="text-muted">Haz clic en cualquier foto para verla más grande</p>
            </div>
        </div>

        <div class="row">
            @foreach($photos as $photo)
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <img src="{{ $photo->getUrl('thumb') }}" class="card-img-top photo-thumbnail" 
                         alt="Foto" style="height: 200px; object-fit: cover; cursor: pointer;"
                         data-bs-toggle="modal" data-bs-target="#photoModal"
                          data-photo-url="{{ $photo->getUrl('thumb') }}"
                         data-photo-name="{{ $photo->name }}"
                         data-photo-id="{{ $photo->id }}">
                    <div class="card-body text-center">
                        <button class="btn btn-primary add-to-cart" data-photo-id="{{ $photo->id }}">
                            <i class="fas fa-shopping-cart me-2"></i>Añadir al carrito
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>No hay fotos en esta sesión aún.
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver a inicio
            </a>
        </div>
    @endif
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
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Las fotos sin marca de agua se entregan tras la compra</small>
                    <button id="modalAddToCart" class="btn btn-primary" data-photo-id="">
                        <i class="fas fa-shopping-cart me-2"></i>Añadir al carrito
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.add-to-cart');
    const modalAddButton = document.getElementById('modalAddToCart');
    
    // Función para añadir al carrito
    function addToCart(photoId, button) {
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Añadiendo...';
        
        fetch('/carrito', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                photo_id: photoId,
                action: 'add'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.innerHTML = '<i class="fas fa-check me-2"></i>Añadida';
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
                
                // Actualizar contador del carrito
                document.getElementById('cart-count').textContent = data.cart_count;
                
                // Actualizar también el otro botón (modal/card)
                const otherButtons = document.querySelectorAll(`[data-photo-id="${photoId}"]`);
                otherButtons.forEach(btn => {
                    if (btn !== button) {
                        btn.innerHTML = '<i class="fas fa-check me-2"></i>Añadida';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-success');
                        btn.disabled = true;
                    }
                });
            } else {
                throw new Error('Error al añadir foto');
            }
        })
        .catch(error => {
            alert('Error al añadir foto al carrito');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    // Event listeners para botones en las cards
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const photoId = this.dataset.photoId;
            addToCart(photoId, this);
        });
    });
    
    // Event listener para abrir modal
    document.querySelectorAll('.photo-thumbnail').forEach(img => {
        img.addEventListener('click', function() {
            const photoUrl = this.dataset.photoUrl;
            const photoName = this.dataset.photoName;
            const photoId = this.dataset.photoId;
            
            document.getElementById('modalPhoto').src = photoUrl;
            document.getElementById('photoModalLabel').innerHTML = '<i class="fas fa-image me-2"></i>' + photoName;
            modalAddButton.dataset.photoId = photoId;
            
            // Verificar si ya está en el carrito
            const cardButton = document.querySelector(`.add-to-cart[data-photo-id="${photoId}"]`);
            if (cardButton && cardButton.classList.contains('btn-success')) {
                modalAddButton.innerHTML = '<i class="fas fa-check me-2"></i>Ya en carrito';
                modalAddButton.classList.remove('btn-primary');
                modalAddButton.classList.add('btn-success');
                modalAddButton.disabled = true;
            } else {
                modalAddButton.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Añadir al carrito';
                modalAddButton.classList.remove('btn-success');
                modalAddButton.classList.add('btn-primary');
                modalAddButton.disabled = false;
            }
        });
    });
    
    // Event listener para botón del modal
    modalAddButton.addEventListener('click', function() {
        const photoId = this.dataset.photoId;
        addToCart(photoId, this);
    });
});
</script>
@endsection