@extends('layouts.admin')

@section('title', 'Fotos - ' . $session->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sessions') }}">Sesiones</a></li>
                    <li class="breadcrumb-item active">{{ $session->title }}</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-images me-2"></i>{{ $session->title }}</h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar me-2"></i>{{ $session->date->format('d/m/Y') }} · 
                        {{ $photos->count() }} fotos
                    </p>
                </div>
                <a href="{{ route('session.show', $session->slug) }}" 
                   class="btn btn-outline-primary" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Ver en sitio
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Upload Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cloud-upload-alt me-2"></i>Subir Nueva Foto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.session.upload', $session->id) }}" 
                          method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-md-8">
                            <input type="file" name="photo" class="form-control" 
                                   accept="image/*" required>
                            <div class="form-text">
                                Formatos: JPG, PNG, GIF. Tamaño máximo: 10MB
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-upload me-2"></i>Subir Foto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="row">
        @forelse($photos as $photo)
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card">
                <img src="{{ $photo->getUrl() }}" class="card-img-top" alt="Foto" 
                     style="height: 150px; object-fit: cover;">
                <div class="card-body p-2">
                    <small class="text-muted d-block">{{ $photo->name }}</small>
                    <small class="text-muted">{{ $photo->human_readable_size }}</small>
                </div>
                <div class="card-footer p-2">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="window.open('{{ $photo->getUrl() }}', '_blank')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="confirmDelete('{{ $photo->name }}', '{{ $photo->id }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h4>No hay fotos en esta sesión</h4>
                <p class="text-muted">Sube la primera foto usando el formulario de arriba</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(photoName, photoId) {
    console.log('Intentando eliminar foto:', photoName, 'ID:', photoId);
    
    if (confirm('¿Estás seguro de eliminar la foto "' + photoName + '"?\n\nEsta acción no se puede deshacer.')) {
        // Verificar que tenemos el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('No se encontró el token CSRF');
            alert('Error: No se encontró el token de seguridad. Recarga la página.');
            return;
        }
        
        console.log('Token CSRF encontrado:', csrfToken.getAttribute('content'));
        
        // Mostrar loading en el botón
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        console.log('Haciendo petición a:', '/admin/fotos/' + photoId);
        
        // Realizar petición AJAX
        fetch('/admin/fotos/' + photoId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta recibida:', response.status, response.statusText);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            if (data.success) {
                // Eliminar la tarjeta de la vista
                const card = button.closest('.col-xl-2, .col-lg-3, .col-md-4, .col-sm-6');
                if (card) {
                    card.remove();
                    console.log('Tarjeta eliminada del DOM');
                }
                
                // Mostrar mensaje de éxito
                showAlert('success', 'Foto eliminada correctamente');
                
                // Actualizar contador si existe
                updatePhotoCount();
            } else {
                throw new Error(data.message || 'Error al eliminar la foto');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            showAlert('danger', 'Error al eliminar la foto: ' + error.message);
            
            // Restaurar botón
            button.disabled = false;
            button.innerHTML = originalHTML;
        });
    }
}

function showAlert(type, message) {
    console.log('Mostrando alerta:', type, message);
    
    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insertar alerta al principio del contenido
    const container = document.querySelector('.container-fluid');
    if (container) {
        const firstElement = container.firstElementChild;
        firstElement.insertAdjacentHTML('beforebegin', alertHTML);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
}

function updatePhotoCount() {
    const photoCards = document.querySelectorAll('.col-xl-2, .col-lg-3, .col-md-4, .col-sm-6');
    const countElement = document.querySelector('.text-muted');
    if (countElement && countElement.textContent.includes('fotos')) {
        const newText = countElement.innerHTML.replace(/\d+\s+fotos/, photoCards.length + ' fotos');
        countElement.innerHTML = newText;
        console.log('Contador actualizado a:', photoCards.length, 'fotos');
    }
}

// Verificar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, verificando elementos...');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        console.log('✓ Token CSRF encontrado');
    } else {
        console.error('✗ Token CSRF NO encontrado');
    }
    
    const deleteButtons = document.querySelectorAll('.btn-outline-danger');
    console.log('✓ Botones de eliminar encontrados:', deleteButtons.length);
});
</script>
@endsection