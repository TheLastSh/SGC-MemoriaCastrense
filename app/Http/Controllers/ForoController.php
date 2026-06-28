<?php

namespace App\Http\Controllers;

use App\Models\ForoCategoria;
use App\Models\Hilo;
use App\Models\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForoController extends Controller
{
    public function index()
    {
        $categorias = ForoCategoria::withCount('hilos')->orderBy('orden')->get();

        return view('foro.index', compact('categorias'));
    }

    public function showCategoria(ForoCategoria $categoria)
    {
        $hilos = $categoria->hilos()
            ->with(['autor', 'respuestas'])
            ->withCount('respuestas')
            ->orderBy('fijado', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('foro.categoria', compact('categoria', 'hilos'));
    }

    public function createHilo(ForoCategoria $categoria)
    {
        return view('foro.create-hilo', compact('categoria'));
    }

    public function storeHilo(Request $request, ForoCategoria $categoria)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido_inicial' => 'required|string|min:10',
        ]);

        $hilo = Hilo::create([
            'titulo' => $request->titulo,
            'contenido_inicial' => $request->contenido_inicial,
            'autor_id' => Auth::id(),
            'categoria_id' => $categoria->id,
        ]);

        return redirect()->route('foro.hilo', $hilo)
            ->with('success', 'Hilo creado correctamente.');
    }

    public function showHilo(Hilo $hilo)
    {
        $hilo->load(['autor', 'categoria', 'respuestas.autor']);

        return view('foro.hilo', compact('hilo'));
    }

    public function storeRespuesta(Request $request, Hilo $hilo)
    {
        if ($hilo->status === 'cerrado') {
            return redirect()->back()->withErrors(['error' => 'Este hilo está cerrado.']);
        }

        $request->validate([
            'contenido' => 'required|string|min:3',
        ]);

        Respuesta::create([
            'hilo_id' => $hilo->id,
            'autor_id' => Auth::id(),
            'contenido' => $request->contenido,
        ]);

        return redirect()->route('foro.hilo', $hilo)
            ->with('success', 'Respuesta publicada.');
    }

    public function destroyRespuesta(Respuesta $respuesta)
    {
        if ($respuesta->autor_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $respuesta->delete();

        return redirect()->back()->with('success', 'Respuesta eliminada.');
    }
}
