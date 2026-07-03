<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $subido_por
 * @property string $nombre_original
 * @property string $filename
 * @property string $mime_type
 * @property float $peso_kb
 * @property int|null $ancho
 * @property int|null $alto
 * @property string|null $alt_text
 * @property string|null $descripcion
 * @property string $coleccion
 * @property array|null $metadatos
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'subido_por',
        'nombre_original',
        'filename',
        'mime_type',
        'peso_kb',
        'ancho',
        'alto',
        'alt_text',
        'descripcion',
        'coleccion',
        'metadatos',
    ];

    protected $casts = [
        'metadatos' => 'array',
    ];

    public function subidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}
