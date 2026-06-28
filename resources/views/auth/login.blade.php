@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <div class="text-center mb-8">
            <svg class="w-12 h-12 text-gold-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L5 10.274zm10 0l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L15 10.274z" clip-rule="evenodd" />
            </svg>
            <h2 class="text-2xl font-merriweather font-bold text-navy-900">Iniciar Sesión</h2>
            <p class="text-sm text-gray-500 mt-1">Accede para guardar y comentar en la Memoria Castrense</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Institucional / Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold-500 focus:ring-gold-500 text-sm">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold-500 focus:ring-gold-500 text-sm">
            </div>

            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-navy-950 bg-gold-600 hover:bg-gold-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 transition-colors">
                Ingresar al Sistema
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-gold-600 font-semibold hover:text-gold-700">Regístrate aquí</a></p>
        </div>
    </div>
</div>
@endsection
