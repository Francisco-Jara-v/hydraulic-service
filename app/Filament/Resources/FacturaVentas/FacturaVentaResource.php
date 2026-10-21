<?php

namespace App\Filament\Resources\FacturaVentas;

use App\Filament\Resources\FacturaVentas\Pages\CreateFacturaVenta;
use App\Filament\Resources\FacturaVentas\Pages\EditFacturaVenta;
use App\Filament\Resources\FacturaVentas\Pages\ListFacturaVentas;
use App\Filament\Resources\FacturaVentas\Schemas\FacturaVentaForm;
use App\Filament\Resources\FacturaVentas\Tables\FacturaVentasTable;
use App\Models\Factura_Venta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FacturaVentaResource extends Resource
{
    protected static ?string $model = Factura_Venta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Factura_Venta';

    public static function form(Schema $schema): Schema
    {
        return FacturaVentaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacturaVentasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFacturaVentas::route('/'),
            'create' => CreateFacturaVenta::route('/create'),
            'edit' => EditFacturaVenta::route('/{record}/edit'),
        ];
    }
}
