<?php

namespace App\Models;

use Database\Factories\FacturaCompraFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes   ;

class Factura_Compra extends Model
{
    /** @use HasFactory<FacturaCompraFactory> */
    use HasFactory;

        protected $table = 'factura__compras';

        protected $fillable = [
            'proveedor_nombre',
            'proveedor_rut',
            'proveedor_giro',
            'tipo_documento',
            'folio',
            'fecha_emision',
            'fecha_recepcion',
            'fecha_vencimiento',
            'forma_pago',
            'moneda',
            'neto',
            'iva',
            'exento',
            'total',
            'estado',
            'observaciones',
            'pdf_url',
        ];

        protected function casts(): array
        {
            return [
                'folio' => 'integer',
                'fecha_emision' => 'date',
                'fecha_recepcion' => 'date',
                'fecha_vencimiento' => 'date',
                'neto' => 'decimal:0',
                'iva' => 'decimal:0',
                'exento' => 'decimal:0',
                'total' => 'decimal:0',
            ];
        }

        public function detalles()
        {
            return $this->hasMany(
                FacturaCompraDetalle::class,
                'factura_compra_id'
            );
        }
}
