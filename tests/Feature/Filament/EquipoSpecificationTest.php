<?php

use App\Models\Equipo;
use App\Models\EquipoEspecificacion;

it('keeps a specification key unchanged when its name is edited', function () {
    $equipo = Equipo::query()->create([
        'tipo' => 'Bomba',
        'descripcion' => 'Bomba hidráulica',
    ]);

    $especificacion = $equipo->especificaciones()->create([
        'nombre' => 'Presión máxima',
        'tipo' => 'number',
    ]);

    $clave = $especificacion->clave;

    $especificacion->update([
        'nombre' => 'Presión de trabajo',
    ]);

    expect($clave)->toStartWith('spec_');
    expect($especificacion->refresh()->clave)->toBe($clave);
});

it('rejects specification names that normalize to the same label key', function () {
    expect(EquipoEspecificacion::tienenNombresNormalizadosUnicos([
        ['nombre' => 'Presión'],
        ['nombre' => 'Presion'],
    ]))->toBeFalse();

    expect(EquipoEspecificacion::tienenNombresNormalizadosUnicos([
        ['nombre' => 'Presión máxima'],
        ['nombre' => 'Presión mínima'],
    ]))->toBeTrue();
});
