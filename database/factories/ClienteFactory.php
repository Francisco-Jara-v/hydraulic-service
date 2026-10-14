<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'giro' => fake()->randomElement([
                'Servicios de ingeniería',
                'Construcción',
                'Transporte',
                'Comercio',
                'Servicios informáticos',
                'Arriendo de maquinaria',
                'Servicios industriales',
            ]),
            'rut' => fake()->numerify('########-#'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+56 9 ########'),
        ];
    }
}
