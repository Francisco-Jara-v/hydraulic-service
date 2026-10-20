<?php

namespace App\Filament\Resources\Arriendos\Pages;

use App\Filament\Resources\Arriendos\ArriendoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArriendos extends ListRecords
{
    protected static string $resource = ArriendoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
