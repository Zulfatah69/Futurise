<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                Select::make('account_type')
                    ->label('Tipe Akun')
                    ->options([
                        'pic' => 'PIC',
                        'public' => 'Public',
                    ])
                    ->required(),
                Select::make('pic_category')
                    ->label('Kategori PIC')
                    ->options([
                        'kampus-sekolah' => 'Kampus / Sekolah',
                        'industri-sponsor' => 'Industri / Sponsor',
                        'publikasi' => 'Publikasi',
                        'registrasi-layanan' => 'Registrasi / Layanan',
                    ]),
                Select::make('account_status')
                    ->label('Status Akun')
                    ->options([
                        'active' => 'Active',
                        'pending_approval' => 'Pending Approval',
                        'inactive' => 'Inactive',
                    ])
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Diverifikasi Pada'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }
}
