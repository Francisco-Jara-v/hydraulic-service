<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArriendoDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\ArriendoDetalleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'arriendo_id',
        'equipo_items_id',
        'valor_arriendo',
        'valor_garantia',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'valor_arriendo' => 'decimal:2',
            'valor_garantia' => 'decimal:2',
        ];
    }

    public function arriendo(): BelongsTo
    {
        return $this->belongsTo(Arriendo::class, 'arriendo_id');
    }

    public function equipoItem(): BelongsTo
    {
        return $this->belongsTo(EquipoItem::class, 'equipo_items_id');
    }
}
