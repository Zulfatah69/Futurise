<?php

namespace App\Filament\Resources\Panitias\Pages;

use App\Filament\Concerns\StreamsCsvExport;
use App\Filament\Resources\Panitias\PanitiaResource;
use App\Models\Panitia;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPanitias extends ListRecords
{
    use StreamsCsvExport;

    protected static string $resource = PanitiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Ekspor CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->authorize('viewAny')
                ->action(function () {
                    $rows = Panitia::query()
                        ->orderBy('nama_lengkap')
                        ->get()
                        ->map(fn (Panitia $panitia): array => [
                            $panitia->id,
                            $panitia->nama_lengkap,
                            $panitia->email,
                            $panitia->no_telepon,
                            $panitia->institusi,
                            $panitia->jenis,
                            $panitia->ukuran_kaos,
                            $panitia->aktif ? 'Aktif' : 'Nonaktif',
                        ]);

                    return $this->streamCsvDownload(
                        'panitia-futurise-' . now()->format('Ymd-His') . '.csv',
                        ['ID', 'Nama Lengkap', 'Email', 'No Telepon', 'Institusi', 'Jenis', 'Ukuran Kaos', 'Status'],
                        $rows
                    );
                }),
            CreateAction::make(),
        ];
    }
}
