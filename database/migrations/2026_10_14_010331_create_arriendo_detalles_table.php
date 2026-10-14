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
        Schema::create('arriendo_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arriendo_id')->constrained('arriendos')->cascadeOnDelete();
            $table->foreignId('equipo_items_id')->constrained('equipo_items')->cascadeOnDelete();
            $table->enum('estado',['borrador', 'activo', 'finalizado', 'anulado'])->default('borrador');
            $$table->decimal('valor_arriendo', 15, 2)->nullable();
            $table->decimal('valor_garantia', 15, 2)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arriendo_detalles');
    }
};
