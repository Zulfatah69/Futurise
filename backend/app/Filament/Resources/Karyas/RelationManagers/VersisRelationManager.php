<?php

namespace App\Filament\Resources\Karyas\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VersisRelationManager extends RelationManager
{
    protected static string $relationship = 'versis';

    protected static ?string $title = 'Riwayat Revisi (BR-09)';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return (bool) auth()->user()?->can('view', $ownerRecord);
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('versi')->label('Versi')->badge()->sortable(),
                TextColumn::make('status_lama')->label('Status Lama')->placeholder('-'),
                TextColumn::make('status_baru')->label('Status Baru')->badge(),
                TextColumn::make('catatan')->label('Catatan Perubahan / Kurasi')->wrap(),
                TextColumn::make('updater.name')->label('Diubah Oleh')->placeholder('Sistem'),
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
            ]);
    }
}
