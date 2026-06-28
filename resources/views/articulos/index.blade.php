@extends('layouts.app')
@section('title', 'Artículos — La Vela de Coro')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8" data-reveal>
        <h1 class="text-heading text-navy-900">Artículos Históricos</h1>
        @auth
            @if(Auth::user()->isPublicador())
                <a href="{{ route('articulos.create') }}" class="mt-3 sm:mt-0 bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-all">Nuevo Artículo</a>
            @endif
        @endauth
    </div>

    {{-- Filtros --}}
    <form method="GET" class="mb-8 flex flex-wrap gap-4 bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-parchment-100/50 shadow-sm" data-reveal>
        <input type="text" name="buscar" placeholder="Buscar artículos..." value="{{ request('buscar') }}" class="flex-1 min-w-[200px] bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm ">
        <select name="categoria" class="bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm ">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-navy-900/90 hover:bg-navy-800 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all active:scale-[0.97]">Filtrar</button>
    </form>

    {{-- Grilla --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children" data-reveal>
        @forelse($articulos as $articulo)
            <div class="group/card bg-white/80 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 border border-parchment-100/50 overflow-hidden card-accent-teal">
                @if($articulo->portada_url)
                    <img src="{{ $articulo->portada_url }}" alt="{{ $articulo->titulo }}" class="w-full h-44 object-cover">
                @else
                    <div class="w-full h-44 bg-navy-800/10 flex items-center justify-center">
                        <svg class="w-16 h-16 text-navy-800/20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs text-gold-600 font-bold uppercase tracking-widest">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                        <span class="text-xs text-gray-400">• {{ $articulo->fecha_publicacion?->format('d/m/Y') }}</span>
                    </div>
                    <h2 class="font-display font-bold text-navy-900 text-lg mb-2">{{ $articulo->titulo }}</h2>
                    <p class="text-gray-500 text-sm line-clamp-3">{{ $articulo->extracto ?? Str::limit(strip_tags($articulo->contenido), 150) }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-gray-400">Por {{ $articulo->autor->name }}</span>
                        <a href="{{ route('articulos.show', $articulo) }}" class="text-gold-600 hover:text-gold-500 font-medium text-sm">Leer más →</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white/60 backdrop-blur-sm rounded-xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <p class="text-gray-500 text-lg">No se encontraron artículos.</p>
                @if(Auth::user()?->isPublicador())
                    <a href="{{ route('articulos.create') }}" class="inline-block mt-4 text-gold-600 hover:text-gold-500 font-medium">Publica el primero →</a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $articulos->links() }}
    </div>
@endsection
