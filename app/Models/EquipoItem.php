<?php

namespace App\Models;

use Database\Factories\EquipoItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipoItem extends Model
{
    /** @use HasFactory<EquipoItemFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'equipo_items';

    protected $fillable = [
        'nombre',
        'equipo_id',
        'codigo_interno',
        'serie',
        'marca',
        'modelo',
        'valor_arriendo',
        'valor_garantia',
        'estado',
        'observacion',
        'especificaciones',
    ];

    protected $casts = [
        'especificaciones' => 'array',
        'valor_arriendo' => 'decimal:2',
        'valor_garantia' => 'decimal:2',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id', 'id');
    }
}
