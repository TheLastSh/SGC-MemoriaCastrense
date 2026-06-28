@extends('layouts.app')
@section('title', 'Foro de Discusión — La Vela de Coro')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-merriweather font-bold text-navy-900">Foro de Discusión</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($categorias as $categoria)
            <a href="{{ route('foro.categoria', $categoria) }}" class="group bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-gold-600/30 transition-all">
                <h2 class="font-merriweather font-bold text-navy-900 text-xl group-hover:text-gold-600 transition-colors">{{ $categoria->nombre }}</h2>
                @if($categoria->descripcion)
                    <p class="text-gray-500 text-sm mt-1">{{ $categoria->descripcion }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-3">{{ $categoria->hilos_count }} hilos</p>
            </a>
        @empty
            <div class="col-span-2 text-center py-16 text-gray-500">
                <p>No hay categorías en el foro aún.</p>
            </div>
        @endforelse
    </div>
@endsection
