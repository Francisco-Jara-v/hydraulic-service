<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factura_Venta extends Model
{
    use HasFactory;

    protected $table = 'factura__ventas';


    protected $fillable = [
        'cliente_id',
        'tipo_documento',
        'folio',
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_pago',
        'forma_pago',
        'moneda',
        'neto',
        'iva',
        'exento',
        'total',
        'estado',
        'observaciones',
        'factura_referencia_id',
        'pdf_url',
    ];

    protected function casts(): array
    {
        return [
            'cliente_id' => 'integer',
            'folio' => 'integer',
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
            'fecha_pago' => 'date',
            'factura_referencia_id' => 'integer',
            'neto' => 'decimal:0',
            'iva' => 'decimal:0',
            'exento' => 'decimal:0',
            'total' => 'decimal:0',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(
            FacturaVentaDetalle::class,
            'factura_venta_id'
        );
    }

    public function facturaReferencia(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'factura_referencia_id'
        );
    }
}