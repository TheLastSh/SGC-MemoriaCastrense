<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $titulo
 * @property string $slug
 * @property string $contenido_inicial
 * @property int $autor_id
 * @property int $categoria_id
 * @property string $status
 * @property bool $fijado
 * @property int|null $ultima_respuesta_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Hilo extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'fijado' => 'boolean',
    ];

    protected $fillable = [
        'titulo',
        'slug',
        'contenido_inicial',
        'autor_id',
        'categoria_id',
        'status',
        'fijado',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $hilo) {
            if (empty($hilo->slug)) {
                $hilo->slug = Str::slug($hilo->titulo).'-'.Str::random(6);
            }
        });
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(ForoCategoria::class, 'categoria_id');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'hilo_id');
    }

    public function ultimaRespuesta(): BelongsTo
    {
        return $this->belongsTo(Respuesta::class, 'ultima_respuesta_id');
    }
}
