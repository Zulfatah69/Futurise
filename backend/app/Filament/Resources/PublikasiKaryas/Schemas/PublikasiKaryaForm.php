<?php

namespace App\Filament\Resources\PublikasiKaryas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PublikasiKaryaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('karya_id')
                    ->label('Karya Sumber')
                    ->relationship('karya', 'judul')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('kanal')
                    ->label('Kanal Publikasi')
                    ->options([
                        'website'       => 'Website',
                        'galeri_online' => 'Galeri Online',
                        'media_sosial'  => 'Media Sosial',
                        'katalog_cetak' => 'Katalog Cetak',
                        'layar_venue'   => 'Layar Venue',
                    ])
                    ->default('website')
                    ->required(),

                TextInput::make('judul_publik')
                    ->label('Judul Publikasi')
                    ->required()
                    ->maxLength(200),

                TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(200),

                Textarea::make('ringkasan_publik')
                    ->label('Ringkasan / Sinopsis Karya')
                    ->rows(3)
                    ->maxLength(500),

                Select::make('status')
                    ->label('Status Publikasi')
                    ->options([
                        'draft'                => 'Draft',
                        'menunggu_persetujuan' => 'Menunggu Persetujuan',
                        'terjadwal'            => 'Terjadwal',
                        'terbit'               => 'Terbit',
                        'ditarik'              => 'Ditarik',
                    ])
                    ->default('draft')
                    ->required(),

                Toggle::make('is_unggulan')
                    ->label('Karya Unggulan (Featured)')
                    ->default(false),

                TextInput::make('urutan_tampil')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0),

                Toggle::make('izin_peserta')
                    ->label('Izin Hak Cipta & Publikasi dari Peserta')
                    ->default(false),

                DateTimePicker::make('tanggal_terbit')
                    ->label('Jadwal Tayang'),

                DateTimePicker::make('tanggal_berakhir')
                    ->label('Batas Akhir Tayang'),

                TextInput::make('meta_title')
                    ->label('SEO Meta Title')
                    ->maxLength(70),

                TextInput::make('meta_description')
                    ->label('SEO Meta Description')
                    ->maxLength(160),

                TextInput::make('alasan_penarikan')
                    ->label('Alasan Penarikan (jika ditarik)')
                    ->maxLength(255),
            ]);
    }
}
