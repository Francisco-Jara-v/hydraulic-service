<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacturaCompraDetalle extends Model
{
    use HasFactory, SoftDeletes;   

    protected $table = 'factura_compra_detalle';

    protected $fillable = [
        'factura_compra_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal',
        'afecto_iva',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'precio_unitario' => 'decimal:2',
            'descuento' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'afecto_iva' => 'boolean',
        ];
    }

    public function facturaCompra(): BelongsTo
    {
        return $this->belongsTo(
            Factura_Compra::class,
            'factura_compra_id'
        );
    }
}
