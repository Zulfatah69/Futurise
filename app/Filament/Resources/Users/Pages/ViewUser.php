<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('person.phone')
                            ->label('Nomor Telepon')
                            ->placeholder('-'),

                        TextEntry::make('account_type')
                            ->label('Jenis Akun')
                            ->formatStateUsing(fn ($state) => strtoupper($state ?? '-')),

                        TextEntry::make('pic_category')
                            ->label('Kategori PIC')
                            ->placeholder('-'),

                        TextEntry::make('account_status')
                            ->label('Status Akun'),

                        TextEntry::make('email_verified_at')
                            ->label('Verifikasi Email')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('Belum diverifikasi'),

                        TextEntry::make('created_at')
                            ->label('Tanggal Registrasi')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
