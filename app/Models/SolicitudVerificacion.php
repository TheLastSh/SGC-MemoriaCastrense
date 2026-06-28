<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudVerificacion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_verificacion';

    protected $casts = [
        'fecha_verificacion' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'tipo',
        'documento_path',
        'resena_curricular',
        'status',
        'revisado_por',
        'motivo_rechazo',
        'fecha_verificacion',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}
