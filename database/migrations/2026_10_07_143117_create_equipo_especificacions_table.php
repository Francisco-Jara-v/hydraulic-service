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
        Schema::create('equipo_especificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('valor');
            $table->string('unidad')->nullable();
            $table->enum('tipo', ['text', 'number', 'decimal', 'boolean'])->default('text');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['equipo_id', 'valor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_especificacions');
    }
};
