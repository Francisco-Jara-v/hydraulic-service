<?php

namespace Database\Factories;

use App\Models\EquipoEspecificacion;
use App\Models\Equipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipoEspecificacion>
 */
class EquipoEspecificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'equipo_id' => Equipo::factory(),
            'nombre' => fake()->randomElement([
                'Voltaje',
                'Potencia',
                'Presión máxima',
                'Capacidad',
                'Amperaje máximo',
            ]),
            'clave' => fake()->optional()->word(),
            'valor' => fake()->optional()->word(),
            'unidad' => fake()->randomElement([
                'V',
                'W',
                'bar',
                'kg',
                'A',
            ]),
            'tipo' => fake()->randomElement([
                'text',
                'number',
                'decimal',
                'boolean',
            ]),
        ];
    }
}
