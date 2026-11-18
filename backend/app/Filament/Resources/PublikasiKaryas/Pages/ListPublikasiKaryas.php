<?php

namespace App\Filament\Resources\PublikasiKaryas\Pages;

use App\Filament\Resources\PublikasiKaryas\PublikasiKaryaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPublikasiKaryas extends ListRecords
{
    protected static string $resource = PublikasiKaryaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
