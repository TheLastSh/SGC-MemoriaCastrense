<?php

use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\VerificacionController;
use Illuminate\Support\Facades\Route;

// Públicas (sin parámetros primero)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/archivo', [ArticuloController::class, 'index'])->name('articulos.index');

// Biblioteca de Medios (pública lectura)
Route::get('/biblioteca', [MediaController::class, 'index'])->name('media.index');
Route::get('/biblioteca/{media}', [MediaController::class, 'show'])->name('media.show');

// Foro (público lectura)
Route::get('/foro', [ForoController::class, 'index'])->name('foro.index');
Route::get('/foro/{categoria}', [ForoController::class, 'showCategoria'])->name('foro.categoria');
Route::get('/foro/hilo/{hilo}', [ForoController::class, 'showHilo'])->name('foro.hilo');

// Autenticación (invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
});

// Autenticados
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Comentarios en artículos
    Route::post('/archivo/{articulo}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    // Foro (crear hilos y responder)
    Route::get('/foro/{categoria}/nuevo-hilo', [ForoController::class, 'createHilo'])->name('foro.create-hilo');
    Route::post('/foro/{categoria}/nuevo-hilo', [ForoController::class, 'storeHilo'])->name('foro.store-hilo');
    Route::post('/foro/hilo/{hilo}/responder', [ForoController::class, 'storeRespuesta'])->name('foro.responder');
    Route::delete('/foro/respuesta/{respuesta}', [ForoController::class, 'destroyRespuesta'])->name('foro.respuesta.destroy');

    // Verificación de usuarios
    Route::get('/verificacion/solicitar', [VerificacionController::class, 'solicitar'])->name('verificacion.solicitar');
    Route::post('/verificacion/solicitar', [VerificacionController::class, 'store'])->name('verificacion.store');

    // Biblioteca (solo verificados pueden subir)
    Route::middleware('role:administrador,publicador')->group(function () {
        Route::get('/biblioteca/subir', [MediaController::class, 'create'])->name('media.create');
        Route::post('/biblioteca/subir', [MediaController::class, 'store'])->name('media.store');

        Route::get('/archivo/crear', [ArticuloController::class, 'create'])->name('articulos.create');
        Route::post('/archivo/crear', [ArticuloController::class, 'store'])->name('articulos.store');
        Route::get('/archivo/{articulo}/editar', [ArticuloController::class, 'edit'])->name('articulos.edit');
        Route::put('/archivo/{articulo}', [ArticuloController::class, 'update'])->name('articulos.update');
        Route::delete('/archivo/{articulo}', [ArticuloController::class, 'destroy'])->name('articulos.destroy');
    });

    // Solo administradores
    Route::middleware('role:administrador')->group(function () {
        Route::get('/verificaciones/pendientes', [VerificacionController::class, 'pendientes'])->name('verificacion.pendientes');
        Route::post('/verificaciones/{solicitud}/aprobar', [VerificacionController::class, 'aprobar'])->name('verificacion.aprobar');
        Route::post('/verificaciones/{solicitud}/rechazar', [VerificacionController::class, 'rechazar'])->name('verificacion.rechazar');

        Route::delete('/biblioteca/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });
});

// Ruta pública con parámetro (debe ir DESPUÉS de las rutas fijas)
Route::get('/archivo/{articulo}', [ArticuloController::class, 'show'])->name('articulos.show');
