<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

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
