<?php

namespace App\Filament\Resources\Arriendos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use App\Models\Arriendo;
use App\Models\Cliente;

use App\Models\EquipoItem;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class ArriendoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del cliente')->schema([
                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Cliente::pluck('company_name', 'id')->toArray())
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            $cliente = Cliente::find($state);
                            if ($cliente) {
                                $set('rut', $cliente->rut);
                                $set('city', $cliente->city ?? '');
                                $set('address', $cliente->address ?? '');
                            }
                        }),
                    TextInput::make('rut')->disabled(),
                    TextInput::make('address')->disabled(),
                    TextInput::make('city')->disabled(),
                ])
                ->columns(2)
                ->columnSpanFull(),
                Section::make('Detalles del Contrato')->schema([
                    DatePicker::make('fecha_inicio')
                        ->required(),
                    DatePicker::make('fecha_fin'),
                    TextInput::make('guia_despacho')
                        ->default(null),
                    TextInput::make('estado')
                        ->required()
                        ->default('Activo')
                        ->disabled(),
                ])
                ->columns(2)
                ->columnSpanFull(),
                
                Section::make('Detalle de los equipos')->schema([
                    Repeater::make('detalles')
                        ->relationship()
                        ->schema([
                            Select::make('equipo_items_id')
                                ->label('Equipo')
                                ->options(EquipoItem::pluck('nombre', 'equipo_id')->toArray())
                                ->required()
                                ->searchable()
                                ->columnSpanFull()
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set) {
                                    $equipo_item = EquipoItem::find($state);
                                    if ($equipo_item) {
                                        $set('valor_arriendo', $equipo_item->valor_arriendo ?? 0.0);
                                        $set('valor_garantia', $equipo_item->valor_garantia ?? 0.0);
                                        
                                    }
                                }),
                            TextInput::make('valor_arriendo')
                                ->numeric()
                                ->prefix('$')

                                ->disabled()
                                ->required(),
                            TextInput::make('valor_garantia')
                                ->numeric()
                                
                                ->disabled()
                                ->prefix('$')
                                ->columns(1),
                            Textarea::make('observacion')
                                ->label('Observación del equipo')
                                ->default(null)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ])
                ->columns(1)
                ->columnSpanFull(),
                Section::make('Observaciones y Archivos')->schema([
                    Textarea::make('observaciones')
                        ->default(null)
                        ->columnSpanFull(),
                    TextInput::make('ruta_contrato_pdf')
                        ->default(null)
                        ->hidden(),
                ])
                ->columns(1)
                ->columnSpanFull(), 


                TextInput::make('monto_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                
                TextInput::make('ruta_contrato_pdf')
                    ->default(null)
                    ->hidden(),
                Textarea::make('observaciones')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
