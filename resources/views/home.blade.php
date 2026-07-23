@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <div class="relative rounded-xl p-8 md:p-12 mb-10 shadow-lg border border-navy-800/50 overflow-hidden animate-gradient-shift"
         style="background: linear-gradient(135deg, #0f172a, #1e293b, #0f172a, #134e4a); background-size: 400% 400%;">
        <div class="hero-topo"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(20,184,166,0.08)_0%,_transparent_60%)] pointer-events-none"></div>
        <div class="absolute -right-16 -top-16 w-64 h-64 opacity-[0.06] pointer-events-none">
            <img src="{{ asset('img/logo.png') }}" alt="" class="w-full h-full">
        </div>
        <div class="max-w-3xl mx-auto text-center relative z-10">
            <div class="flex items-center justify-center gap-3 mb-5" data-reveal>
                <img src="{{ asset('img/logo.png') }}" alt="" class="w-14 h-14 animate-float">
            </div>
            <h1 class="text-display text-white mb-4" data-reveal>
                SGC Memoria Castrense<br>
                <span class="text-gradient-gold">La Vela de Coro</span>
            </h1>
            <p class="text-gray-300 text-base sm:text-lg mb-8 max-w-2xl mx-auto" data-reveal>Preservación, catalogación y divulgación de la historia militar del Estado Falcón, Venezuela. Un espacio abierto a historiadores, cultores y cronistas.</p>
            <div class="flex justify-center gap-4 sm:gap-8 text-center flex-wrap" data-reveal>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-8 py-5 border border-white/10 hover:bg-white/15 transition-colors min-w-[140px]">
                    <svg class="w-6 h-6 text-gold-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <p class="text-3xl font-bold text-gold-500" data-counter="{{ $totalArticulos }}">0</p>
                    <p class="text-gray-400 text-sm mt-1 font-medium tracking-wide uppercase">Artículos</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-8 py-5 border border-white/10 hover:bg-white/15 transition-colors min-w-[140px]">
                    <svg class="w-6 h-6 text-gold-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-3xl font-bold text-gold-500" data-counter="{{ $totalMedia }}">0</p>
                    <p class="text-gray-400 text-sm mt-1 font-medium tracking-wide uppercase">Archivos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Artículos Recientes --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-2" data-reveal>
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-gold-600">Contenido</span>
                <h2 class="text-heading text-navy-900 mt-1">Artículos Recientes</h2>
            </div>
            <a href="{{ route('articulos.index') }}" class="text-gold-600 hover:text-gold-500 font-medium text-sm">Ver todos →</a>
        </div>
        <div class="section-divider" data-reveal>
            <svg class="w-4 h-4 text-gold-500/60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stagger-children" data-reveal>
            @forelse($recientes as $articulo)
                <div class="group/card bg-white/80 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 border border-parchment-100/50 overflow-hidden card-accent-teal">
                    @if($articulo->portada_url)
                        <img src="{{ $articulo->portada_url }}" alt="{{ $articulo->titulo }}" class="w-full h-40 object-cover group-hover/card:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-40 bg-navy-800/5 flex items-center justify-center">
                            <svg class="w-12 h-12 text-navy-800/15" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <span class="text-xs text-gold-600 font-bold uppercase tracking-[0.15em]">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                        <h3 class="font-display font-bold text-navy-900 mt-1.5 leading-snug">{{ $articulo->titulo }}</h3>
                        <p class="text-gray-500 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $articulo->extracto }}</p>
                        <a href="{{ route('articulos.show', $articulo) }}" class="inline-block mt-3 text-sm text-gold-600 hover:text-gold-500 font-semibold">Leer más →</a>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-gray-500 text-center py-8 bg-white/60 backdrop-blur-sm rounded-xl">No hay artículos publicados aún.</p>
            @endforelse
        </div>
    </section>

    {{-- Más Visitados --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-2" data-reveal>
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-gold-600">Destacados</span>
                <h2 class="text-heading text-navy-900 mt-1">Más Visitados</h2>
            </div>
        </div>
        <div class="section-divider" data-reveal>
            <svg class="w-4 h-4 text-gold-500/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger-children" data-reveal>
            @forelse($destacados as $articulo)
                <div class="flex bg-white/80 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 border border-parchment-100/50 overflow-hidden group/card card-accent-gold">
                    @if($articulo->portada_url)
                        <img src="{{ $articulo->portada_url }}" alt="" class="w-32 h-32 object-cover flex-shrink-0 group-hover/card:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-32 h-32 bg-navy-800/5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-navy-800/15" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        </div>
                    @endif
                    <div class="p-4 flex flex-col justify-center flex-1">
                        <span class="text-xs text-gold-600 font-bold uppercase tracking-[0.15em]">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                        <h3 class="font-display font-bold text-navy-900 mt-1 leading-snug">{{ $articulo->titulo }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-400 text-xs">{{ $articulo->visitas }} visitas</span>
                        </div>
                        <a href="{{ route('articulos.show', $articulo) }}" class="text-sm text-gold-600 hover:text-gold-500 font-semibold mt-2">Leer más →</a>
                    </div>
                </div>
            @empty
                <p class="col-span-2 text-gray-500 bg-white/60 backdrop-blur-sm rounded-xl px-6 py-8 text-center">Sin contenido destacado.</p>
            @endforelse
        </div>
    </section>

    {{-- Últimos hilos del foro --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-2" data-reveal>
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-gold-600">Comunidad</span>
                <h2 class="text-heading text-navy-900 mt-1">Foro — Últimos Hilos</h2>
            </div>
            <a href="{{ route('foro.index') }}" class="text-gold-600 hover:text-gold-500 font-medium text-sm">Ir al foro →</a>
        </div>
        <div class="section-divider" data-reveal>
            <svg class="w-4 h-4 text-gold-500/60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd"/></svg>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 divide-y divide-parchment-100/50" data-reveal>
            @forelse($ultimosHilos as $hilo)
                <a href="{{ route('foro.hilo', $hilo) }}" class="flex items-center justify-between px-6 py-4 hover:bg-parchment-50/80 hover:pl-8 transition-all {{ $hilo->fijado ? 'bg-gold-50/50' : '' }}">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            @if($hilo->fijado)
                                <svg class="w-4 h-4 text-gold-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6l4-2 4 2 4-2 4 2v10l-4 2-4-2-4 2-4-2V6z"/></svg>
                            @endif
                            <h3 class="font-semibold text-navy-900">{{ $hilo->titulo }}</h3>
                        </div>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                            <span class="text-gold-600 font-bold uppercase tracking-[0.1em]">{{ $hilo->categoria->nombre }}</span>
                            <span>•</span>
                            <span>por {{ $hilo->autor->name }}</span>
                        </div>
                    </div>
                    <div class="text-right ml-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span class="text-sm text-gray-500 font-medium">{{ $hilo->respuestas_count }}</span>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-center py-8">No hay hilos en el foro aún. ¡Sé el primero!</p>
            @endforelse
        </div>
    </section>
@endsection
