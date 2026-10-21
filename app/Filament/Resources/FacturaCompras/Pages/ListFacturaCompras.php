<?php

namespace App\Filament\Resources\FacturaCompras\Pages;

use App\Filament\Resources\FacturaCompras\FacturaCompraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacturaCompras extends ListRecords
{
    protected static string $resource = FacturaCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
