<?php

namespace App\Filament\Resources\Pesertas\Pages;

use App\Filament\Concerns\StreamsCsvExport;
use App\Filament\Resources\Pesertas\PesertaResource;
use App\Models\Peserta;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPesertas extends ListRecords
{
    use StreamsCsvExport;

    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Ekspor CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->authorize('viewAny')
                ->action(function () {
                    $rows = Peserta::query()
                        ->withCount('karyasAsLeader')
                        ->orderBy('institusi')
                        ->orderBy('nama_lengkap')
                        ->get()
                        ->map(fn (Peserta $peserta): array => [
                            $peserta->id,
                            $peserta->nama_lengkap,
                            $peserta->email,
                            $peserta->no_telepon,
                            $peserta->institusi,
                            $peserta->jenis_peserta,
                            $peserta->nomor_identitas,
                            $peserta->karyas_as_leader_count,
                            $peserta->created_at?->toDateTimeString(),
                        ]);

                    return $this->streamCsvDownload(
                        'peserta-futurise-' . now()->format('Ymd-His') . '.csv',
                        ['ID', 'Nama Lengkap', 'Email', 'No Telepon', 'Institusi', 'Jenis Peserta', 'Nomor Identitas', 'Jumlah Karya', 'Tanggal Daftar'],
                        $rows
                    );
                }),
            CreateAction::make(),
        ];
    }
}
