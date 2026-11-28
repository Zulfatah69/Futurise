<?php

namespace App\Filament\Resources\Karyas\Tables;

use App\Models\Karya;
use App\Services\KaryaService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class KaryasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Karya')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->wrap(),

                TextColumn::make('submitter.nama_lengkap')
                    ->label('Ketua Tim')
                    ->searchable(),

                TextColumn::make('submitter.institusi')
                    ->label('Institusi / Kampus')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'             => 'gray',
                        'diajukan'          => 'info',
                        'dalam_pemeriksaan' => 'warning',
                        'perlu_revisi'      => 'warning',
                        'lolos'             => 'success',
                        'tidak_lolos'       => 'danger',
                        default             => 'gray',
                    }),

                TextColumn::make('skor_akhir')
                    ->label('Skor')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Tgl Submit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('approveKurasi')
                    ->label('Lolos Kurasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->authorize('update')
                    ->requiresConfirmation()
                    ->modalHeading('Nyatakan Lolos Kurasi?')
                    ->modalDescription('Karya ini akan dinyatakan lolos kurasi tahap administrasi dan berhak dinilai oleh juri.')
                    ->visible(fn (Karya $record): bool => in_array($record->status, ['diajukan', 'dalam_pemeriksaan', 'perlu_revisi']))
                    ->action(function (Karya $record): void {
                        DB::transaction(function () use ($record): void {
                            $statusLama = $record->status;
                            $record->update(['status' => 'lolos']);
                            app(KaryaService::class)->recordRevision($record, auth()->id(), $statusLama, 'lolos', 'Dinyatakan lolos kurasi via panel panitia.');
                        });
                        app(KaryaService::class)->notifyCurationStatus($record->fresh('submitter'), 'lolos');
                    }),

                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->authorize('update')
                    ->modalHeading('Kembalikan untuk Revisi?')
                    ->modalDescription('Tulis catatan perbaikan yang wajib ditindaklanjuti peserta sebelum pengajuan ulang (BR-09).')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Catatan Revisi untuk Peserta')
                            ->rows(3)
                            ->required()
                            ->maxLength(500),
                    ])
                    ->visible(fn (Karya $record): bool => in_array($record->status, ['diajukan', 'dalam_pemeriksaan']))
                    ->action(function (Karya $record, array $data): void {
                        DB::transaction(function () use ($record, $data): void {
                            $statusLama = $record->status;
                            $record->update([
                                'status'         => 'perlu_revisi',
                                'catatan_kurasi' => $data['catatan'],
                            ]);
                            app(KaryaService::class)->recordRevision($record, auth()->id(), $statusLama, 'perlu_revisi', $data['catatan']);
                        });
                        app(KaryaService::class)->notifyCurationStatus($record->fresh('submitter'), 'perlu_revisi', $data['catatan']);
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
