@extends('layouts.app')
@section('title', $categoria->nombre . ' — Foro')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('foro.index') }}" class="text-sm text-gray-500 hover:text-gold-600">← Foro</a>
            <h1 class="text-3xl font-merriweather font-bold text-navy-900 mt-1">{{ $categoria->nombre }}</h1>
        </div>
        @auth
            <a href="{{ route('foro.create-hilo', $categoria) }}" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-4 py-2 rounded-md text-sm font-bold transition-colors">Nuevo Hilo</a>
        @else
            <a href="{{ route('login') }}" class="text-gold-600 hover:text-gold-500 text-sm font-medium">Inicia sesión para crear un hilo</a>
        @endauth
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y">
        @forelse($hilos as $hilo)
            <a href="{{ route('foro.hilo', $hilo) }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors {{ $hilo->fijado ? 'bg-gold-50/50' : '' }}">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        @if($hilo->fijado)
                            <svg class="w-4 h-4 text-gold-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6l4-2 4 2 4-2 4 2v10l-4 2-4-2-4 2-4-2V6z"/></svg>
                        @endif
                        <h2 class="font-semibold text-navy-900">{{ $hilo->titulo }}</h2>
                        @if($hilo->status === 'cerrado')
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Cerrado</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                        <span>por {{ $hilo->autor->name }}</span>
                        <span>{{ $hilo->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="text-right text-sm text-gray-500 ml-4">
                    <p class="font-medium">{{ $hilo->respuestas_count }}</p>
                    <p class="text-xs">respuestas</p>
                </div>
            </a>
        @empty
            <p class="text-gray-500 text-center py-12">No hay hilos en esta categoría. ¡Sé el primero en crear uno!</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $hilos->links() }}</div>
@endsection
