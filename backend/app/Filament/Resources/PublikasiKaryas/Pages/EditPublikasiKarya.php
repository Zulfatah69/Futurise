<?php

namespace App\Filament\Resources\PublikasiKaryas\Pages;

use App\Filament\Resources\PublikasiKaryas\PublikasiKaryaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPublikasiKarya extends EditRecord
{
    protected static string $resource = PublikasiKaryaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
