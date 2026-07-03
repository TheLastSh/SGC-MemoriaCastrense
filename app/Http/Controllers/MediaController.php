<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\ArticuloService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MediaController extends Controller
{
    protected $articuloService;

    public function __construct(ArticuloService $articuloService)
    {
        $this->articuloService = $articuloService;
    }

    /**
     * Muestra la biblioteca de medios con filtros.
     */
    public function index(Request $request): View
    {
        $query = Media::with('subidor');

        if ($request->has('buscar')) {
            $termino = '%'.$request->buscar.'%';
            $query->where(function ($q) use ($termino) {
                $q->where('nombre_original', 'LIKE', $termino)
                    ->orWhere('alt_text', 'LIKE', $termino)
                    ->orWhere('descripcion', 'LIKE', $termino);
            });
        }

        if ($request->has('coleccion')) {
            $query->where('coleccion', $request->coleccion);
        }

        if ($request->has('tipo')) {
            $query->where('mime_type', 'LIKE', $request->tipo.'%');
        }

        $media = $query->orderBy('created_at', 'desc')
            ->paginate(24);

        return view('media.index', compact('media'));
    }

    /**
     * Muestra el formulario de subida de medios.
     */
    public function create(): View
    {
        return view('media.create');
    }

    /**
     * Almacena un nuevo archivo en la biblioteca de medios.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo' => 'required|file|max:25600',
            'coleccion' => 'required|in:imagen,video,documento',
            'alt_text' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        try {
            $media = $this->articuloService->subirMedia(
                $request->only(['coleccion', 'alt_text', 'descripcion']),
                $request->file('archivo'),
                Auth::id()
            );

            return redirect()->route('media.show', $media)
                ->with('success', 'Archivo subido a la biblioteca.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al subir archivo: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al subir el archivo.']);
        }
    }

    /**
     * Muestra un archivo de la biblioteca.
     */
    public function show(Media $media): View
    {
        $media->load('subidor');

        return view('media.show', compact('media'));
    }

    /**
     * Elimina un archivo de la biblioteca.
     */
    public function destroy(Media $media): RedirectResponse
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403);
        }

        try {
            $media->delete();

            return redirect()->route('media.index')
                ->with('success', 'Archivo eliminado de la biblioteca.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al eliminar archivo: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al eliminar el archivo.']);
        }
    }
}
