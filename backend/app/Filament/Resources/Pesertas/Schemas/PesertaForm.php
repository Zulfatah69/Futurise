<?php

namespace App\Filament\Resources\Pesertas\Schemas;

use App\Models\Peserta;
use App\Models\User;
use App\Rules\ValidParticipantIdentity;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PesertaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Akun Pengguna Terhubung')
                    ->options(fn () => User::query()->orderBy('name')->pluck('email', 'id'))
                    ->searchable()
                    ->nullable(),

                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap / Penanggung Jawab')
                    ->required()
                    ->maxLength(120),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(120),

                TextInput::make('no_telepon')
                    ->label('Nomor Telepon / WhatsApp')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('institusi')
                    ->label('Kampus / Sekolah / Institusi')
                    ->required()
                    ->maxLength(150),

                Select::make('jenis_peserta')
                    ->label('Kategori Peserta')
                    ->options([
                        'pic'       => 'PIC / Koordinator Kampus',
                        'mahasiswa' => 'Mahasiswa',
                        'siswa'     => 'Siswa SMA/Sederajat',
                        'siswa_smk' => 'Siswa SMK',
                        'umum'      => 'Inovator Umum',
                    ])
                    ->default('mahasiswa')
                    ->required(),

                TextInput::make('nomor_identitas')
                    ->label('NIM / NIDN / NISN / NIK')
                    ->maxLength(30)
                    ->rule(fn (Get $get, ?Peserta $record): ValidParticipantIdentity => new ValidParticipantIdentity(
                        $get('jenis_peserta'),
                        $get('institusi'),
                        $record?->id
                    )),
            ]);
    }
}
