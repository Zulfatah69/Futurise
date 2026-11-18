<?php

namespace App\Filament\Resources\Karyas\Tables;

use App\Models\Karya;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->requiresConfirmation()
                    ->modalHeading('Nyatakan Lolos Kurasi?')
                    ->modalDescription('Karya ini akan dinyatakan lolos kurasi tahap administrasi dan berhak dinilai oleh juri.')
                    ->visible(fn (Karya $record): bool => in_array($record->status, ['diajukan', 'dalam_pemeriksaan', 'perlu_revisi']))
                    ->action(fn (Karya $record) => $record->update(['status' => 'lolos'])),

                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kembalikan untuk Revisi?')
                    ->modalDescription('Peserta akan dapat memperbaiki proposal dan berkas karyanya.')
                    ->visible(fn (Karya $record): bool => in_array($record->status, ['diajukan', 'dalam_pemeriksaan']))
                    ->action(fn (Karya $record) => $record->update(['status' => 'perlu_revisi'])),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
