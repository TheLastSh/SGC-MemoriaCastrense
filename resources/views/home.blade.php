@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <div class="bg-navy-900 rounded-xl p-8 mb-10 shadow-lg border border-navy-800">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-merriweather font-bold text-white mb-4">Archivo Histórico Militar de La Vela de Coro</h1>
            <p class="text-gray-300 text-lg mb-6">Preservación, catalogación y divulgación de la historia militar del Estado Falcón, Venezuela. Un espacio para historiadores, cultores y cronistas.</p>
            <div class="flex justify-center gap-8 text-center">
                <div>
                    <p class="text-3xl font-bold text-gold-500">{{ $totalArticulos }}</p>
                    <p class="text-gray-400 text-sm">Artículos</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gold-500">{{ $totalMedia }}</p>
                    <p class="text-gray-400 text-sm">Archivos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Artículos Recientes --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-merriweather font-bold text-navy-900">Artículos Recientes</h2>
            <a href="{{ route('articulos.index') }}" class="text-gold-600 hover:text-gold-500 font-medium text-sm">Ver todos →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($recientes as $articulo)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    @if($articulo->portada_url)
                        <img src="{{ $articulo->portada_url }}" alt="{{ $articulo->titulo }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-navy-800 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gold-500/50" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <span class="text-xs text-gold-600 font-bold uppercase">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                        <h3 class="font-merriweather font-bold text-navy-900 mt-1">{{ $articulo->titulo }}</h3>
                        <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $articulo->extracto }}</p>
                        <a href="{{ route('articulos.show', $articulo) }}" class="inline-block mt-3 text-sm text-gold-600 hover:text-gold-500 font-medium">Leer más →</a>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-gray-500 text-center py-8">No hay artículos publicados aún.</p>
            @endforelse
        </div>
    </section>

    {{-- Más Visitados --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-merriweather font-bold text-navy-900">Más Visitados</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($destacados as $articulo)
                <div class="flex bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    @if($articulo->portada_url)
                        <img src="{{ $articulo->portada_url }}" alt="" class="w-32 h-32 object-cover flex-shrink-0">
                    @else
                        <div class="w-32 h-32 bg-navy-800 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gold-500/50" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        </div>
                    @endif
                    <div class="p-4 flex flex-col justify-center">
                        <span class="text-xs text-gold-600 font-bold uppercase">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                        <h3 class="font-merriweather font-bold text-navy-900">{{ $articulo->titulo }}</h3>
                        <p class="text-gray-400 text-xs mt-1">{{ $articulo->visitas }} visitas</p>
                        <a href="{{ route('articulos.show', $articulo) }}" class="text-sm text-gold-600 hover:text-gold-500 font-medium mt-2">Leer más →</a>
                    </div>
                </div>
            @empty
                <p class="col-span-2 text-gray-500">Sin contenido destacado.</p>
            @endforelse
        </div>
    </section>

    {{-- Últimos hilos del foro --}}
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-merriweather font-bold text-navy-900">Foro — Últimos Hilos</h2>
            <a href="{{ route('foro.index') }}" class="text-gold-600 hover:text-gold-500 font-medium text-sm">Ir al foro →</a>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y">
            @forelse($ultimosHilos as $hilo)
                <a href="{{ route('foro.hilo', $hilo) }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div>
                        <span class="text-xs text-gold-600 font-bold uppercase">{{ $hilo->categoria->nombre }}</span>
                        <h3 class="font-medium text-navy-900">{{ $hilo->titulo }}</h3>
                        <p class="text-xs text-gray-400">por {{ $hilo->autor->name }}</p>
                    </div>
                    <span class="text-sm text-gray-500">{{ $hilo->respuestas_count }} respuestas</span>
                </a>
            @empty
                <p class="text-gray-500 text-center py-8">No hay hilos en el foro aún. ¡Sé el primero!</p>
            @endforelse
        </div>
    </section>
@endsection
