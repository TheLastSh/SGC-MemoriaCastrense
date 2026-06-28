@extends('layouts.app')
@section('title', 'Nuevo Hilo — ' . $categoria->nombre)

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('foro.categoria', $categoria) }}" class="text-sm text-gray-500 hover:text-gold-600 mb-4 inline-block">← {{ $categoria->nombre }}</a>
        <h1 class="text-heading text-navy-900 mb-8">Nuevo Hilo</h1>

        <form action="{{ route('foro.create-hilo', $categoria) }}" method="POST" class="space-y-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-8 card-accent-gold">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1.5">Título *</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="255" placeholder="Título del hilo" class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm">
                </div>
                @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1.5">Contenido * (mín 10 caracteres)</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-4 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <textarea name="contenido_inicial" rows="6" required minlength="10" placeholder="Describe tu tema o pregunta..." class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm">{{ old('contenido_inicial') }}</textarea>
                </div>
                @error('contenido_inicial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-8 py-3 rounded-lg text-sm font-bold shadow-sm transition-all">Crear Hilo</button>
        </form>
    </div>
@endsection
