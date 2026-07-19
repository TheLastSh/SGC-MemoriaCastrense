@extends('layouts.app')
@section('title', $media->nombre_original . ' — Biblioteca')

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('media.index') }}" class="text-sm text-gray-500 hover:text-gold-600 mb-4 inline-block">← Volver a la Biblioteca</a>

        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 overflow-hidden">
            @if(str_starts_with($media->mime_type, 'image/'))
                <img src="{{ $media->filename }}" alt="{{ $media->alt_text ?? $media->nombre_original }}" class="w-full max-h-[70vh] object-contain bg-navy-800/10">
            @elseif(str_starts_with($media->mime_type, 'video/'))
                <video controls class="w-full max-h-[70vh] bg-black">
                    <source src="{{ $media->filename }}" type="{{ $media->mime_type }}">
                </video>
            @else
                <div class="flex items-center justify-center h-64 bg-navy-800/10">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-navy-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        <a href="{{ $media->filename }}" target="_blank" class="text-gold-600 hover:underline font-medium">Descargar {{ $media->nombre_original }}</a>
                    </div>
                </div>
            @endif

            <div class="p-6">
                <h1 class="text-2xl font-display font-bold text-navy-900">{{ $media->nombre_original }}</h1>
                @if($media->alt_text)
                    <p class="text-gray-500 mt-1">{{ $media->alt_text }}</p>
                @endif
                @if($media->descripcion)
                    <p class="text-gray-700 mt-3">{{ $media->descripcion }}</p>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-sm">
                    <div class="min-w-0"><span class="text-gray-400">Tipo</span><p class="font-medium truncate">{{ $media->mime_type }}</p></div>
                    <div class="min-w-0"><span class="text-gray-400">Peso</span><p class="font-medium truncate">{{ round($media->peso_kb / 1024, 2) }} MB</p></div>
                    @if($media->ancho)<div class="min-w-0"><span class="text-gray-400">Dimensiones</span><p class="font-medium truncate">{{ $media->ancho }} × {{ $media->alto }} px</p></div>@endif
                    <div class="min-w-0"><span class="text-gray-400">Subido por</span><p class="font-medium truncate">{{ $media->subidor->name }}</p></div>
                    <div class="min-w-0"><span class="text-gray-400">Colección</span><p class="font-medium truncate capitalize">{{ $media->coleccion }}</p></div>
                    <div class="min-w-0"><span class="text-gray-400">Fecha</span><p class="font-medium truncate">{{ $media->created_at->format('d/m/Y') }}</p></div>
                </div>

                @auth
                    @if(Auth::user()->isAdmin())
                        <form action="{{ route('media.destroy', $media) }}" method="POST" class="mt-6" onsubmit="return confirm('¿Eliminar este archivo de la biblioteca?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection
