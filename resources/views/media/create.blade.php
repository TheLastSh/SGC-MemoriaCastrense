@extends('layouts.app')
@section('title', 'Subir Archivo — Biblioteca')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-merriweather font-bold text-navy-900 mb-8">Subir a la Biblioteca</h1>

        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Archivo * (máx 25MB)</label>
                <input type="file" name="archivo" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                @error('archivo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Tipo de Colección *</label>
                <select name="coleccion" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    <option value="imagen">Imagen</option>
                    <option value="video">Video</option>
                    <option value="documento">Documento</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Texto Alternativo (alt)</label>
                <input type="text" name="alt_text" maxlength="255" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3" maxlength="1000" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm"></textarea>
            </div>

            <button type="submit" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-8 py-3 rounded-lg text-sm font-bold transition-colors">Subir Archivo</button>
        </form>
    </div>
@endsection
