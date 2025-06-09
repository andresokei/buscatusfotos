<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - BuscaTusFotos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white text-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>Panel de Administración
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>Usuario:
                                </label>
                                <input type="text" name="username" class="form-control" 
                                       placeholder="Introduce tu usuario" required autofocus>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña:
                                </label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Introduce tu contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Acceder
                            </button>
                        </form>

                        <div class="mt-4 text-center">
                            <div class="alert alert-info mb-3">
                                <small>
                                    <strong>Demo:</strong> admin / password123
                                </small>
                            </div>
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Volver al sitio web
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>