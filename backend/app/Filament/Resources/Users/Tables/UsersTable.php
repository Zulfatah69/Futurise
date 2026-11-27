<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->url(fn (User $record): string =>
                        route('filament.admin.resources.users.view', [
                            'record' => $record,
                        ])
                    )
                    ->color('primary')
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('peserta.institusi')
                    ->label('Asal Kampus / Institusi')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('peserta.no_telepon')
                    ->label('No. HP')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('account_type')
                    ->label('Jenis Akun')
                    ->badge(),

                TextColumn::make('pic_category')
                    ->label('Kategori PIC')
                    ->badge(),

                TextColumn::make('account_status')
                    ->label('Status Akun')
                    ->badge(),

                TextColumn::make('email_verified_at')
                    ->label('Verifikasi Email')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable(),

            ])
            ->filters([])
            ->recordActions([
                Action::make('approvePic')
                    ->label('Setujui PIC')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize('update')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui akun PIC?')
                    ->modalDescription('Akun PIC akan diaktifkan setelah persetujuan.')
                    ->visible(fn (User $record): bool =>
                        $record->account_type === 'pic'
                        && $record->account_status === 'pending_approval'
                        && $record->hasVerifiedEmail()
                    )
                    ->action(fn (User $record) =>
                        $record->update([
                            'account_status' => 'active',
                        ])
                    ),

                Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->authorize('update')
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan akun?')
                    ->modalDescription('Pengguna tidak akan bisa login selama akun dinonaktifkan.')
                    ->visible(fn (User $record): bool =>
                        in_array($record->account_type, ['pic', 'public'], true)
                        && $record->account_status === 'active'
                        && $record->id !== auth()->id()
                    )
                    ->action(fn (User $record) =>
                        $record->update([
                            'account_status' => 'inactive',
                        ])
                    ),

                Action::make('activate')
                    ->label('Aktifkan Kembali')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize('update')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan kembali akun?')
                    ->modalDescription('Pengguna akan dapat login kembali jika emailnya sudah diverifikasi.')
                    ->visible(fn (User $record): bool =>
                        in_array($record->account_type, ['pic', 'public'], true)
                        && $record->account_status === 'inactive'
                        && $record->id !== auth()->id()
                    )
                    ->action(fn (User $record) =>
                        $record->update([
                            'account_status' => 'active',
                        ])
                    ),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
