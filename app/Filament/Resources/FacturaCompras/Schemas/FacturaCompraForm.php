<?php

namespace App\Filament\Resources\FacturaCompras\Schemas;

use App\Models\Factura_Compra;
use Filament\Schemas\Schema;
use App\Models\FacturaCompraDetalle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class FacturaCompraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del proveedor')
                ->schema([
                    TextInput::make('proveedor_nombre')
                        ->label('Nombre del proveedor')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('proveedor_rut')
                        ->label('RUT del proveedor')
                        ->maxLength(20),

                    TextInput::make('proveedor_giro')
                        ->label('Giro del proveedor'),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Section::make('Documento tributario')
                ->schema([
                    Select::make('tipo_documento')
                        ->label('Tipo de documento')
                        ->options([
                            'DECLARACION DE INGRESO' => 'Declaración de ingreso',
                            'FACTURA' => 'Factura',
                            'FACTURA ELECTRONICA' => 'Factura electrónica',
                            'FACTURA DE COMPRA' => 'Factura de compra',
                            'FACTURA DE COMPRA ELECTRONICA' => 'Factura de compra electrónica',
                            'FACTURA DE INICIO' => 'Factura de inicio',
                            'FACTURA EXENTA' => 'Factura exenta',
                            'FACTURA ELECTRONICA EXENTA' => 'Factura electrónica exenta',
                            'LIQUIDACION FACTURA' => 'Liquidación factura',
                            'LIQUIDACION FACTURA EXENTA' => 'Liquidación factura exenta',
                            'NOTA DE CREDITO' => 'Nota de crédito',
                            'NOTA DE CREDITO ELECTRONICA' => 'Nota de crédito electrónica',
                            'NOTA DE DEBITO' => 'Nota de débito',
                            'NOTA DE DEBITO ELECTRONICA' => 'Nota de débito electrónica',
                            'SOLICITUD REGISTRO FACTURA' => 'Solicitud registro factura',
                        ])
                        ->required()
                        ->searchable(),

                    TextInput::make('folio')
                        ->label('Folio')
                        ->numeric()
                        ->required(),

                    DatePicker::make('fecha_emision')
                        ->label('Fecha de emisión')
                        ->required(),

                    DatePicker::make('fecha_recepcion')
                        ->label('Fecha de recepción'),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Fecha de vencimiento'),

                    TextInput::make('forma_pago')
                        ->label('Forma de pago')
                        ->maxLength(50),

                    TextInput::make('moneda')
                        ->label('Moneda')
                        ->default('CLP')
                        ->required()
                        ->maxLength(10),

                    Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'Ingresada' => 'Ingresada',
                            'Pagada' => 'Pagada',
                            'Pendiente' => 'Pendiente',
                            'Anulada' => 'Anulada',
                        ])
                        ->default('Ingresada')
                        ->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Section::make('Detalle de la factura')
                ->schema([
                    Repeater::make('detalles')
                        ->label('Productos o conceptos')
                        ->relationship()
                        ->schema([
                            TextInput::make('descripcion')
                                ->label('Descripción')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->default(1)
                                ->minValue(0.01)
                                ->required(),

                            TextInput::make('precio_unitario')
                                ->label('Precio unitario')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0)
                                ->required(),

                            TextInput::make('descuento')
                                ->label('Descuento')
                                ->numeric()
                                ->prefix('$')
                                ->default(0)
                                ->minValue(0)
                                ->required(),

                            Select::make('afecto_iva')
                                ->label('Afecto a IVA')
                                ->options([
                                    1 => 'Sí',
                                    0 => 'No, exento',
                                ])
                                ->hidden()
                                ->default(1)
                                ->required(),

                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('$')
                                ->readOnly()
                                ->dehydrated()
                                ->required(),
                        ])
                        ->columns(2)
                        ->defaultItems(1)
                        ->addActionLabel('Agregar línea')
                        ->reorderable(false)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),

            Section::make('Totales')
                ->schema([
                    TextInput::make('neto')
                        ->label('Neto')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->required(),

                    TextInput::make('iva')
                        ->label('IVA')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->required(),

                    TextInput::make('exento')
                        ->label('Exento')
                        ->numeric()
                        ->hidden()
                        ->prefix('$')
                        ->default(0)
                        ->required(),

                    TextInput::make('total')
                        ->label('Total')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->required(),
                ])
                ->columns(4)
                ->columnSpanFull(),

            Section::make('Observaciones y archivo')
                ->schema([
                    Textarea::make('observaciones')
                        ->label('Observaciones')
                        ->columnSpanFull(),

                    TextInput::make('pdf_url')
                        ->label('URL del PDF')
                        ->url()
                        ->hidden()
                        ->maxLength(255),
                ])
                ->columns(1)
                ->columnSpanFull(),
        ]);
    }
}
