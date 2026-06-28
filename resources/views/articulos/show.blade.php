@extends('layouts.app')
@section('title', $articulo->titulo . ' — La Vela de Coro')

@section('content')
    <article class="max-w-4xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                <a href="{{ route('articulos.index') }}" class="hover:text-gold-600">← Volver</a>
                <span>•</span>
                <span class="text-gold-600 font-bold uppercase">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                <span>•</span>
                <span>{{ $articulo->fecha_publicacion?->format('d F, Y') }}</span>
            </div>
            <h1 class="text-4xl font-merriweather font-bold text-navy-900 leading-tight">{{ $articulo->titulo }}</h1>
            <div class="flex items-center gap-3 mt-4">
                <div class="w-10 h-10 rounded-full bg-navy-800 flex items-center justify-center text-gold-500 font-bold text-sm">
                    {{ substr($articulo->autor->name, 0, 2) }}
                </div>
                <div>
                    <p class="font-semibold text-navy-900">{{ $articulo->autor->name }}</p>
                    @if($articulo->autor->tipo_verificado)
                        <span class="text-xs text-gold-600 font-bold">✓ {{ ucfirst($articulo->autor->tipo_verificado) }} Verificado</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Portada --}}
        @if($articulo->portada_url)
            <img src="{{ $articulo->portada_url }}" alt="{{ $articulo->titulo }}" class="w-full h-80 object-cover rounded-xl mb-8 shadow-md">
        @endif

        {{-- Extracto --}}
        @if($articulo->extracto)
            <p class="text-xl text-gray-600 font-merriweather italic mb-6 leading-relaxed">{{ $articulo->extracto }}</p>
        @endif

        {{-- Contenido --}}
        <div class="prose prose-lg max-w-none prose-headings:font-merriweather prose-headings:text-navy-900 prose-a:text-gold-600 mb-12">
            {!! $articulo->contenido !!}
        </div>

        {{-- Tags --}}
        @if($articulo->tags->count())
            <div class="flex flex-wrap gap-2 mb-8">
                @foreach($articulo->tags as $tag)
                    <span class="bg-navy-100 text-navy-800 px-3 py-1 rounded-full text-sm font-medium">{{ $tag->nombre }}</span>
                @endforeach
            </div>
        @endif

        {{-- Acciones --}}
        @auth
            @if(Auth::user()->isAdmin() || Auth::id() === $articulo->author_id)
                <div class="flex gap-3 mb-8">
                    <a href="{{ route('articulos.edit', $articulo) }}" class="bg-navy-900 hover:bg-navy-800 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Editar</a>
                    <form action="{{ route('articulos.destroy', $articulo) }}" method="POST" onsubmit="return confirm('¿Archivar este artículo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">Archivar</button>
                    </form>
                </div>
            @endif
        @endauth

        {{-- Comentarios --}}
        <section class="border-t border-gray-200 pt-8">
            <h2 class="text-2xl font-merriweather font-bold text-navy-900 mb-6">Comentarios ({{ $articulo->comentarios->count() }})</h2>

            @auth
                <form action="{{ route('comentarios.store', $articulo) }}" method="POST" class="mb-8">
                    @csrf
                    <textarea name="contenido" rows="3" placeholder="Deja un comentario..." class="w-full border border-gray-300 rounded-lg p-4 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500" maxlength="1000" required></textarea>
                    <button type="submit" class="mt-2 bg-gold-600 hover:bg-gold-500 text-navy-950 px-6 py-2 rounded-md text-sm font-bold transition-colors">Publicar Comentario</button>
                </form>
            @else
                <p class="mb-8 text-gray-500"><a href="{{ route('login') }}" class="text-gold-600 hover:underline">Inicia sesión</a> para dejar un comentario.</p>
            @endauth

            <div class="space-y-6">
                @forelse($articulo->comentarios as $comentario)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-semibold text-navy-900">{{ $comentario->user->name }}</span>
                                @if($comentario->user->tipo_verificado)
                                    <span class="text-xs text-gold-600 font-bold ml-1">✓ Verificado</span>
                                @endif
                                <span class="text-gray-400 text-xs ml-2">{{ $comentario->created_at->diffForHumans() }}</span>
                            </div>
                            @auth
                                @if(Auth::user()->isAdmin() || Auth::id() === $comentario->user_id)
                                    <form action="{{ route('comentarios.destroy', $comentario) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Eliminar</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <p class="mt-2 text-gray-700">{{ $comentario->contenido }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-6">No hay comentarios aún. Sé el primero en comentar.</p>
                @endforelse
            </div>
        </section>
    </article>
@endsection
