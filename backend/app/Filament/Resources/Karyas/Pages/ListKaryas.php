<?php

namespace App\Filament\Resources\Karyas\Pages;

use App\Filament\Concerns\StreamsCsvExport;
use App\Filament\Resources\Karyas\KaryaResource;
use App\Models\Karya;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListKaryas extends ListRecords
{
    use StreamsCsvExport;

    protected static string $resource = KaryaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Ekspor CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->authorize('viewAny')
                ->action(function () {
                    $rows = Karya::query()
                        ->with(['festival:id,nama', 'submitter:id,nama_lengkap,institusi'])
                        ->latest()
                        ->get()
                        ->map(fn (Karya $karya): array => [
                            $karya->id,
                            $karya->festival?->nama,
                            $karya->judul,
                            $karya->kategori,
                            $karya->teknologi,
                            $karya->submitter?->nama_lengkap,
                            $karya->submitter?->institusi,
                            $karya->status,
                            $karya->tahap_kesiapan,
                            $karya->persetujuan_publikasi ? 'Ya' : 'Tidak',
                            $karya->catatan_kurasi,
                            $karya->created_at?->toDateTimeString(),
                        ]);

                    return $this->streamCsvDownload(
                        'karya-futurise-' . now()->format('Ymd-His') . '.csv',
                        ['ID', 'Festival', 'Judul Karya', 'Kategori', 'Teknologi', 'Ketua / Pengusul', 'Institusi', 'Status Kurasi', 'Tahap Kesiapan', 'Izin Publikasi', 'Catatan Kurasi', 'Tanggal Submit'],
                        $rows
                    );
                }),
            CreateAction::make(),
        ];
    }
}
