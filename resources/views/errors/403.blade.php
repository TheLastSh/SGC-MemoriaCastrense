@extends('layouts.app')
@section('title', 'Acceso Denegado')

@section('content')
<div class="max-w-lg mx-auto text-center py-20">
    <h1 class="text-6xl font-display font-bold text-navy-900 mb-4">403</h1>
    <p class="text-xl text-gray-600 mb-2">Acceso denegado</p>
    <p class="text-gray-500 mb-8">No cuentas con los permisos necesarios para realizar esta acción.</p>
    <a href="{{ route('home') }}" class="inline-block bg-gold-600 hover:bg-gold-500 text-white px-6 py-3 rounded-lg font-medium transition-all">Volver al inicio</a>
    @if(isset($correlation_id) && $correlation_id !== 'N/A')
        <p class="text-xs text-gray-400 mt-6">Código de error: {{ $correlation_id }}</p>
    @endif
</div>
@endsection
