@extends('layouts.app')
@section('title', 'Biblioteca de Medios — La Vela de Coro')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8" data-reveal>
        <h1 class="text-heading text-navy-900">Biblioteca de Medios</h1>
        @auth
            @if(Auth::user()->isPublicador())
                <a href="{{ route('media.create') }}" class="mt-3 sm:mt-0 bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-all">Subir Archivo</a>
            @endif
        @endauth
    </div>

    <form method="GET" class="mb-8 flex flex-wrap gap-4 bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-parchment-100/50 shadow-sm" data-reveal>
        <input type="text" name="buscar" placeholder="Buscar en la biblioteca..." value="{{ request('buscar') }}" class="flex-1 min-w-[200px] bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm ">
        <select name="coleccion" class="bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm ">
            <option value="">Todo</option>
            <option value="imagen" {{ request('coleccion') == 'imagen' ? 'selected' : '' }}>Imágenes</option>
            <option value="video" {{ request('coleccion') == 'video' ? 'selected' : '' }}>Videos</option>
            <option value="documento" {{ request('coleccion') == 'documento' ? 'selected' : '' }}>Documentos</option>
        </select>
        <button type="submit" class="bg-navy-900/90 hover:bg-navy-800 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all active:scale-[0.97]">Filtrar</button>
    </form>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 stagger-children" data-reveal>
        @forelse($media as $item)
            <a href="{{ route('media.show', $item) }}" class="group/card bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 overflow-hidden hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 card-accent-teal">
                @if(str_starts_with($item->mime_type, 'image/'))
                    <div class="aspect-square bg-navy-800/10 overflow-hidden">
                        <img src="{{ $item->filename }}" alt="{{ $item->alt_text ?? $item->nombre_original }}" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                    </div>
                @elseif(str_starts_with($item->mime_type, 'video/'))
                    <div class="aspect-square bg-navy-900 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gold-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                    </div>
                @else
                    <div class="aspect-square bg-navy-800 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gold-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    </div>
                @endif
                <div class="p-2">
                    <p class="text-xs text-navy-900 font-medium truncate">{{ $item->nombre_original }}</p>
                    <p class="text-xs text-gray-400">{{ round($item->peso_kb / 1024, 1) }} MB</p>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16 bg-white/60 backdrop-blur-sm rounded-xl">
                <p class="text-gray-500">No hay archivos en la biblioteca.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $media->links() }}</div>
@endsection
