@extends('layouts.app')
@section('title', 'Solicitar Verificación')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-heading text-navy-900 mb-4">Solicitar Verificación</h1>
        <p class="text-gray-500 mb-8">Si eres historiador, cultor o cronista con conocimiento sobre la historia militar de La Vela de Coro, solicita tu verificación para poder publicar artículos y subir archivos a la biblioteca.</p>

        <form action="{{ route('verificacion.solicitar') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-6 md:p-8 card-accent-teal">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Tipo de Verificación *</label>
                <select name="tipo" required class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm ">
                    <option value="historiador">Historiador</option>
                    <option value="cultor">Cultor</option>
                    <option value="cronista">Cronista</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Reseña Curricular * (mín 50 caracteres)</label>
                <textarea name="resena_curricular" id="resena_curricular" rows="6" required minlength="50" maxlength="2000" placeholder="Cuenta tu experiencia, estudios, publicaciones o trayectoria relacionada con la historia militar..." class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm " oninput="actualizarContador(this)">{{ old('resena_curricular') }}</textarea>
                <div id="char-counter" class="text-xs mt-1 text-gray-400">Mínimo 50 caracteres</div>
                @error('resena_curricular') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Documento de Respaldo (opcional — PDF o imagen, máx 10MB)</label>
                <input type="file" name="documento" accept=".pdf,image/jpeg,image/png" class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm">
                @error('documento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-8 py-3 rounded-lg text-sm font-bold shadow-sm transition-all">Enviar Solicitud</button>
        </form>
    </div>

<script>
function actualizarContador(el) {
    const len = el.value.length;
    const counter = document.getElementById('char-counter');
    if (len < 50) {
        const faltan = 50 - len;
        counter.textContent = 'Te faltan ' + faltan + ' caracteres (mín 50)';
        counter.className = 'text-xs mt-1 text-red-500';
    } else {
        counter.textContent = '✓ ' + len + ' caracteres (mín 50)';
        counter.className = 'text-xs mt-1 text-green-600';
    }
}
</script>
@endsection
