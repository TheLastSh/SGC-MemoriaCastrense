<?php

namespace App\Http\Controllers;

use App\Models\SolicitudVerificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VerificacionController extends Controller
{
    /**
     * Muestra el formulario de solicitud de verificación.
     */
    public function solicitar(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->solicitudVerificacion) {
            return redirect()->back()->withErrors(['error' => 'Ya tienes una solicitud de verificación.']);
        }

        return view('verificacion.solicitar');
    }

    /**
     * Procesa y almacena una nueva solicitud de verificación.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->solicitudVerificacion) {
            return redirect()->back()->withErrors(['error' => 'Ya tienes una solicitud activa.']);
        }

        $request->validate([
            'tipo' => 'required|in:historiador,cultor,cronista',
            'resena_curricular' => 'required|string|min:50|max:2000',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
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
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al crear solicitud de verificación: '.$e->getMessage());

            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Error interno al enviar la solicitud.']);
        }
    }

    /**
     * Retorna el conteo de solicitudes pendientes (para el badge en vivo).
     */
    public function pendientesCount(): JsonResponse
    {
        $count = SolicitudVerificacion::where('status', 'pendiente')->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Muestra la lista de solicitudes de verificación pendientes.
     */
    public function pendientes(): View
    {
        $solicitudes = SolicitudVerificacion::with('usuario')
            ->where('status', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('verificacion.pendientes', compact('solicitudes'));
    }

    /**
     * Aprueba una solicitud de verificación y actualiza el rol del usuario.
     */
    public function aprobar(SolicitudVerificacion $solicitud): RedirectResponse
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al aprobar solicitud: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al aprobar la solicitud.']);
        }
    }

    /**
     * Rechaza una solicitud de verificación con un motivo.
     */
    public function rechazar(Request $request, SolicitudVerificacion $solicitud): RedirectResponse
    {
        $request->validate(['motivo' => 'required|string|max:500']);

        try {
            $solicitud->update([
                'status' => 'rechazado',
                'revisado_por' => Auth::id(),
                'motivo_rechazo' => $request->motivo,
            ]);

            return redirect()->route('verificacion.pendientes')
                ->with('success', 'Solicitud rechazada.');
        } catch (\Exception $e) {
            Log::error('[ERROR] Error al rechazar solicitud: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Error interno al rechazar la solicitud.']);
        }
    }
}
