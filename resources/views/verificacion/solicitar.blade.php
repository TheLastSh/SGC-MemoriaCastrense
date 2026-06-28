@extends('layouts.app')
@section('title', 'Solicitar Verificación')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-merriweather font-bold text-navy-900 mb-4">Solicitar Verificación</h1>
        <p class="text-gray-500 mb-8">Si eres historiador, cultor o cronista con conocimiento sobre la historia militar de La Vela de Coro, solicita tu verificación para poder publicar artículos y subir archivos a la biblioteca.</p>

        <form action="{{ route('verificacion.solicitar') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Tipo de Verificación *</label>
                <select name="tipo" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">
                    <option value="historiador">Historiador</option>
                    <option value="cultor">Cultor</option>
                    <option value="cronista">Cronista</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Reseña Curricular * (mín 50 caracteres)</label>
                <textarea name="resena_curricular" rows="6" required minlength="50" maxlength="2000" placeholder="Cuenta tu experiencia, estudios, publicaciones o trayectoria relacionada con la historia militar..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500">{{ old('resena_curricular') }}</textarea>
                @error('resena_curricular') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Documento de Respaldo (opcional — PDF o imagen, máx 10MB)</label>
                <input type="file" name="documento" accept=".pdf,image/jpeg,image/png" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                @error('documento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-gold-600 hover:bg-gold-500 text-navy-950 px-8 py-3 rounded-lg text-sm font-bold transition-colors">Enviar Solicitud</button>
        </form>
    </div>
@endsection
