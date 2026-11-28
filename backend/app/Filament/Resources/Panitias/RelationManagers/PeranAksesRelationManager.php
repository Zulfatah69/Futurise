<?php

namespace App\Filament\Resources\Panitias\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PeranAksesRelationManager extends RelationManager
{
    protected static string $relationship = 'peranAkses';

    protected static ?string $title = 'Peran Akses Kepanitiaan';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return (bool) auth()->user()?->can('view', $ownerRecord);
    }

    public function isReadOnly(): bool
    {
        return ! (bool) auth()->user()?->can('update', $this->getOwnerRecord());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('kode')->label('Kode')->badge(),
                TextColumn::make('nama')->label('Nama Peran')->weight('bold'),
                TextColumn::make('deskripsi')->label('Deskripsi')->placeholder('-')->limit(45),
            ])
            ->headerActions([
                AttachAction::make()->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make(),
            ]);
    }
}
