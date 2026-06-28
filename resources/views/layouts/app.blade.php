<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Archivo Histórico Militar de La Vela de Coro — Preservación y difusión de la historia militar venezolana.">
    <meta property="og:title" content="La Vela de Coro — Archivo Histórico Militar">
    <meta property="og:description" content="Plataforma de preservación y difusión de la historia militar de La Vela de Coro, estado Falcón, Venezuela.">
    <meta property="og:type" content="website">
    <title>@yield('title', 'La Vela de Coro — Archivo Histórico Militar')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-slate-800 font-inter antialiased flex flex-col min-h-screen">

    <nav class="bg-navy-900 border-b-4 border-gold-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3">
                        <svg class="w-10 h-10 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L5 10.274zm10 0l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L15 10.274z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex flex-col">
                            <span class="text-white font-merriweather font-bold text-xl leading-tight">La Vela de Coro</span>
                            <span class="text-gold-400 text-xs tracking-wider uppercase font-semibold">Archivo Histórico Militar</span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white hover:bg-navy-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">Inicio</a>
                    <a href="{{ route('articulos.index') }}" class="text-gray-300 hover:text-white hover:bg-navy-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">Artículos</a>
                    <a href="{{ route('media.index') }}" class="text-gray-300 hover:text-white hover:bg-navy-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">Biblioteca</a>
                    <a href="{{ route('foro.index') }}" class="text-gray-300 hover:text-white hover:bg-navy-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">Foro</a>

                    @auth
                        @if(Auth::user()->isPublicador())
                            <a href="{{ route('articulos.create') }}" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Nuevo Artículo
                            </a>
                        @endif

                        <div class="relative ml-4 flex items-center gap-4 border-l border-navy-700 pl-4">
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
                                <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors" title="Cerrar Sesión">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="border-l border-navy-700 pl-4 flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="bg-navy-800 border border-gold-600/30 hover:border-gold-500 text-gold-500 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-colors">Crear Cuenta</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-md shadow-sm" role="alert">
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

    <footer class="bg-navy-950 border-t border-navy-800 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Archivo Histórico Militar de La Vela de Coro.</p>
            <p class="mt-1 text-xs">Preservación y difusión de la historia militar del Estado Falcón, Venezuela.</p>
        </div>
    </footer>

    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
</body>
</html>
