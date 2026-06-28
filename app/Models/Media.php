<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
