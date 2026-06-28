@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-merriweather font-bold text-navy-900">Crear una Cuenta</h2>
            <p class="text-sm text-gray-500 mt-1">Únete a la Memoria Castrense para dejar comentarios y guardar documentos</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold-500 focus:ring-gold-500 text-sm">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold-500 focus:ring-gold-500 text-sm">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold-500 focus:ring-gold-500 text-sm">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold-500 focus:ring-gold-500 text-sm">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-navy-900 hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-900 transition-colors">
                    Registrarse
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-gold-600 font-semibold hover:text-gold-700">Inicia sesión</a></p>
        </div>
    </div>
</div>
@endsection
