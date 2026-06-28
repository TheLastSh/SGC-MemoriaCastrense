@extends('layouts.app')
@section('title', 'Foro de Discusión — La Vela de Coro')

@section('content')
    <div class="flex justify-between items-center mb-8" data-reveal>
        <h1 class="text-heading text-navy-900">Foro de Discusión</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger-children" data-reveal>
        @forelse($categorias as $categoria)
            <a href="{{ route('foro.categoria', $categoria) }}" class="group/card bg-white/80 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 border border-parchment-100/50 p-6 card-accent-gold">
                <h2 class="font-display font-bold text-navy-900 text-xl group-hover:text-gold-600 transition-colors">{{ $categoria->nombre }}</h2>
                @if($categoria->descripcion)
                    <p class="text-gray-500 text-sm mt-1">{{ $categoria->descripcion }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-3">{{ $categoria->hilos_count }} hilos</p>
            </a>
        @empty
            <div class="col-span-2 text-center py-16 text-gray-500 bg-white/60 backdrop-blur-sm rounded-xl">
                <p>No hay categorías en el foro aún.</p>
            </div>
        @endforelse
    </div>
@endsection
