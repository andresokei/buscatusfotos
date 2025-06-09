@extends('layouts.admin')

@section('title', 'Sesiones - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-photo-video me-2"></i>Gestionar Sesiones</h1>
            <p class="text-muted mb-0">Administra las sesiones de fotos</p>
        </div>
        <a href="{{ route('admin.session.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nueva Sesión
        </a>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Sessions Grid -->
    <div class="row">
        @forelse($sessions as $session)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">{{ $session->title }}</h5>
                        <span class="badge {{ $session->listed ? 'bg-success' : 'bg-secondary' }}">
                            {{ $session->listed ? 'Visible' : 'Oculta' }}
                        </span>
                    </div>
                    
                    <p class="card-text">
                        <i class="fas fa-calendar me-2"></i>{{ $session->date->format('d/m/Y') }}
                    </p>
                    
                    <p class="card-text">
                        <i class="fas fa-images me-2"></i>{{ $session->getMedia('photos')->count() }} fotos
                    </p>
                    
                    @if($session->description)
                        <p class="card-text text-muted">{{ Str::limit($session->description, 80) }}</p>
                    @endif
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.session.photos', $session->id) }}" class="btn btn-primary">
                            <i class="fas fa-images me-2"></i>Gestionar Fotos
                        </a>
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('session.show', $session->slug) }}" 
                               class="btn btn-outline-info btn-sm" target="_blank">
                                <i class="fas fa-eye me-2"></i>Ver en sitio
                            </a>
                            <button class="btn btn-outline-danger btn-sm" 
                                    onclick="confirmDelete({{ $session->id }}, '{{ $session->title }}')" 
                                    title="Eliminar sesión">
                                <i class="fas fa-trash me-2"></i>Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-photo-video fa-3x text-muted mb-3"></i>
                <h3>No hay sesiones</h3>
                <p class="text-muted">Crea tu primera sesión de fotos</p>
                <a href="{{ route('admin.session.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nueva Sesión
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(sessionId, sessionTitle) {
    if (confirm('¿Estás seguro de eliminar la sesión "' + sessionTitle + '"?\n\nEsta acción eliminará también todas las fotos asociadas y no se puede deshacer.')) {
        // Crear formulario dinámico para DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/sesiones/' + sessionId;
        
        // Token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Method DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection