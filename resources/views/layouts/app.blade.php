<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Archivo Histórico Militar de La Vela de Coro — Preservación y difusión de la historia militar venezolana.">
    <meta property="og:title" content="La Vela de Coro — Archivo Histórico Militar">
    <meta property="og:description" content="Plataforma de preservación y difusión de la historia militar de La Vela de Coro, estado Falcón, Venezuela.">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'La Vela de Coro — Archivo Histórico Militar')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-parchment-50 text-slate-800 font-body antialiased flex flex-col min-h-screen">

    <nav class="sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 group">
                        <img src="{{ asset('img/logo.png') }}" alt="La Vela de Coro" class="h-9 w-9 group-hover:opacity-80 transition-opacity">
                        <div class="flex flex-col">
                            <span class="text-white font-display font-bold text-xl leading-tight">La Vela de Coro</span>
                            <span class="text-gold-400 text-xs tracking-wider uppercase font-semibold">Archivo Histórico Militar</span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white hover:bg-navy-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors">Inicio</a>
                    <a href="{{ route('articulos.index') }}" class="text-gray-300 hover:text-white hover:bg-navy-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors">Artículos</a>
                    <a href="{{ route('media.index') }}" class="text-gray-300 hover:text-white hover:bg-navy-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors">Biblioteca</a>
                    <a href="{{ route('foro.index') }}" class="text-gray-300 hover:text-white hover:bg-navy-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors">Foro</a>

                    @auth
                        @if(Auth::user()->isPublicador())
                            <a href="{{ route('articulos.create') }}" class="bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Nuevo Artículo
                            </a>
                        @endif

                            <a href="{{ route('perfil.show') }}" class="ml-4 text-gray-400 hover:text-gold-500 transition-colors active:scale-[0.97]" title="Mi Perfil">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </a>

                        <div class="relative flex items-center gap-4 border-l border-navy-700/50 pl-4">
                            <div class="flex flex-col text-right">
                                <span class="text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gold-500 font-bold uppercase tracking-wider">
                                    @if(Auth::user()->tipo_verificado)
                                        {{ Auth::user()->tipo_verificado }}
                                    @else
                                        {{ Auth::user()->role }}
                                    @endif
                                </span>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="ml-2">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors active:scale-[0.97]" title="Cerrar Sesión">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="border-l border-navy-700/50 pl-4 flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="bg-navy-800/50 border border-gold-600/30 hover:border-gold-500/50 text-gold-500 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-all active:scale-[0.97]">Crear Cuenta</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50/90 backdrop-blur-sm border-l-4 border-green-500 p-4 rounded-r-md shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-navy-950/95 border-t border-navy-800/50 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" alt="" class="h-8 w-8 opacity-80">
                <div>
                    <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Archivo Histórico Militar de La Vela de Coro.</p>
                    <p class="text-gray-500 text-xs">Preservación y difusión de la historia militar del Estado Falcón, Venezuela.</p>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
</body>
</html>
