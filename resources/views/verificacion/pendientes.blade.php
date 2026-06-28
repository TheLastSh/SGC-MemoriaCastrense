@extends('layouts.app')
@section('title', 'Solicitudes Pendientes')

@section('content')
    <h1 class="text-heading text-navy-900 mb-8">Solicitudes de Verificación Pendientes</h1>

    <div class="space-y-4">
        @forelse($solicitudes as $solicitud)
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="font-semibold text-navy-900 text-lg">{{ $solicitud->usuario->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $solicitud->usuario->email }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs bg-gold-100 text-gold-700 px-2 py-1 rounded-full font-medium capitalize">{{ $solicitud->tipo }}</span>
                            <span class="text-xs text-gray-400">Solicitado {{ $solicitud->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('verificacion.aprobar', $solicitud) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-50/80 hover:bg-green-100 active:scale-[0.97] text-green-600 px-4 py-2 rounded-md text-sm font-medium transition-all">Aprobar</button>
                        </form>
                        <form action="{{ route('verificacion.rechazar', $solicitud) }}" method="POST" onsubmit="return confirm('¿Rechazar esta solicitud?')">
                            @csrf
                            <input type="text" name="motivo" placeholder="Motivo del rechazo" required class="border border-parchment-200/50 bg-white/80 rounded px-2 py-1 text-sm">
                            <button type="submit" class="bg-red-50/80 hover:bg-red-100 active:scale-[0.97] text-red-600 px-4 py-2 rounded-md text-sm font-medium transition-all">Rechazar</button>
                        </form>
                    </div>
                </div>
                <div class="mt-4 bg-navy-800/5 rounded p-4">
                    <p class="text-sm font-medium text-navy-900">Reseña Curricular:</p>
                    <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $solicitud->resena_curricular }}</p>
                    @if($solicitud->documento_path)
                        <a href="{{ Storage::disk('public')->url($solicitud->documento_path) }}" target="_blank" class="inline-block mt-2 text-sm text-gold-600 hover:underline">Ver Documento</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-500 bg-white/60 backdrop-blur-sm rounded-xl">
                <p>No hay solicitudes pendientes.</p>
            </div>
        @endforelse
    </div>
@endsection
