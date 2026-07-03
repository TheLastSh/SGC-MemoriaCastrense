<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $nombre
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->nombre);
            }
        });
    }

    public function articulos(): BelongsToMany
    {
        return $this->belongsToMany(Articulo::class, 'articulo_tag');
    }
}
