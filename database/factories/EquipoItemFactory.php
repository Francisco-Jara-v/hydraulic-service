<?php

namespace Database\Factories;

use App\Models\EquipoItem;
use App\Models\Equipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipoItem>
 */
class EquipoItemFactory extends Factory
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
            'nombre' => fake()->word(),
            'codigo_interno' => fake()->unique()->bothify('EQ-#####'),
            'serie' => fake()->optional()->bothify('SER-########'),
            'marca' => fake()->randomElement([
                'Bosch',
                'Makita',
                'DeWalt',
                'Genérica',
            ]),
            'modelo' => fake()->bothify('MOD-###'),
            'valor_arriendo' => fake()->randomFloat(2, 5000, 500000),
            'valor_garantia' => fake()->randomFloat(2, 0, 2000000),
            'estado' => 'disponible',
            'observacion' => fake()->optional()->sentence(),
            'especificaciones' => [
                'voltaje' => fake()->randomElement([110, 220, 380]),
                'potencia' => fake()->numberBetween(500, 5000),
            ],
        ];
    }
}
