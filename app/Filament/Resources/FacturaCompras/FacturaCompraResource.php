<?php

namespace App\Filament\Resources\FacturaCompras;

use App\Filament\Resources\FacturaCompras\Pages\CreateFacturaCompra;
use App\Filament\Resources\FacturaCompras\Pages\EditFacturaCompra;
use App\Filament\Resources\FacturaCompras\Pages\ListFacturaCompras;
use App\Filament\Resources\FacturaCompras\Schemas\FacturaCompraForm;
use App\Filament\Resources\FacturaCompras\Tables\FacturaComprasTable;
use App\Models\Factura_Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FacturaCompraResource extends Resource
{
    protected static ?string $model = Factura_Compra::class;

    protected static ?string $label = 'Factura de Compra';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?string $recordTitleAttribute = 'Factura_Compra';

    public static function form(Schema $schema): Schema
    {
        return FacturaCompraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacturaComprasTable::configure($table);
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
            'index' => ListFacturaCompras::route('/'),
            'create' => CreateFacturaCompra::route('/create'),
            'edit' => EditFacturaCompra::route('/{record}/edit'),
        ];
    }
}
