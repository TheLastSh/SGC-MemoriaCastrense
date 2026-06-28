@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-12 mb-16">
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-10 card-accent-gold">
        <div class="text-center mb-10">
            <img src="{{ asset('img/logo.png') }}" alt="" class="w-14 h-14 mx-auto mb-4">
            <h2 class="text-3xl font-display font-bold text-navy-900">Iniciar Sesión</h2>
            <p class="text-gray-500 mt-2">Accede al Archivo Histórico Militar de La Vela de Coro</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-navy-900 mb-2">Correo Electrónico</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="correo@ejemplo.com"
                        class="pl-10 w-full rounded-lg border-parchment-200/50 bg-white/80 shadow-sm text-sm py-2.5">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-navy-900 mb-2">Contraseña</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="pl-10 w-full rounded-lg border-parchment-200/50 bg-white/80 shadow-sm text-sm py-2.5">
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-navy-950 bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 transition-all">
                Ingresar al Sistema
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">¿No tienes cuenta?</p>
            <a href="{{ route('register') }}" class="text-gold-600 font-bold hover:text-gold-500 text-sm">Crear una Cuenta →</a>
        </div>
    </div>
</div>
@endsection
