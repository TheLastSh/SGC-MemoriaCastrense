<?php

namespace App\Http\Controllers;

use App\Models\SolicitudVerificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificacionController extends Controller
{
    public function solicitar()
    {
        if (Auth::user()->solicitudVerificacion) {
            return redirect()->back()->withErrors(['error' => 'Ya tienes una solicitud de verificación.']);
        }

        return view('verificacion.solicitar');
    }

    public function store(Request $request)
    {
        if (Auth::user()->solicitudVerificacion) {
            return redirect()->back()->withErrors(['error' => 'Ya tienes una solicitud activa.']);
        }

        $request->validate([
            'tipo' => 'required|in:historiador,cultor,cronista',
            'resena_curricular' => 'required|string|min:50|max:2000',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $documentoPath = null;
        if ($request->hasFile('documento')) {
            $documentoPath = $request->file('documento')
                ->store('verificaciones/'.Auth::id(), 'public');
        }

        SolicitudVerificacion::create([
            'user_id' => Auth::id(),
            'tipo' => $request->tipo,
            'documento_path' => $documentoPath,
            'resena_curricular' => $request->resena_curricular,
        ]);

        return redirect()->route('home')
            ->with('success', 'Solicitud enviada. Un administrador la revisará pronto.');
    }

    public function pendientes()
    {
        $solicitudes = SolicitudVerificacion::with('usuario')
            ->where('status', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('verificacion.pendientes', compact('solicitudes'));
    }

    public function aprobar(SolicitudVerificacion $solicitud)
    {
        $solicitud->update([
            'status' => 'aprobado',
            'revisado_por' => Auth::id(),
            'fecha_verificacion' => now(),
        ]);

        $solicitud->usuario->update([
            'role' => 'publicador',
            'tipo_verificado' => $solicitud->tipo,
        ]);

        return redirect()->route('verificacion.pendientes')
            ->with('success', 'Usuario verificado como '.$solicitud->tipo.'.');
    }

    public function rechazar(Request $request, SolicitudVerificacion $solicitud)
    {
        $request->validate(['motivo' => 'required|string|max:500']);

        $solicitud->update([
            'status' => 'rechazado',
            'revisado_por' => Auth::id(),
            'motivo_rechazo' => $request->motivo,
        ]);

        return redirect()->route('verificacion.pendientes')
            ->with('success', 'Solicitud rechazada.');
    }
}
