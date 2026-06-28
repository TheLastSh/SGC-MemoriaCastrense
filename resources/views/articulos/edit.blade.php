@extends('layouts.app')
@section('title', 'Editar: ' . $articulo->titulo)

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-merriweather font-bold text-navy-900 mb-8">Editar Artículo</h1>

        <form action="{{ route('articulos.update', $articulo) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1">Título *</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $articulo->titulo) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1">Categoría *</label>
                    <select name="categoria_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $articulo->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Extracto</label>
                <textarea name="extracto" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">{{ old('extracto', $articulo->extracto) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Contenido *</label>
                <input type="hidden" name="contenido" id="contenido" value="{{ old('contenido', $articulo->contenido) }}">
                <trix-editor input="contenido" class="trix-content min-h-[300px] border border-gray-300 rounded-lg"></trix-editor>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Tags</label>
                <select name="tags[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $articulo->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Estado</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    <option value="borrador" {{ $articulo->status == 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="revision" {{ $articulo->status == 'revision' ? 'selected' : '' }}>En Revisión</option>
                    <option value="publicado" {{ $articulo->status == 'publicado' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <button type="submit" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-8 py-3 rounded-lg text-sm font-bold transition-colors">Guardar Cambios</button>
                <a href="{{ route('articulos.show', $articulo) }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
