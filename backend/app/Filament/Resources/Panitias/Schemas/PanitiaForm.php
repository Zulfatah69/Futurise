<?php

namespace App\Filament\Resources\Panitias\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PanitiaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(100),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),

                TextInput::make('no_telepon')
                    ->label('Nomor Telepon / WhatsApp')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('institusi')
                    ->label('Asal Perguruan Tinggi / Instansi')
                    ->maxLength(150),

                Select::make('jenis')
                    ->label('Status Kepanitiaan')
                    ->options([
                        'internal'          => 'Internal Penyelenggara',
                        'relawan'           => 'Relawan (Volunteer)',
                        'profesional'       => 'Profesional / Vendor',
                        'mahasiswa_magang'  => 'Mahasiswa Magang',
                    ])
                    ->default('relawan')
                    ->required(),

                Select::make('ukuran_kaos')
                    ->label('Ukuran Kaos Seragam')
                    ->options([
                        'XS'   => 'XS',
                        'S'    => 'S',
                        'M'    => 'M',
                        'L'    => 'L',
                        'XL'   => 'XL',
                        'XXL'  => 'XXL',
                        'XXXL' => 'XXXL',
                    ]),

                TextInput::make('kontak_darurat_nama')
                    ->label('Nama Kontak Darurat')
                    ->maxLength(100),

                TextInput::make('kontak_darurat_telepon')
                    ->label('Nomor Kontak Darurat')
                    ->tel()
                    ->maxLength(20),

                Toggle::make('aktif')
                    ->label('Status Panitia Aktif')
                    ->default(true),
            ]);
    }
}
