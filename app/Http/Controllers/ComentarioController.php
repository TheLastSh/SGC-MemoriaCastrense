<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, Articulo $articulo)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $articulo->comentarios()->create([
            'user_id' => Auth::id(),
            'contenido' => $request->contenido,
        ]);

        return redirect()->route('articulos.show', $articulo)
            ->with('success', 'Comentario agregado.');
    }

    public function destroy(Comentario $comentario)
    {
        if ($comentario->user_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado.');
    }
}
