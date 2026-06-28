@extends('layouts.app')
@section('title', 'Nuevo Hilo — ' . $categoria->nombre)

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('foro.categoria', $categoria) }}" class="text-sm text-gray-500 hover:text-gold-600 mb-4 inline-block">← {{ $categoria->nombre }}</a>
        <h1 class="text-3xl font-merriweather font-bold text-navy-900 mb-8">Nuevo Hilo</h1>

        <form action="{{ route('foro.create-hilo', $categoria) }}" method="POST" class="space-y-6 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="255" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Contenido * (mín 10 caracteres)</label>
                <textarea name="contenido_inicial" rows="6" required minlength="10" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">{{ old('contenido_inicial') }}</textarea>
                @error('contenido_inicial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-8 py-3 rounded-lg text-sm font-bold transition-colors">Crear Hilo</button>
        </form>
    </div>
@endsection
