@extends('layouts.app')
@section('title', $hilo->titulo . ' — Foro')

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('foro.categoria', $hilo->categoria) }}" class="text-sm text-gray-500 hover:text-gold-600 mb-4 inline-block">← {{ $hilo->categoria->nombre }}</a>

        {{-- Hilo principal --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-6 mb-6" data-reveal>
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-display font-bold text-navy-900">{{ $hilo->titulo }}</h1>
                    <div class="flex items-center gap-2 mt-2 text-sm text-gray-400">
                        <span>por <strong class="text-navy-900">{{ $hilo->autor->name }}</strong></span>
                        @if($hilo->autor->tipo_verificado)
                            <span class="text-xs text-gold-600 font-bold">✓ {{ ucfirst($hilo->autor->tipo_verificado) }}</span>
                        @endif
                        <span>{{ $hilo->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-gray-700 leading-relaxed whitespace-pre-line">{{ $hilo->contenido_inicial }}</div>
        </div>

        {{-- Respuestas --}}
        <h2 class="text-heading text-navy-900 mb-4">Respuestas ({{ $hilo->respuestas->count() }})</h2>

        <div class="space-y-4 mb-8 stagger-children" data-reveal>
            @forelse($hilo->respuestas as $respuesta)
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-5 hover:shadow-md transition-shadow" id="respuesta-{{ $respuesta->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2 text-sm">
                            <span class="font-semibold text-navy-900">{{ $respuesta->autor->name }}</span>
                            @if($respuesta->autor->tipo_verificado)
                                <span class="text-xs text-gold-600 font-bold">✓ Verificado</span>
                            @endif
                            <span class="text-gray-400">{{ $respuesta->created_at->diffForHumans() }}</span>
                        </div>
                        @auth
                            @if(Auth::user()->isAdmin() || Auth::id() === $respuesta->autor_id)
                                <form action="{{ route('foro.respuesta.destroy', $respuesta) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Eliminar</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <p class="mt-3 text-gray-700 whitespace-pre-line">{{ $respuesta->contenido }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8 bg-white/60 backdrop-blur-sm rounded-xl">No hay respuestas aún. Sé el primero en responder.</p>
            @endforelse
        </div>

        {{-- Formulario de respuesta --}}
        @auth
            @if($hilo->status === 'abierto')
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-6" data-reveal>
                    <h3 class="font-semibold text-navy-900 mb-4">Responder</h3>
                    <form action="{{ route('foro.responder', $hilo) }}" method="POST">
                        @csrf
                        <textarea name="contenido" rows="4" required minlength="3" placeholder="Escribe tu respuesta..." class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm "></textarea>
                        <button type="submit" class="mt-3 bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-6 py-2 rounded-md text-sm font-bold shadow-sm transition-all">Publicar Respuesta</button>
                    </form>
                </div>
            @else
                <p class="text-center text-gray-500 py-4 bg-white/60 backdrop-blur-sm rounded-xl">Este hilo está cerrado. No se pueden agregar nuevas respuestas.</p>
            @endif
        @else
            <p class="text-center text-gray-500 py-4 bg-white/60 backdrop-blur-sm rounded-xl"><a href="{{ route('login') }}" class="text-gold-600 hover:underline">Inicia sesión</a> para responder.</p>
        @endauth
    </div>
@endsection
