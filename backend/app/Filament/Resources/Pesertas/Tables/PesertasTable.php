<?php

namespace App\Filament\Resources\Pesertas\Tables;

use App\Models\Peserta;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PesertasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('institusi')
                    ->label('Kampus / Institusi')
                    ->searchable()
                    ->sortable()
                    ->limit(36),

                TextColumn::make('jenis_peserta')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pic'       => 'info',
                        'mahasiswa' => 'success',
                        'siswa', 'siswa_smk' => 'warning',
                        default     => 'gray',
                    }),

                TextColumn::make('nomor_identitas')
                    ->label('NIM / NISN / NIK')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->placeholder('-'),

                TextColumn::make('karyas_as_leader_count')
                    ->label('Karya')
                    ->counts('karyasAsLeader')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jenis_peserta')
                    ->label('Kategori Peserta')
                    ->options([
                        'pic'       => 'PIC Kampus',
                        'mahasiswa' => 'Mahasiswa',
                        'siswa'     => 'Siswa SMA',
                        'siswa_smk' => 'Siswa SMK',
                        'umum'      => 'Inovator Umum',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
