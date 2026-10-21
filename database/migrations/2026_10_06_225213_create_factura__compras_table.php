<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factura__compras', function (Blueprint $table) {
            $table->id();
            $table->string('proveedor_nombre');
            $table->string('proveedor_rut', 20)->nullable();
            $table->string('proveedor_giro')->nullable();
            $table->enum('tipo_documento', ['DECLARACION DE INGRESO', 'FACTURA', 'FACTURA ELECTRONICA', 'FACTURA DE COMPRA', 'FACTURA DE COMPRA ELECTRONICA', 'FACTURA DE INICIO', 'FACTURA EXENTA', 'FACTURA ELECTRONICA EXENTA', 'LIQUIDACION FACTURA', 'LIQUIDACION FACTURA EXENTA', 'NOTA DE CREDITO', 'NOTA DE CREDITO ELECTRONICA', 'NOTA DE DEBITO', 'NOTA DE DEBITO ELECTRONICA', 'SOLICITUD REGISTRO FACTURA']);
            $table->integer('folio')->unique('factura_compras_folio_unique');
            $table->date('fecha_emision');
            $table->date('fecha_recepcion')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('forma_pago', 50)->nullable();
            $table->string('moneda', 10)->default('CLP');
            $table->decimal('neto', 12)->default(0);
            $table->decimal('iva', 12)->default(0);
            $table->decimal('exento', 12)->default(0);
            $table->decimal('total', 12)->default(0);
            $table->string('estado', 45)->default('Ingresada');
            $table->text('observaciones')->nullable();
            $table->string('pdf_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura__compras');
    }
};
