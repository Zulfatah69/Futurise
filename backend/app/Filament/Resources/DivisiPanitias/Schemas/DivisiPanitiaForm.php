<?php

namespace App\Filament\Resources\DivisiPanitias\Schemas;

use App\Models\Festival;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DivisiPanitiaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('festival_id')
                    ->label('Edisi Festival')
                    ->options(Festival::pluck('nama', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('nama')
                    ->label('Nama Divisi')
                    ->required()
                    ->maxLength(100),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Tugas & Tanggung Jawab Divisi')
                    ->rows(3)
                    ->maxLength(255),

                TextInput::make('kuota_anggota')
                    ->label('Kuota Maksimal Anggota')
                    ->numeric()
                    ->minValue(1),

                TextInput::make('urutan')
                    ->label('Nomor Urutan Tampilan')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
