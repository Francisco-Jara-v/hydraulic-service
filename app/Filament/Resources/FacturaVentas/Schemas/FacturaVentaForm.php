<?php

namespace App\Filament\Resources\FacturaVentas\Schemas;


use App\Models\Cliente;
use App\Models\Factura_Venta;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacturaVentaForm
{
    private static function tiposDocumento(): array
    {
        return [
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
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del cliente')
                ->schema([
                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(
                            Cliente::query()
                                ->orderBy('company_name')
                                ->pluck('company_name', 'id')
                                ->toArray()
                        )
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('tipo_documento')
                        ->label('Tipo de documento')
                        ->options(self::tiposDocumento())
                        ->searchable()
                        ->required(),

                    TextInput::make('folio')
                        ->label('Folio')
                        ->integer()
                        ->required()
                        ->unique(
                            table: 'factura__ventas',
                            column: 'folio',
                            ignoreRecord: true
                        ),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Section::make('Fechas y pago')
                ->schema([
                    DatePicker::make('fecha_emision')
                        ->label('Fecha de emisión')
                        ->required(),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Fecha de vencimiento')
                        ->afterOrEqual('fecha_emision'),

                    DatePicker::make('fecha_pago')
                        ->label('Fecha de pago')
                        ->afterOrEqual('fecha_emision'),

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
                            'EMITIDA' => 'Emitida',
                            'PAGADA' => 'Pagada',
                            'PENDIENTE' => 'Pendiente',
                            'ANULADA' => 'Anulada',
                        ])
                        ->default('EMITIDA')
                        ->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Section::make('Referencia del documento')
                ->schema([
                    Select::make('factura_referencia_id')
                        ->label('Documento relacionado')
                        ->options(
                            Factura_Venta::query()
                                ->orderByDesc('fecha_emision')
                                ->get()
                                ->mapWithKeys(fn (Factura_Venta $factura) => [
                                    $factura->id => "{$factura->tipo_documento} N.º {$factura->folio}",
                                ])
                                ->toArray()
                        )
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText(
                            'Utiliza este campo para relacionar una nota de crédito o débito con su documento original.'
                        ),
                ])
                ->columnSpanFull(),

            Section::make('Detalle de venta')
                ->schema([
                    Repeater::make('detalles')
                        ->relationship()
                        ->schema([
                            TextInput::make('descripcion')
                                ->label('Descripción')
                                ->required()
                                ->maxLength(255),

                            Textarea::make('detalle')
                                ->label('Detalle adicional')
                                ->rows(2),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->minValue(0.01)
                                ->default(1)
                                ->required(),

                            TextInput::make('precio_unitario')
                                ->label('Precio unitario')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('$')
                                ->required(),

                            TextInput::make('descuento')
                                ->label('Descuento')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('$')
                                ->default(0)
                                ->required(),

                            Select::make('afecto_iva')
                                ->label('Afecto a IVA')
                                ->options([
                                    1 => 'Sí',
                                    0 => 'No, exento',
                                ])
                                ->default(1)
                                ->required(),

                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('$')
                                ->default(0)
                                ->required(),
                        ])
                        ->columns(2)
                        ->defaultItems(1)
                        ->addActionLabel('Agregar detalle')
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
                        ->maxLength(255),
                ])
                ->columnSpanFull(),
        ]);
    }
}
