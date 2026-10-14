<?php

namespace App\Filament\Resources\Equipos\Schemas;

use App\Models\EquipoEspecificacion;
use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EquipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('tipo')
                            ->required(),
                        Textarea::make('descripcion')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Repeater::make('especificaciones')
                    ->label('Especificaciones del tipo de equipo')
                    ->relationship()
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('unidad')
                            ->label('Unidad de medida')
                            ->maxLength(50),
                        Select::make('tipo')
                            ->label('Tipo de dato')
                            ->options([
                                'text' => 'Texto',
                                'number' => 'Número entero',
                                'decimal' => 'Número decimal',
                                'boolean' => 'Si / No',
                            ])
                            ->required()
                            ->default('text'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->addActionLabel('Agregar especificación')
                    ->collapsible()
                    ->rules([
                        fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                            if (is_array($value) && ! EquipoEspecificacion::tienenNombresNormalizadosUnicos($value)) {
                                $fail('Cada especificación debe tener un nombre distinto.');
                            }
                        },
                    ]),
            ]);
    }
}
