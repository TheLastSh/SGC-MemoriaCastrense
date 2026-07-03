<?php

namespace App\Http\Controllers;

use App\Models\ForoCategoria;
use App\Models\Hilo;
use App\Models\Respuesta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ForoController extends Controller
{
    /**
     * Muestra el índice del foro con todas las categorías.
     */
    public function index(): View
    {
        $categorias = ForoCategoria::withCount('hilos')->orderBy('orden')->get();

        return view('foro.index', compact('categorias'));
    }

    /**
     * Muestra los hilos de una categoría del foro.
     */
    public function showCategoria(ForoCategoria $categoria): View
    {
        $hilos = $categoria->hilos()
            ->with(['autor', 'respuestas'])
            ->withCount('respuestas')
            ->orderBy('fijado', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('foro.categoria', compact('categoria', 'hilos'));
    }

    /**
     * Muestra el formulario para crear un nuevo hilo.
     */
    public function createHilo(ForoCategoria $categoria): View
    {
        return view('foro.create-hilo', compact('categoria'));
    }

    /**
     * Almacena un nuevo hilo en el foro.
     */
    public function storeHilo(Request $request, ForoCategoria $categoria): RedirectResponse
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido_inicial' => 'required|string|min:10',
        ]);

        try {
            $hilo = Hilo::create([
                'titulo' => $request->titulo,
                'contenido_inicial' => $request->contenido_inicial,
                'autor_id' => Auth::id(),
                'categoria_id' => $categoria->id,
            ]);

            return redirect()->route('foro.hilo', $hilo)
                ->with('success', 'Hilo creado correctamente.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al crear hilo: '.$e->getMessage());

            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Error interno al crear el hilo.']);
        }
    }

    /**
     * Muestra un hilo con sus respuestas.
     */
    public function showHilo(Hilo $hilo): View
    {
        $hilo->load(['autor', 'categoria', 'respuestas.autor']);

        return view('foro.hilo', compact('hilo'));
    }

    /**
     * Almacena una respuesta en un hilo.
     */
    public function storeRespuesta(Request $request, Hilo $hilo): RedirectResponse
    {
        if ($hilo->status === 'cerrado') {
            return redirect()->back()->withErrors(['error' => 'Este hilo está cerrado.']);
        }

        $request->validate([
            'contenido' => 'required|string|min:3',
        ]);

        try {
            Respuesta::create([
                'hilo_id' => $hilo->id,
                'autor_id' => Auth::id(),
                'contenido' => $request->contenido,
            ]);

            return redirect()->route('foro.hilo', $hilo)
                ->with('success', 'Respuesta publicada.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al publicar respuesta: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al publicar la respuesta.']);
        }
    }

    /**
     * Elimina una respuesta del foro.
     */
    public function destroyRespuesta(Respuesta $respuesta): RedirectResponse
    {
        if ($respuesta->autor_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        try {
            $respuesta->delete();

            return redirect()->back()->with('success', 'Respuesta eliminada.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al eliminar respuesta: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al eliminar la respuesta.']);
        }
    }
}
