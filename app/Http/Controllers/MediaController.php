<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\ArticuloService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    protected $articuloService;

    public function __construct(ArticuloService $articuloService)
    {
        $this->articuloService = $articuloService;
    }

    public function index(Request $request)
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

    public function create()
    {
        return view('media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:25600',
            'coleccion' => 'required|in:imagen,video,documento',
            'alt_text' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $media = $this->articuloService->subirMedia(
            $request->only(['coleccion', 'alt_text', 'descripcion']),
            $request->file('archivo'),
            Auth::id()
        );

        return redirect()->route('media.show', $media)
            ->with('success', 'Archivo subido a la biblioteca.');
    }

    public function show(Media $media)
    {
        $media->load('subidor');

        return view('media.show', compact('media'));
    }

    public function destroy(Media $media)
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $media->delete();

        return redirect()->route('media.index')
            ->with('success', 'Archivo eliminado de la biblioteca.');
    }
}
