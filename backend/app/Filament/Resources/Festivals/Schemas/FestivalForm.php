<?php

namespace App\Filament\Resources\Festivals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FestivalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Festival')
                    ->required()
                    ->maxLength(150),

                TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(150),

                TextInput::make('edisi')
                    ->label('Edisi Acara')
                    ->placeholder('Misal: Edisi 1 (2026)')
                    ->required()
                    ->maxLength(50),

                TextInput::make('tahun')
                    ->label('Tahun')
                    ->numeric()
                    ->required(),

                TextInput::make('tema')
                    ->label('Tema Festival')
                    ->maxLength(255),

                TextInput::make('tagline')
                    ->label('Tagline')
                    ->maxLength(255),

                TextInput::make('penyelenggara')
                    ->label('Penyelenggara')
                    ->default('APTIKOM Jawa Barat')
                    ->maxLength(150),

                Select::make('format')
                    ->label('Format Penyelenggaraan')
                    ->options([
                        'luring'  => 'Luring (Tatap Muka)',
                        'daring'  => 'Daring (Online)',
                        'hibrida' => 'Hibrida (Hybrid)',
                    ])
                    ->default('luring')
                    ->required(),

                TextInput::make('lokasi_utama')
                    ->label('Lokasi Utama')
                    ->placeholder('Misal: Bandung, Jawa Barat')
                    ->maxLength(255),

                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),

                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),

                Toggle::make('aktif')
                    ->label('Status Aktif')
                    ->default(true),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Festival')
                    ->rows(4)
                    ->columnSpanFull(),

                TextInput::make('website_url')
                    ->label('Website Resmi')
                    ->url()
                    ->maxLength(255),

                TextInput::make('email_kontak')
                    ->label('Email Kontak')
                    ->email()
                    ->maxLength(100),
            ]);
    }
}
