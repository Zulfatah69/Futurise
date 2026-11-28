<?php

namespace App\Filament\Resources\Karyas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KaryaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('festival_id')
                    ->label('Festival Terkait')
                    ->relationship('festival', 'nama')
                    ->required(),

                Select::make('peserta_id')
                    ->label('Ketua Tim / Submitter')
                    ->relationship('submitter', 'nama_lengkap')
                    ->searchable()
                    ->required(),

                TextInput::make('judul')
                    ->label('Judul Karya / Proyek')
                    ->required()
                    ->maxLength(200)
                    ->columnSpanFull(),

                Select::make('kategori')
                    ->label('Kategori Lomba')
                    ->options([
                        'Web & Mobile App Innovation'           => 'Web & Mobile App Innovation',
                        'Artificial Intelligence & Big Data'    => 'Artificial Intelligence & Big Data',
                        'IoT, Robotics & Embedded Systems'      => 'IoT, Robotics & Embedded Systems',
                        'Game Development & Interactive Media'  => 'Game Development & Interactive Media',
                        'Cybersecurity & Infrastructure'        => 'Cybersecurity & Infrastructure',
                    ])
                    ->required(),

                TextInput::make('teknologi')
                    ->label('Stack Teknologi Utama')
                    ->placeholder('Misal: Python, PyTorch, React, Docker')
                    ->required()
                    ->maxLength(255),

                TextInput::make('url_demo')
                    ->label('Tautan Video / Live Demo')
                    ->url()
                    ->maxLength(255),

                TextInput::make('lisensi')
                    ->label('Lisensi Karya')
                    ->placeholder('Misal: MIT, Apache-2.0, Hak Cipta Terbatas')
                    ->maxLength(50),

                Select::make('status')
                    ->label('Status Kurasi / Lomba')
                    ->options([
                        'draft'             => 'Draft (Peserta)',
                        'diajukan'          => 'Diajukan',
                        'dalam_pemeriksaan' => 'Dalam Pemeriksaan Kurasi',
                        'perlu_revisi'      => 'Perlu Revisi',
                        'lolos'             => 'Lolos Kurasi / Finalis',
                        'tidak_lolos'       => 'Tidak Lolos',
                    ])
                    ->default('draft')
                    ->required(),

                TextInput::make('skor_akhir')
                    ->label('Skor Akhir Penjurian')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                Toggle::make('persetujuan_publikasi')
                    ->label('Disetujui untuk Dipamerkan ke Publik (Showcase Expo)')
                    ->default(false),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Lengkap Karya')
                    ->rows(4)
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('masalah')
                    ->label('Latar Belakang Masalah')
                    ->rows(3),

                Textarea::make('solusi')
                    ->label('Solusi yang Ditawarkan')
                    ->rows(3),

                TextInput::make('pengguna_sasaran')
                    ->label('Pengguna Sasaran')
                    ->maxLength(255),

                TextInput::make('tahap_kesiapan')
                    ->label('Tahap Kesiapan (TRL / Prototipe / Siap Uji)')
                    ->maxLength(100),

                Textarea::make('fitur_utama')
                    ->label('Fitur Utama')
                    ->rows(3),

                Textarea::make('hasil_uji')
                    ->label('Hasil Uji / Validasi')
                    ->rows(3),

                Textarea::make('keterbatasan')
                    ->label('Keterbatasan Saat Ini')
                    ->rows(2),

                Textarea::make('kebutuhan_kolaborasi')
                    ->label('Kebutuhan Kolaborasi / Tindak Lanjut')
                    ->rows(2),

                Textarea::make('catatan_kurasi')
                    ->label('Catatan Kurator / Alasan Revisi')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
