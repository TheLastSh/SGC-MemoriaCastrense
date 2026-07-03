<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\SolicitudVerificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerfilController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado con sus datos y estadísticas.
     */
    public function show(): View
    {
        $user = Auth::user()->load([
            'articulos' => fn ($q) => $q->with('categoria')->orderBy('created_at', 'desc'),
            'favoritos' => fn ($q) => $q->with('categoria', 'autor')->orderBy('favoritos.created_at', 'desc'),
            'comentarios' => fn ($q) => $q->with('articulo')->orderBy('created_at', 'desc')->take(20),
            'solicitudVerificacion',
        ]);

        $stats = [
            'articulos' => $user->articulos->count(),
            'favoritos' => $user->favoritos->count(),
            'comentarios' => $user->comentarios->count(),
        ];

        $pendientesCount = $user->isAdmin()
            ? SolicitudVerificacion::where('status', 'pendiente')->count()
            : 0;

        return view('perfil.show', compact('user', 'stats', 'pendientesCount'));
    }

    /**
     * Agrega o quita un artículo de los favoritos del usuario.
     */
    public function toggleFavorito(Articulo $articulo): JsonResponse
    {
        $user = Auth::user();

        if ($user->hasFavorito($articulo)) {
            $user->favoritos()->detach($articulo);

            return response()->json(['status' => 'removed']);
        }

        $user->favoritos()->attach($articulo);

        return response()->json(['status' => 'added']);
    }
}
