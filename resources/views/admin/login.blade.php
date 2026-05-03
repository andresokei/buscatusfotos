<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Admin — BuscaTusFotos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 flex flex-col">

    <main class="flex-1 flex items-center justify-center px-6 py-16">
        <div class="w-full max-w-sm">
            <div class="text-center mb-10">
                <p class="meta mb-3">BuscaTusFotos</p>
                <h1 class="font-display text-3xl text-ink">Panel de administración</h1>
            </div>

            <div class="bg-paper border border-zinc-200 p-8">
                @if(session('error'))
                    <div class="mb-6 border-l-2 border-accent bg-zinc-50 px-4 py-3">
                        <p class="text-sm text-ink">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="username" class="label">Usuario</label>
                        <input type="text" name="username" id="username" class="input"
                               placeholder="Tu usuario" required autofocus>
                    </div>
                    <div>
                        <label for="password" class="label">Contraseña</label>
                        <input type="password" name="password" id="password" class="input"
                               placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        Acceder
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="meta hover:text-ink transition">
                    ← Volver al sitio
                </a>
            </div>
        </div>
    </main>

</body>
</html>
