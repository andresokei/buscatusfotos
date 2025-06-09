@extends('layouts.admin')

@section('title', 'Nueva Sesión - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sessions') }}">Sesiones</a></li>
                    <li class="breadcrumb-item active">Nueva Sesión</li>
                </ol>
            </nav>
            
            <h1><i class="fas fa-plus me-2"></i>Crear Nueva Sesión</h1>
            <p class="text-muted">Completa los datos y sube fotos de la nueva sesión</p>
        </div>
    </div>

    <form action="{{ route('admin.session.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Datos de la Sesión -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-photo-video me-2"></i>Datos de la Sesión</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-2"></i>Título de la Sesión *
                            </label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   placeholder="Ej: Sesión Playa del Sardinero" 
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">
                                <i class="fas fa-calendar me-2"></i>Fecha *
                            </label>
                            <input type="date" name="date" id="date" class="form-control" 
                                   value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Descripción (opcional)
                            </label>
                            <textarea name="description" id="description" class="form-control" rows="3" 
                                      placeholder="Describe la sesión, condiciones del mar, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="listed" id="listed" class="form-check-input" 
                                       {{ old('listed', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="listed">
                                    <i class="fas fa-eye me-2"></i>Visible en el sitio público
                                </label>
                                <div class="form-text">Si no está marcado, la sesión estará oculta</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subir Fotos -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Fotos de la Sesión</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="photos" class="form-label">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Seleccionar Fotos (opcional)
                            </label>
                            <input type="file" name="photos[]" id="photos" class="form-control" 
                                   accept="image/*" multiple>
                            <div class="form-text">
                                Puedes seleccionar múltiples fotos. Formatos: JPG, PNG, GIF. Máximo 10MB por foto.
                            </div>
                        </div>
                        
                        <div id="photo-preview" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.sessions') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Crear Sesión y Subir Fotos
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = '';
    
    if (e.target.files.length > 0) {
        Array.from(e.target.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted">${file.name}</small>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
                preview.appendChild(col);
            }
        });
    }
});
</script>
@endsection