<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Arriendo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Arriendo>
 */
class ArriendoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = fake()->dateTimeBetween('-2 months', 'now');

        return [
            'cliente_id' => Cliente::factory(),
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => fake()->optional(0.7)->dateTimeBetween(
                $fechaInicio,
                '+1 month'
            )?->format('Y-m-d'),
            'guia_despacho' => fake()->optional()->numerify('GD-######'),
            'monto_total' => fake()->randomFloat(2, 10000, 2000000),
            'ruta_contrato_pdf' => null,
            'estado' => fake()->randomElement([
                'pendiente',
                'activo',
                'finalizado',
                'anulado',
            ]),
            'observaciones' => fake()->optional()->sentence(),
        ];
    }
}
