<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BuscaTusFotos')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    <header class="border-b border-zinc-200 bg-paper sticky top-0 z-40">
        <div class="container-x flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="font-display text-xl tracking-tightish text-ink">
                BuscaTusFotos
            </a>

            <nav class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="meta hover:text-ink transition">
                    Inicio
                </a>
                <a href="{{ route('cart.view') }}" class="meta hover:text-ink transition relative inline-flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/>
                    </svg>
                    Carrito
                    @if(count(session('cart', [])) > 0)
                        <span id="cart-count" class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-accent text-paper text-[10px] font-medium">
                            {{ count(session('cart', [])) }}
                        </span>
                    @else
                        <span id="cart-count" class="hidden">0</span>
                    @endif
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-1 py-12 lg:py-20">
        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 mt-20 py-10">
        <div class="container-x flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="meta">© {{ date('Y') }} BuscaTusFotos</p>
            <p class="meta">Encuentra tus fotos de surf</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
