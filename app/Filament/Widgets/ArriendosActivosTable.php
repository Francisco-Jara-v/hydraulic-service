<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Arriendo;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ArriendosActivosTable extends TableWidget
{
    protected static ?string $heading = 'Arriendos Activos';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Arriendo::query()
                    ->where('estado', 'Activo')
                    ->with('cliente')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('N.º contrato')
                    ->sortable(),

                TextColumn::make('cliente.company_name')
                    ->label('Cliente')
                    ->searchable(),

                TextColumn::make('fecha_inicio')
                    ->label('Fecha de inicio')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_fin')
                    ->label('Fecha de término')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('monto_total')
                    ->label('Monto total')
                    ->money('CLP', locale: 'es_CL')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('fecha_inicio', 'desc')
            ->paginated([5, 10, 25]);
    }
}
