<?php

namespace App\Filament\Resources\Equipos\Pages;

use App\Filament\Resources\Equipos\EquipoResource;
use App\Filament\Resources\Equipos\Schemas\EquipoItemForm;
use App\Models\Equipo;
use App\Models\EquipoItem;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EquipoItems extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = EquipoResource::class;

    protected string $view = 'filament.resources.equipos.pages.equipo-items';

    public Equipo $record;

    public function table(Table $table): Table
    {
        $columnas = [
            Tables\Columns\TextColumn::make('codigo_interno')
                ->label('Código interno')
                ->searchable(),

            Tables\Columns\TextColumn::make('serie')
                ->label('Serie')
                ->searchable(),

            Tables\Columns\TextColumn::make('marca')
                ->label('Marca')
                ->searchable(),

            Tables\Columns\TextColumn::make('modelo')
                ->label('Modelo')
                ->searchable(),

            Tables\Columns\TextColumn::make('valor_arriendo')
                ->label('Valor arriendo')
                ->money('CLP'),

            Tables\Columns\TextColumn::make('valor_garantia')
                ->label('Valor garantía')
                ->money('CLP'),

            Tables\Columns\TextColumn::make('estado')
                ->label('Estado'),
        ];

        foreach ($this->record->especificaciones as $especificacion) {

            $label = $especificacion->nombre;

            if ($especificacion->unidad) {
                $label .= " ({$especificacion->unidad})";
            }

            $columnas[] = Tables\Columns\TextColumn::make(
                "especificaciones.{$especificacion->clave}"
            )
                ->label($label);
        }

        $columnas[] = Tables\Columns\TextColumn::make('observacion')
            ->label('Observación')
            ->limit(50);

        return $table
            ->query(
                EquipoItem::query()
                    ->where('equipo_id', $this->record->id)
            )
            ->columns($columnas)
            ->filters([
                TrashedFilter::make(),
            ])

            ->headerActions([
                Action::make('crear')
                    ->label('Nueva '.strtolower($this->record->tipo))
                    ->icon('heroicon-o-plus')
                    ->schema(
                        EquipoItemForm::configure(
                            Schema::make(),
                            $this->record
                        )->getComponents()
                    )
                    ->action(function (array $data): void {
                        EquipoItem::create([
                            'equipo_id' => $this->record->id,
                            'codigo_interno' => $data['codigo_interno'],
                            'serie' => $data['serie'] ?? null,
                            'marca' => $data['marca'] ?? null,
                            'modelo' => $data['modelo'] ?? null,
                            'valor_arriendo' => $data['valor_arriendo'],
                            'valor_garantia' => $data['valor_garantia'],
                            'estado' => $data['estado'],
                            'observacion' => $data['observacion'] ?? null,
                            'especificaciones' => $data['especificaciones'] ?? [],
                        ]);
                    })
                    ->successNotificationTitle('Unidad creada correctamente'),
            ])

            ->recordActions([
                Action::make('editar')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->fillForm(function (EquipoItem $record): array {
                        return [
                            'codigo_interno' => $record->codigo_interno,
                            'serie' => $record->serie,
                            'marca' => $record->marca,
                            'modelo' => $record->modelo,
                            'valor_arriendo' => $record->valor_arriendo,
                            'valor_garantia' => $record->valor_garantia,
                            'estado' => $record->estado,
                            'observacion' => $record->observacion,
                            'especificaciones' => $record->especificaciones ?? [],
                        ];
                    })
                    ->schema(
                        EquipoItemForm::configure(
                            Schema::make(),
                            $this->record
                        )->getComponents()
                    )
                    ->action(function (EquipoItem $record, array $data): void {
                        $record->update([
                            'codigo_interno' => $data['codigo_interno'],
                            'serie' => $data['serie'] ?? null,
                            'marca' => $data['marca'] ?? null,
                            'modelo' => $data['modelo'] ?? null,
                            'valor_arriendo' => $data['valor_arriendo'],
                            'valor_garantia' => $data['valor_garantia'],
                            'estado' => $data['estado'],
                            'observacion' => $data['observacion'] ?? null,
                            'especificaciones' => array_replace(
                                $record->especificaciones ?? [],
                                $data['especificaciones'] ?? [],
                            ),
                        ]);
                    })
                    ->successNotificationTitle('Unidad actualizada correctamente'),
                DeleteAction::make(),
                RestoreAction::make(),
            ]);
    }

    public function getTitle(): string
    {
        return 'Unidades de '.$this->record->tipo;
    }
}
