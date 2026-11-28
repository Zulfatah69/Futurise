<?php

namespace App\Filament\Resources\Festivals\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class KategorisRelationManager extends RelationManager
{
    protected static string $relationship = 'kategoris';

    protected static ?string $title = 'Kategori Lomba';

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
            TextInput::make('nama')
                ->label('Nama Kategori / Bidang')
                ->required()
                ->maxLength(100),
            TextInput::make('kuota')
                ->label('Kuota Karya')
                ->numeric()
                ->minValue(1),
            TextInput::make('urutan')
                ->label('Urutan Tampil')
                ->numeric()
                ->default(0),
            Textarea::make('deskripsi')
                ->label('Deskripsi Kategori')
                ->rows(2)
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->label('#')->sortable(),
                TextColumn::make('nama')->label('Nama Kategori')->searchable()->weight('bold'),
                TextColumn::make('kuota')->label('Kuota')->badge()->placeholder('Tanpa batas'),
                TextColumn::make('deskripsi')->label('Deskripsi')->placeholder('-')->limit(45),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
