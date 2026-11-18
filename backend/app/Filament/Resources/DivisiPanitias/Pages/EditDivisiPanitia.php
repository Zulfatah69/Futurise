<?php

namespace App\Filament\Resources\DivisiPanitias\Pages;

use App\Filament\Resources\DivisiPanitias\DivisiPanitiaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDivisiPanitia extends EditRecord
{
    protected static string $resource = DivisiPanitiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
