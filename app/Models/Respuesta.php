<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respuesta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hilo_id',
        'autor_id',
        'contenido',
        'editado',
    ];

    public function hilo(): BelongsTo
    {
        return $this->belongsTo(Hilo::class, 'hilo_id');
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }
}
