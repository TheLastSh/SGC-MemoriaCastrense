<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tipo_verificado',
        'biografia',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'author_id');
    }

    public function hilos()
    {
        return $this->hasMany(Hilo::class, 'autor_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'autor_id');
    }

    public function solicitudVerificacion()
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
