<?php

namespace App\Filament\Resources\Arriendos;

use App\Filament\Resources\Arriendos\Pages\CreateArriendo;
use App\Filament\Resources\Arriendos\Pages\EditArriendo;
use App\Filament\Resources\Arriendos\Pages\ListArriendos;
use App\Filament\Resources\Arriendos\Schemas\ArriendoForm;
use App\Filament\Resources\Arriendos\Tables\ArriendosTable;
use App\Models\Arriendo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArriendoResource extends Resource
{
    protected static ?string $model = Arriendo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Arriendo';

    public static function form(Schema $schema): Schema
    {
        return ArriendoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArriendosTable::configure($table);
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
            'index' => ListArriendos::route('/'),
            'create' => CreateArriendo::route('/create'),
            'edit' => EditArriendo::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
