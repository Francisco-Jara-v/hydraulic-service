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
        Schema::create('equipo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->string('codigo_interno')->unique()->nullable();
            $table->string('serie')->unique()->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->decimal('valor_arriendo', 15, 2)->nullable();
            $table->decimal('valor_garantia', 15, 2)->nullable();
            $table->enum('estado', ['Disponible', 'Arrendado', 'En Mantenimiento'])->default('Disponible');
            $table->text('observacion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_items');
    }
};
