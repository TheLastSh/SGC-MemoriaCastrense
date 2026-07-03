<?php

namespace App\Services;

use App\Models\Articulo;
use App\Models\Media;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio encargado de la lógica de negocio para artículos y medios.
 */
class ArticuloService
{
    /**
     * Publica o guarda como borrador un artículo con transacción y manejo de adjuntos.
     *
     * @param  array  $datos  Datos validados del artículo
     * @param  array  $tags  IDs de tags a asociar
     * @param  UploadedFile|null  $portada  Archivo de portada opcional
     * @param  int  $userId  ID del autor
     *
     * @throws Exception
     */
    public function publicarArticulo(array $datos, array $tags, ?UploadedFile $portada, int $userId): Articulo
    {
        $portadaUrl = null;

        if ($portada) {
            $path = $portada->store('articulos/portadas/'.date('Y'), 'public');
            $portadaUrl = Storage::disk('public')->url($path);
        }

        try {
            return DB::transaction(function () use ($datos, $tags, $portadaUrl, $userId) {
                $articulo = Articulo::create([
                    'titulo' => $datos['titulo'],
                    'extracto' => $datos['extracto'] ?? null,
                    'contenido' => $datos['contenido'],
                    'portada_url' => $portadaUrl,
                    'status' => $datos['status'] ?? 'borrador',
                    'author_id' => $userId,
                    'categoria_id' => $datos['categoria_id'],
                    'fecha_publicacion' => $datos['status'] === 'publicado' ? now() : null,
                ]);

                if (! empty($tags)) {
                    $articulo->tags()->sync($tags);
                }

                return $articulo;
            });
        } catch (Exception $e) {
            if ($portadaUrl && Storage::disk('public')->exists($portadaUrl)) {
                Storage::disk('public')->delete($portadaUrl);
            }
            Log::error('[ERROR] Error al crear articulo: '.$e->getMessage());
            throw new Exception('Error interno al publicar el articulo.');
        }
    }

    /**
     * Sube un archivo a la biblioteca de medios y registra sus metadatos.
     *
     * @param  array  $datos  Datos del archivo (colección, alt_text, descripción)
     * @param  UploadedFile  $archivo  Archivo a subir
     * @param  int  $userId  ID del usuario que sube
     */
    public function subirMedia(array $datos, UploadedFile $archivo, int $userId): Media
    {
        $coleccion = $datos['coleccion'] ?? 'documento';
        $subcarpeta = match ($coleccion) {
            'imagen' => 'imagenes',
            'video' => 'videos',
            default => 'documentos',
        };

        $path = $archivo->store("biblioteca/{$subcarpeta}/".date('Y'), 'public');
        $url = Storage::disk('public')->url($path);
        $dimensiones = null;

        if (str_starts_with($archivo->getMimeType(), 'image/')) {
            try {
                $imageSize = getimagesize($archivo->path());
                $dimensiones = ['ancho' => $imageSize[0], 'alto' => $imageSize[1]];
            } catch (Exception $e) {
                $dimensiones = null;
            }
        }

        return Media::create([
            'subido_por' => $userId,
            'nombre_original' => $archivo->getClientOriginalName(),
            'filename' => $url,
            'mime_type' => $archivo->getMimeType(),
            'peso_kb' => round($archivo->getSize() / 1024),
            'ancho' => $dimensiones['ancho'] ?? null,
            'alto' => $dimensiones['alto'] ?? null,
            'alt_text' => $datos['alt_text'] ?? null,
            'descripcion' => $datos['descripcion'] ?? null,
            'coleccion' => $coleccion,
            'metadatos' => null,
        ]);
    }
}
