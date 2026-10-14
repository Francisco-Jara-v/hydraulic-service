<?php

namespace App\Filament\Resources\EquipoItems\Pages;

use App\Filament\Resources\EquipoItems\EquipoItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEquipoItems extends ManageRecords
{
    protected static string $resource = EquipoItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
