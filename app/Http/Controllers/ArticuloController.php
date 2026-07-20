<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Tag;
use App\Services\ArticuloService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticuloController extends Controller
{
    protected $articuloService;

    public function __construct(ArticuloService $articuloService)
    {
        $this->articuloService = $articuloService;
    }

    /**
     * Muestra el catálogo público de artículos con filtros.
     */
    public function index(Request $request): View
    {
        $query = Articulo::with(['categoria', 'autor'])
            ->where('status', 'publicado');

        if ($request->has('buscar')) {
            $termino = '%'.$request->buscar.'%';
            $query->where(function ($q) use ($termino) {
                $q->where('titulo', 'LIKE', $termino)
                    ->orWhere('extracto', 'LIKE', $termino)
                    ->orWhere('contenido', 'LIKE', $termino);
            });
        }

        if ($request->has('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $request->tag));
        }

        $articulos = $query->orderBy('fecha_publicacion', 'desc')
            ->paginate(12);

        $categorias = Categoria::all();
        $tags = Tag::all();

        return view('articulos.index', compact('articulos', 'categorias', 'tags'));
    }

    /**
     * Muestra un artículo individual.
     */
    public function show(Articulo $articulo): View
    {
        if ($articulo->status !== 'publicado' && $articulo->author_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(404);
        }

        $articulo->load(['categoria', 'autor', 'tags', 'comentarios.user']);
        $articulo->increment('visitas');

        return view('articulos.show', compact('articulo'));
    }

    /**
     * Muestra el formulario de creación de artículos.
     */
    public function create(): View
    {
        $categorias = Categoria::all();
        $tags = Tag::all();

        return view('articulos.create', compact('categorias', 'tags'));
    }

    /**
     * Almacena un nuevo artículo.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'titulo' => 'required|string|max:255',
            'extracto' => 'nullable|string|max:500',
            'contenido' => 'required|string',
            'categoria_id' => 'required',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'portada' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'required|in:borrador,publicado',
        ];

        if ($request->categoria_id === 'otro') {
            $rules['categoria_nueva'] = 'required|string|max:255';
        } else {
            $rules['categoria_id'] .= '|exists:categorias,id';
        }

        $request->validate($rules);

        $categoriaId = $request->categoria_id;
        if ($categoriaId === 'otro') {
            $categoria = Categoria::firstOrCreate(
                ['nombre' => $request->categoria_nueva]
            );
            $categoriaId = $categoria->id;
        }

        try {
            $datos = $request->only(['titulo', 'extracto', 'contenido', 'status']);
            $datos['categoria_id'] = $categoriaId;

            $this->articuloService->publicarArticulo(
                $datos,
                $request->tags ?? [],
                $request->file('portada'),
                Auth::id()
            );

            $msg = $request->status === 'publicado'
                ? 'Artículo publicado correctamente.'
                : 'Artículo guardado como borrador.';

            $redirect = $request->redirect_to && str_starts_with($request->redirect_to, '/')
                ? $request->redirect_to
                : route('articulos.index');

            return redirect($redirect)->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Muestra el formulario de edición de un artículo.
     */
    public function edit(Articulo $articulo): View
    {
        if ($articulo->author_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $categorias = Categoria::all();
        $tags = Tag::all();

        return view('articulos.edit', compact('articulo', 'categorias', 'tags'));
    }

    /**
     * Actualiza un artículo existente.
     */
    public function update(Request $request, Articulo $articulo): RedirectResponse
    {
        if ($articulo->author_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $rules = [
            'titulo' => 'required|string|max:255',
            'extracto' => 'nullable|string|max:500',
            'contenido' => 'required|string',
            'categoria_id' => 'required',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => 'required|in:borrador,publicado',
        ];

        if ($request->categoria_id === 'otro') {
            $rules['categoria_nueva'] = 'required|string|max:255';
        } else {
            $rules['categoria_id'] .= '|exists:categorias,id';
        }

        $request->validate($rules);

        $categoriaId = $request->categoria_id;
        if ($categoriaId === 'otro') {
            $categoria = Categoria::firstOrCreate(
                ['nombre' => $request->categoria_nueva]
            );
            $categoriaId = $categoria->id;
        }

        $articulo->update([
            'titulo' => $request->titulo,
            'extracto' => $request->extracto,
            'contenido' => $request->contenido,
            'categoria_id' => $categoriaId,
            'status' => $request->status,
        ]);

        if ($request->status === 'publicado' && ! $articulo->fecha_publicacion) {
            $articulo->update(['fecha_publicacion' => now()]);
        }

        $articulo->tags()->sync($request->tags ?? []);

        $redirect = $request->redirect_to && str_starts_with($request->redirect_to, '/')
            ? $request->redirect_to
            : route('articulos.show', $articulo);

        return redirect($redirect)
            ->with('success', 'Artículo actualizado correctamente.');
    }

    /**
     * Elimina un artículo (soft delete).
     */
    public function destroy(Articulo $articulo): RedirectResponse
    {
        if ($articulo->author_id !== Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $articulo->delete();

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo archivado.');
    }
}
