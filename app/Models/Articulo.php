<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $titulo
 * @property string $slug
 * @property string|null $extracto
 * @property string $contenido
 * @property string|null $portada_url
 * @property string $status
 * @property int $author_id
 * @property int $categoria_id
 * @property string|null $fecha_publicacion
 * @property int $visitas
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Articulo extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'fecha_publicacion' => 'date:Y-m-d',
        'visitas' => 'integer',
    ];

    protected $fillable = [
        'titulo',
        'slug',
        'extracto',
        'contenido',
        'portada_url',
        'status',
        'author_id',
        'categoria_id',
        'fecha_publicacion',
        'visitas',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $articulo) {
            if (empty($articulo->slug)) {
                $articulo->slug = Str::slug($articulo->titulo).'-'.Str::random(5);
            }
        });
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'articulo_tag');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'articulo_id');
    }

    public function favoritadoPor(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos')->withTimestamps();
    }
}
