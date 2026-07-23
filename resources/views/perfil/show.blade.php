@extends('layouts.app')
@section('title', 'Mi Perfil - SGC Memoria Castrense')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ tab: '{{ $user->isVerificado() ? 'articulos' : 'favoritos' }}' }">
    {{-- Header del perfil --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-8 card-accent-gold mb-8" data-reveal>
        <div class="flex items-start gap-6 flex-col sm:flex-row">
            <div class="w-20 h-20 rounded-full bg-navy-900 flex items-center justify-center text-white font-display font-bold text-3xl flex-shrink-0">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-wrap">
                    <h1 class="text-3xl font-display font-bold text-navy-900">{{ $user->name }}</h1>
                    @if($user->tipo_verificado)
                        <span class="bg-teal-50/80 text-teal-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">✓ {{ $user->tipo_verificado }}</span>
                    @elseif($user->isAdmin())
                        <span class="bg-gold-100 text-gold-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Administrador</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-medium">Usuario</span>
                    @endif
                </div>
                @if($user->biografia)
                    <p class="text-gray-600 mt-2">{{ $user->biografia }}</p>
                @endif
                <div class="flex items-center gap-4 mt-3 text-sm text-gray-500 flex-wrap">
                    <span>Miembro desde {{ $user->created_at->format('M Y') }}</span>
                    <span>•</span>
                    @if($user->isVerificado())
                        <span>{{ $stats['articulos'] }} artículos</span>
                        <span>•</span>
                    @endif
                    <span>{{ $stats['favoritos'] }} favoritos</span>
                    <span>•</span>
                    <span>{{ $stats['comentarios'] }} comentarios</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-parchment-200/50 mb-8" data-reveal>
        <nav class="flex gap-6 -mb-px overflow-x-auto pb-1">
            @if($user->isVerificado())
                <button @click="tab = 'articulos'" :class="{ 'border-gold-600 text-gold-600': tab === 'articulos', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'articulos' }" class="pb-3 border-b-2 text-sm font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    Mis Artículos
                </button>
            @endif
            <button @click="tab = 'favoritos'" :class="{ 'border-gold-600 text-gold-600': tab === 'favoritos', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'favoritos' }" class="pb-3 border-b-2 text-sm font-semibold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                Favoritos
                @if($stats['favoritos'] > 0)
                    <span class="bg-gold-100 text-gold-700 text-xs px-2 py-0.5 rounded-full">{{ $stats['favoritos'] }}</span>
                @endif
            </button>
            <button @click="tab = 'comentarios'" :class="{ 'border-gold-600 text-gold-600': tab === 'comentarios', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'comentarios' }" class="pb-3 border-b-2 text-sm font-semibold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Comentarios
            </button>
            @if($user->isAdmin())
                <a href="{{ route('verificacion.pendientes') }}" class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-sm font-semibold transition-colors flex items-center gap-2 group">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Pendientes
                    <span id="pendientes-badge" class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full font-bold group-hover:bg-red-200 transition-colors">{{ $pendientesCount > 0 ? $pendientesCount : '0' }}</span>
                </a>
            @else
                <button @click="tab = 'verificacion'" :class="{ 'border-gold-600 text-gold-600': tab === 'verificacion', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'verificacion' }" class="pb-3 border-b-2 text-sm font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Verificación
                </button>
            @endif
        </nav>
    </div>

    {{-- Mis Artículos (solo verificados) --}}
    <div x-show="tab === 'articulos'" x-cloak data-reveal>
        @if($user->articulos->count())
            <div class="space-y-4">
                @foreach($user->articulos as $articulo)
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs text-gold-600 font-bold uppercase tracking-wider">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                                    @if($articulo->status === 'publicado')
                                        <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full font-medium">Publicado</span>
                                    @elseif($articulo->status === 'borrador')
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">Borrador</span>
                                    @else
                                        <span class="text-xs bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full font-medium">Revisión</span>
                                    @endif
                                </div>
                                <h3 class="font-display font-bold text-navy-900">{{ $articulo->titulo }}</h3>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                        {{ $articulo->visitas }} visitas
                                    </span>
                                    <span>{{ $articulo->comentarios->count() }} comentarios</span>
                                    <span>{{ $articulo->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2 flex-shrink-0">
                                <a href="{{ route('articulos.show', $articulo) }}" class="text-gold-600 hover:text-gold-500 text-sm font-medium">Ver</a>
                                <a href="{{ route('articulos.edit', $articulo) }}" class="text-navy-900 hover:text-navy-700 text-sm font-medium">Editar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white/60 backdrop-blur-sm rounded-xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                <p class="text-gray-500 text-lg font-medium">No has publicado artículos aún</p>
                @if($user->isPublicador())
                    <a href="{{ route('articulos.create') }}" class="inline-block mt-4 text-gold-600 hover:text-gold-500 font-semibold">Escribir tu primer artículo →</a>
                @endif
            </div>
        @endif
    </div>

    {{-- Favoritos --}}
    <div x-show="tab === 'favoritos'" x-cloak data-reveal>
        @if($user->favoritos->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($user->favoritos as $articulo)
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-4 card-accent-teal flex gap-4">
                        @if($articulo->portada_url)
                            <img src="{{ $articulo->portada_url }}" alt="" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-lg bg-navy-800/5 flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-navy-800/15" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <span class="text-xs text-gold-600 font-bold uppercase tracking-wider">{{ $articulo->categoria->nombre ?? 'General' }}</span>
                            <a href="{{ route('articulos.show', $articulo) }}" class="block font-display font-bold text-navy-900 mt-0.5 hover:text-gold-600 transition-colors truncate">{{ $articulo->titulo }}</a>
                            <p class="text-xs text-gray-400 mt-1">por {{ $articulo->autor->name }}</p>
                            <button onclick="toggleFavorito({{ $articulo->id }}, this)" class="mt-2 text-sm text-red-400 hover:text-red-600 font-medium transition-colors">Quitar</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white/60 backdrop-blur-sm rounded-xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <p class="text-gray-500 text-lg font-medium">No tienes artículos guardados</p>
                <a href="{{ route('articulos.index') }}" class="inline-block mt-4 text-gold-600 hover:text-gold-500 font-semibold">Explorar artículos →</a>
            </div>
        @endif
    </div>

    {{-- Comentarios --}}
    <div x-show="tab === 'comentarios'" x-cloak data-reveal>
        @if($user->comentarios->count())
            <div class="space-y-4">
                @foreach($user->comentarios as $comentario)
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-gray-700">{{ Str::limit($comentario->contenido, 200) }}</p>
                                <div class="flex items-center gap-2 mt-2 text-xs text-gray-400">
                                    <span>En</span>
                                    <a href="{{ route('articulos.show', $comentario->articulo) }}" class="text-gold-600 hover:text-gold-500 font-medium truncate">{{ $comentario->articulo->titulo }}</a>
                                    <span>• {{ $comentario->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white/60 backdrop-blur-sm rounded-xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <p class="text-gray-500 text-lg font-medium">No has hecho comentarios aún</p>
            </div>
        @endif
    </div>

    {{-- Verificación --}}
    @unless($user->isAdmin())
    <div x-show="tab === 'verificacion'" x-cloak data-reveal>
        @php $solicitud = $user->solicitudVerificacion; @endphp
        @if($solicitud)
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-8 text-center">
                @if($solicitud->status === 'aprobado')
                    <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-navy-900">¡Verificado como {{ ucfirst($solicitud->tipo) }}!</h3>
                    <p class="text-gray-500 mt-2">Ya puedes publicar artículos y subir archivos a la biblioteca.</p>
                @elseif($solicitud->status === 'rechazado')
                    <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-navy-900">Solicitud Rechazada</h3>
                    @if($solicitud->motivo_rechazo)
                        <p class="text-gray-500 mt-2">{{ $solicitud->motivo_rechazo }}</p>
                    @endif
                    <a href="{{ route('verificacion.solicitar') }}" class="inline-block mt-4 text-gold-600 hover:text-gold-500 font-semibold">Solicitar nuevamente →</a>
                @else
                    <div class="w-16 h-16 rounded-full bg-yellow-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-navy-900">Solicitud Pendiente</h3>
                    <p class="text-gray-500 mt-2">Estamos revisando tu solicitud como <strong>{{ ucfirst($solicitud->tipo) }}</strong>. Te notificaremos cuando sea aprobada.</p>
                    <p class="text-xs text-gray-400 mt-4">Enviada {{ $solicitud->created_at->diffForHumans() }}</p>
                @endif
            </div>
        @else
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-navy-800/5 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-navy-900">Verifica tu Cuenta</h3>
                <p class="text-gray-500 mt-2">Si eres historiador, cultor o cronista, solicita tu verificación para publicar artículos y subir archivos.</p>
                <a href="{{ route('verificacion.solicitar') }}" class="inline-block mt-4 bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-6 py-3 rounded-lg text-sm font-bold shadow-sm transition-all">Solicitar Verificación</a>
            </div>
        @endif
    </div>
    @endunless
</div>

<script>
@if($user->isAdmin())
setInterval(function() {
    fetch('{{ route('verificacion.pendientes.count') }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('pendientes-badge').textContent = data.count;
        });
}, 10000);
@endif

function toggleFavorito(articuloId, btn) {
    fetch('/perfil/favoritos/' + articuloId, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'removed') {
                btn.closest('.flex')?.remove() || btn.closest('.grid > div')?.remove();
            }
        });
}
</script>
@endsection
