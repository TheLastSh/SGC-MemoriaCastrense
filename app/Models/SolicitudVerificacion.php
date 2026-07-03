<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $tipo
 * @property string|null $documento_path
 * @property string $resena_curricular
 * @property string $status
 * @property int|null $revisado_por
 * @property string|null $motivo_rechazo
 * @property Carbon|null $fecha_verificacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
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
