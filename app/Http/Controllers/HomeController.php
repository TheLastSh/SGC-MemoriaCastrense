<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Hilo;
use App\Models\Media;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Muestra la página principal con artículos recientes, destacados y estadísticas.
     */
    public function index(): View
    {
        $recientes = Articulo::with('categoria')
            ->where('status', 'publicado')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $destacados = Articulo::with('categoria')
            ->where('status', 'publicado')
            ->orderBy('visitas', 'desc')
            ->take(4)
            ->get();

        $ultimosHilos = Hilo::with(['autor', 'categoria'])
            ->withCount('respuestas')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalArticulos = Articulo::where('status', 'publicado')->count();
        $totalMedia = Media::count();

        return view('home', compact(
            'recientes', 'destacados', 'ultimosHilos',
            'totalArticulos', 'totalMedia'
        ));
    }
}
