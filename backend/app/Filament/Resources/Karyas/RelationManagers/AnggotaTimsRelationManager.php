<?php

namespace App\Filament\Resources\Karyas\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AnggotaTimsRelationManager extends RelationManager
{
    protected static string $relationship = 'anggotaTims';

    protected static ?string $title = 'Anggota Tim';

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
            TextInput::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(100),
            TextInput::make('peran')
                ->label('Peran dalam Tim')
                ->maxLength(50),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(100),
            TextInput::make('no_telepon')
                ->label('No. Telepon')
                ->maxLength(20),
            TextInput::make('nomor_identitas')
                ->label('NIM / NISN')
                ->maxLength(30),
            Toggle::make('is_ketua')
                ->label('Ketua Tim')
                ->default(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->label('#')->sortable(),
                TextColumn::make('nama_lengkap')->label('Nama Lengkap')->searchable()->weight('bold'),
                TextColumn::make('peran')->label('Peran')->badge(),
                IconColumn::make('is_ketua')->label('Ketua')->boolean(),
                TextColumn::make('email')->label('Email')->placeholder('-'),
                TextColumn::make('nomor_identitas')->label('Identitas')->placeholder('-'),
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
