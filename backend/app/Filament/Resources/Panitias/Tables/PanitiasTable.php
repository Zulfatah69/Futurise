<?php

namespace App\Filament\Resources\Panitias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PanitiasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Nama Panitia')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('no_telepon')
                    ->label('Telepon / WA')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('institusi')
                    ->label('Institusi')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('-'),

                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'internal'          => 'primary',
                        'relawan'           => 'info',
                        'profesional'       => 'warning',
                        'mahasiswa_magang'  => 'success',
                        default             => 'gray',
                    }),

                TextColumn::make('ukuran_kaos')
                    ->label('Kaos')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),

                IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis')
                    ->label('Jenis Panitia')
                    ->options([
                        'internal'          => 'Internal Penyelenggara',
                        'relawan'           => 'Relawan (Volunteer)',
                        'profesional'       => 'Profesional / Vendor',
                        'mahasiswa_magang'  => 'Mahasiswa Magang',
                    ]),

                SelectFilter::make('aktif')
                    ->label('Status Keaktifan')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
