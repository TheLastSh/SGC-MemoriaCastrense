<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $role
 * @property string|null $tipo_verificado
 * @property string|null $biografia
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'biografia',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    public function isPublicador(): bool
    {
        return $this->role === 'publicador' || $this->isAdmin();
    }

    public function isUsuario(): bool
    {
        return $this->role === 'usuario';
    }

    public function isVerificado(): bool
    {
        return $this->role === 'publicador' || $this->role === 'administrador';
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }

    public function articulos(): HasMany
    {
        return $this->hasMany(Articulo::class, 'author_id');
    }

    public function hilos(): HasMany
    {
        return $this->hasMany(Hilo::class, 'autor_id');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'autor_id');
    }

    public function solicitudVerificacion(): HasOne
    {
        return $this->hasOne(SolicitudVerificacion::class, 'user_id');
    }

    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(Articulo::class, 'favoritos')->withTimestamps();
    }

    public function hasFavorito(Articulo $articulo): bool
    {
        return $this->favoritos()->where('articulo_id', $articulo->id)->exists();
    }
}
