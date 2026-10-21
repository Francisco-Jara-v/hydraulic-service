<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacturaVentaDetalle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'factura_venta_detalle';

    protected $fillable = [
        'factura_venta_id',
        'descripcion',
        'detalle',
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

    public function facturaVenta(): BelongsTo
    {
        return $this->belongsTo(
            Factura_Venta::class,
            'factura_venta_id'
        );
    }
}

