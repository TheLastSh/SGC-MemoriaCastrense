@extends('layouts.app')
@section('title', 'Solicitudes Pendientes')

@section('content')
    <h1 class="text-3xl font-merriweather font-bold text-navy-900 mb-8">Solicitudes de Verificación Pendientes</h1>

    <div class="space-y-4">
        @forelse($solicitudes as $solicitud)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
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
                            <button type="submit" class="bg-green-50 hover:bg-green-100 text-green-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">Aprobar</button>
                        </form>
                        <form action="{{ route('verificacion.rechazar', $solicitud) }}" method="POST" onsubmit="return confirm('¿Rechazar esta solicitud?')">
                            @csrf
                            <input type="text" name="motivo" placeholder="Motivo del rechazo" required class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">Rechazar</button>
                        </form>
                    </div>
                </div>
                <div class="mt-4 bg-gray-50 rounded p-4">
                    <p class="text-sm font-medium text-navy-900">Reseña Curricular:</p>
                    <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $solicitud->resena_curricular }}</p>
                    @if($solicitud->documento_path)
                        <a href="{{ Storage::disk('public')->url($solicitud->documento_path) }}" target="_blank" class="inline-block mt-2 text-sm text-gold-600 hover:underline">Ver Documento</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-500">
                <p>No hay solicitudes pendientes.</p>
            </div>
        @endforelse
    </div>
@endsection
