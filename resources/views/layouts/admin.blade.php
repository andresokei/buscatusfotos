<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — BuscaTusFotos')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-zinc-50">

    <header class="border-b border-zinc-200 bg-paper sticky top-0 z-40">
        <div class="container-x flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.index') }}" class="font-display text-lg tracking-tightish text-ink">
                    BuscaTusFotos<span class="text-accent">·</span><span class="meta align-middle ml-1">Admin</span>
                </a>

                <nav class="hidden md:flex items-center gap-6 ml-4">
                    <a href="{{ route('admin.index') }}"
                       class="meta hover:text-ink transition border-b-2 pb-1 {{ request()->routeIs('admin.index') ? 'border-ink text-ink' : 'border-transparent' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.sessions') }}"
                       class="meta hover:text-ink transition border-b-2 pb-1 {{ request()->routeIs('admin.sessions*') || request()->routeIs('admin.session.*') ? 'border-ink text-ink' : 'border-transparent' }}">
                        Sesiones
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="meta hover:text-ink transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    Ver sitio
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="meta hover:text-accent transition">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 py-10 lg:py-14">
        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 mt-12 py-6 bg-paper">
        <div class="container-x">
            <p class="meta">Panel de administración · BuscaTusFotos</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
