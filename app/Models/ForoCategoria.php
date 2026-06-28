<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ForoCategoria extends Model
{
    use HasFactory;

    protected $table = 'foro_categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'orden',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }

    public function hilos(): HasMany
    {
        return $this->hasMany(Hilo::class, 'categoria_id');
    }
}
