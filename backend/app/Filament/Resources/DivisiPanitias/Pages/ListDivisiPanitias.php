<?php

namespace App\Filament\Resources\DivisiPanitias\Pages;

use App\Filament\Resources\DivisiPanitias\DivisiPanitiaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDivisiPanitias extends ListRecords
{
    protected static string $resource = DivisiPanitiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
