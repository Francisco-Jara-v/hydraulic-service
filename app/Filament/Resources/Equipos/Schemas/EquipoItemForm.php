<?php

namespace App\Filament\Resources\Equipos\Schemas;

use App\Models\Equipo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class EquipoItemForm
{
    public static function configure(
        Schema $schema,
        ?Equipo $equipo = null
    ): Schema {

        $componentes = [
            TextInput::make('codigo_interno')
                ->label('Código interno')
                ->required()
                ->maxLength(255),

            TextInput::make('serie')
                ->label('Número de serie')
                ->maxLength(255),

            TextInput::make('marca')
                ->label('Marca')
                ->maxLength(255),

            TextInput::make('modelo')
                ->label('Modelo')
                ->maxLength(255),

            TextInput::make('valor_arriendo')
                ->label('Valor de arriendo')
                ->numeric()
                ->prefix('$')
                ->required(),

            TextInput::make('valor_garantia')
                ->label('Valor de garantía')
                ->numeric()
                ->prefix('$')
                ->required(),

            Select::make('estado')
                ->label('Estado')
                ->options([
                    'Disponible' => 'Disponible',
                    'Arrendado' => 'Arrendado',
                    'En Mantenimiento' => 'En Mantenimiento',
                ])
                ->required()
                ->default('Disponible'),
        ];

        /*
         * Especificaciones dinámicas
         */
        if ($equipo) {

            foreach ($equipo->especificaciones as $especificacion) {

                $label = $especificacion->nombre;

                if ($especificacion->unidad) {
                    $label .= " ({$especificacion->unidad})";
                }

                $campo = match ($especificacion->tipo) {

                    'number' => TextInput::make(
                        "especificaciones.{$especificacion->clave}"
                    )
                        ->label($label)
                        ->integer(),

                    'decimal' => TextInput::make(
                        "especificaciones.{$especificacion->clave}"
                    )
                        ->label($label)
                        ->numeric(),

                    'boolean' => Toggle::make(
                        "especificaciones.{$especificacion->clave}"
                    )
                        ->label($label),

                    default => TextInput::make(
                        "especificaciones.{$especificacion->clave}"
                    )
                        ->label($label)
                        ->maxLength(255),
                };

                $componentes[] = $campo;
            }
        }

        $componentes[] = Textarea::make('observacion')
            ->label('Observación')
            ->rows(4)
            ->columnSpanFull();

        return $schema
            ->components([
                Grid::make(2)
                    ->schema($componentes),
            ]);
    }
}
