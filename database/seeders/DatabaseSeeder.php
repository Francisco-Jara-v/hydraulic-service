<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use App\Models\EquipoItem;
use App\Models\Equipo;
use App\Models\EquipoEspecificacion;
use App\Models\Arriendo;
use App\Models\ArriendoDetalle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Cliente::factory(10)->create();
        Arriendo::factory(10)->create();
        ArriendoDetalle::factory(10)->create();
        Equipo::factory(10)->create();
        EquipoItem::factory(10)->create();
        EquipoEspecificacion::factory(10)->create();
        /*User::factory(10)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
