@extends('layouts.app')
@section('title', 'Nuevo Artículo — La Vela de Coro')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-merriweather font-bold text-navy-900 mb-8">Nuevo Artículo</h1>

        <form action="{{ route('articulos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1">Título *</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="255" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                    @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1">Categoría *</label>
                    <select name="categoria_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Extracto (opcional)</label>
                <textarea name="extracto" rows="2" maxlength="500" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">{{ old('extracto') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Contenido *</label>
                <input type="hidden" name="contenido" id="contenido" value="{{ old('contenido') }}">
                <trix-editor input="contenido" class="trix-content min-h-[300px] border border-gray-300 rounded-lg"></trix-editor>
                @error('contenido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Tags (opcional)</label>
                <select name="tags[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Imagen de Portada (opcional, máx 5MB)</label>
                <input type="file" name="portada" accept="image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                @error('portada') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <button type="submit" name="status" value="publicado" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-8 py-3 rounded-lg text-sm font-bold transition-colors">Publicar</button>
                <button type="submit" name="status" value="borrador" class="bg-navy-900 hover:bg-navy-800 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">Guardar como Borrador</button>
                <a href="{{ route('articulos.index') }}" class="text-gray-500 hover:text-gray-700 text-sm ml-auto">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
