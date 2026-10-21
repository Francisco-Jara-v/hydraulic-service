<?php

namespace App\Filament\Resources\FacturaVentas\Pages;

use App\Filament\Resources\FacturaVentas\FacturaVentaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacturaVentas extends ListRecords
{
    protected static string $resource = FacturaVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
