<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Comentario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComentarioController extends Controller
{
    /**
     * Almacena un nuevo comentario en un artículo.
     */
    public function store(Request $request, Articulo $articulo): RedirectResponse
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        try {
            $articulo->comentarios()->create([
                'user_id' => Auth::id(),
                'contenido' => $request->contenido,
            ]);

            return redirect()->route('articulos.show', $articulo)
                ->with('success', 'Comentario agregado.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al crear comentario: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al crear el comentario.']);
        }
    }

    /**
     * Elimina un comentario.
     */
    public function destroy(Comentario $comentario): RedirectResponse
    {
        if ($comentario->user_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        try {
            $comentario->delete();

            return redirect()->back()->with('success', 'Comentario eliminado.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al eliminar comentario: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al eliminar el comentario.']);
        }
    }
}
