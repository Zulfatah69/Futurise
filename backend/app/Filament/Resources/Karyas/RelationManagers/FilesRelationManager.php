<?php

namespace App\Filament\Resources\Karyas\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    protected static ?string $title = 'Berkas Lampiran';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return (bool) auth()->user()?->can('view', $ownerRecord);
    }

    public function isReadOnly(): bool
    {
        return ! (bool) auth()->user()?->can('update', $this->getOwnerRecord());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_asli')->label('Nama Berkas')->disabled(),
            TextInput::make('jenis')->label('Jenis')->disabled(),
            TextInput::make('keterangan')->label('Keterangan')->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_asli')->label('Nama Berkas')->searchable()->weight('bold'),
                TextColumn::make('jenis')->label('Jenis')->badge(),
                TextColumn::make('mime_type')->label('Tipe')->placeholder('-'),
                TextColumn::make('ukuran_byte')
                    ->label('Ukuran')
                    ->formatStateUsing(fn (?int $state): string => $state ? number_format($state / 1024, 1) . ' KB' : '-'),
                IconColumn::make('is_publik')->label('Publik')->boolean(),
                TextColumn::make('keterangan')->label('Keterangan')->placeholder('-'),
                TextColumn::make('path')
                    ->label('Unduh')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Buka Berkas' : '-')
                    ->url(fn ($record): ?string => $record->path ? asset('storage/' . ltrim($record->path, '/')) : null, shouldOpenInNewTab: true),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }
}
