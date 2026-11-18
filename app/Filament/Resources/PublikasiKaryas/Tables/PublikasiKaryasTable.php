<?php

namespace App\Filament\Resources\PublikasiKaryas\Tables;

use App\Models\PublikasiKarya;
use App\Services\PublikasiService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PublikasiKaryasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul_publik')
                    ->label('Judul Publikasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('karya.judul')
                    ->label('Karya Sumber')
                    ->searchable()
                    ->limit(35)
                    ->color('gray'),

                TextColumn::make('kanal')
                    ->label('Kanal')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'                => 'gray',
                        'menunggu_persetujuan' => 'warning',
                        'terjadwal'            => 'info',
                        'terbit'               => 'success',
                        'ditarik'              => 'danger',
                        default                => 'gray',
                    }),

                IconColumn::make('is_unggulan')
                    ->label('Unggulan')
                    ->boolean(),

                TextColumn::make('jumlah_dilihat')
                    ->label('Tayangan')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('jumlah_suka')
                    ->label('Apresiasi')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('tanggal_terbit')
                    ->label('Tgl Terbit')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'                => 'Draft',
                        'menunggu_persetujuan' => 'Menunggu Persetujuan',
                        'terjadwal'            => 'Terjadwal',
                        'terbit'               => 'Terbit',
                        'ditarik'              => 'Ditarik',
                    ]),
                SelectFilter::make('kanal')
                    ->options([
                        'website'       => 'Website',
                        'galeri_online' => 'Galeri Online',
                        'media_sosial'  => 'Media Sosial',
                        'katalog_cetak' => 'Katalog Cetak',
                        'layar_venue'   => 'Layar Venue',
                    ]),
            ])
            ->recordActions([
                Action::make('approvePublish')
                    ->label('Setujui & Terbitkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Terbitkan Karya ke Showcase?')
                    ->modalDescription('Karya ini akan disetujui kurasinya dan langsung tayang di galeri showcase publik.')
                    ->visible(fn (PublikasiKarya $record): bool => in_array($record->status, ['draft', 'menunggu_persetujuan', 'terjadwal', 'ditarik']))
                    ->action(function (PublikasiKarya $record) {
                        app(PublikasiService::class)->publish($record, auth()->user(), 'Disetujui dan diterbitkan via Admin Panel.');
                    }),

                Action::make('withdrawPublication')
                    ->label('Tarik dari Etalase')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->form([
                        TextInput::make('alasan')
                            ->label('Alasan Penarikan')
                            ->required(),
                    ])
                    ->modalHeading('Tarik Publikasi Karya?')
                    ->modalDescription('Karya tidak akan ditampilkan lagi pada katalog pameran publik.')
                    ->visible(fn (PublikasiKarya $record): bool => $record->status === 'terbit')
                    ->action(function (PublikasiKarya $record, array $data) {
                        app(PublikasiService::class)->withdraw($record, auth()->user(), $data['alasan']);
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
