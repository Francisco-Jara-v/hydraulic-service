<?php

namespace Database\Factories;

use App\Models\ArriendoDetalle;
use App\Models\Arriendo;
use App\Models\EquipoItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArriendoDetalle>
 */
class ArriendoDetalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'arriendo_id' => Arriendo::factory(),
            'equipo_items_id' => EquipoItem::factory(),
            'valor_arriendo' => fake()->randomFloat(2, 5000, 500000),
            'valor_garantia' => fake()->randomFloat(2, 0, 2000000),
            'observacion' => fake()->optional()->sentence(),
        ];
    }
}
