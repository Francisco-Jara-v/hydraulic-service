<?php

namespace App\Filament\Resources\FacturaCompras\Pages;

use App\Filament\Resources\FacturaCompras\FacturaCompraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFacturaCompra extends EditRecord
{
    protected static string $resource = FacturaCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
