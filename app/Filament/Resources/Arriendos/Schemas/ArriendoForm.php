<?php

namespace App\Filament\Resources\Arriendos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use App\Models\Arriendo;
use App\Models\Cliente;
use Carbon\Carbon;
use App\Models\EquipoItem;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class ArriendoForm
{

    private static function calcularMontoTotal($get, $set): void
    {
        $fechaInicio = $get('fecha_inicio');
        $fechaFin = $get('fecha_fin');
        $detalles = $get('detalles') ?? [];
    
        // Sin fecha de inicio, el total queda en cero.
        if (! $fechaInicio) {
            $set('monto_total', 0);
    
            return;
        }
    
        // Si no hay fecha de término, se cobra un día.
        $dias = 1;
    
        if ($fechaFin) {
            $inicio = Carbon::parse($fechaInicio)->startOfDay();
            $fin = Carbon::parse($fechaFin)->startOfDay();
    
            $dias = max(1, (int) $inicio->diffInDays($fin) + 1);
        }
    
        $total = collect($detalles)->sum(function ($detalle) use ($dias) {
            return (float) ($detalle['valor_arriendo'] ?? 0) * $dias;
        });
    
        $set('monto_total', $total);
    }
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
                    TextInput::make('rut')
                        ->label('RUT')
                        ->disabled(),
                    TextInput::make('address')
                        ->label('Dirección')
                        ->disabled(),
                    TextInput::make('city')
                        ->label('Ciudad')
                        ->disabled(),
                ])
                ->columns(2)
                ->columnSpanFull(),
                Section::make('Detalles del Contrato')->schema([
                    DatePicker::make('fecha_inicio')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            self::calcularMontoTotal($get, $set);
                        }),

                    DatePicker::make('fecha_fin')
                        ->live()
                        ->minDate(fn ($get) => $get('fecha_inicio'))
                        ->afterStateUpdated(function ($state, $get, $set) {
                            self::calcularMontoTotal($get, $set);
                        }),
                    TextInput::make('guia_despacho')
                        ->default(null),
                    TextInput::make('estado')
                        ->required()
                        ->default('Activo')
                        ->disabled(),
                ])
                ->columns(2)
                ->columnSpanFull(),
                
                
                Section::make('Detalle de los equipos')
                    ->schema([
                        Repeater::make('detalles')
                            ->relationship()
                            ->schema([
                                Select::make('equipo_item_id')
                                    ->label('Equipo')
                                    ->options(
                                        EquipoItem::query()
                                            ->with('equipo')
                                            ->where('estado', 'disponible')
                                            ->get()
                                            ->mapWithKeys(fn (EquipoItem $item) => [
                                                $item->id => sprintf(
                                                    '%s - %s - %s',
                                                    $item->equipo?->tipo ?? 'Equipo',
                                                    $item->codigo_interno,
                                                    $item->marca ?? 'Sin marca'
                                                ),
                                            ])
                                            ->toArray()
                                    )
                                    ->required()
                                    ->searchable()
                                    ->live()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $equipoItem = EquipoItem::find($state);
                                        $set(
                                            'valor_arriendo',
                                            $equipoItem?->valor_arriendo ?? 0
                                        );
                                        $set(
                                            'valor_garantia',
                                            $equipoItem?->valor_garantia ?? 0
                                        );
                                    }),
                                TextInput::make('valor_arriendo')
                                    ->label('Precio diario')
                                        ->numeric()
                                        ->prefix('$')
                                        ->readOnly()
                                        ->required(),

                                    TextInput::make('valor_garantia')
                                        ->label('Garantía')
                                        ->numeric()
                                        ->prefix('$')
                                        ->readOnly(),

                                    Textarea::make('observaciones')
                                        ->label('Observación del equipo')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),
                        ])
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            self::calcularMontoTotal($get, $set);
                        })
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
                    ->default(0.0)
                    ->columnSpanFull(),
                
                TextInput::make('ruta_contrato_pdf')
                    ->default(null)
                    ->hidden(),
                Textarea::make('observaciones')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
