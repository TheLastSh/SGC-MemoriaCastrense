@extends('layouts.app')
@section('title', 'Error del Servidor')

@section('content')
<div class="max-w-lg mx-auto text-center py-20">
    <h1 class="text-6xl font-display font-bold text-navy-900 mb-4">500</h1>
    <p class="text-xl text-gray-600 mb-2">Error interno del servidor</p>
    <p class="text-gray-500 mb-4">Ocurrió un error inesperado. Si el problema persiste, contacta al administrador.</p>
    @if(isset($correlation_id) && $correlation_id !== 'N/A')
        <p class="text-sm text-gold-600 font-mono bg-gold-50 inline-block px-4 py-2 rounded-lg mb-8">Reporte el código: {{ $correlation_id }}</p>
    @endif
    <div>
        <a href="{{ route('home') }}" class="inline-block bg-gold-600 hover:bg-gold-500 text-white px-6 py-3 rounded-lg font-medium transition-all">Volver al inicio</a>
    </div>
</div>
@endsection
