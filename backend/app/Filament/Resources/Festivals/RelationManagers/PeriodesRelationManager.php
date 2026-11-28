<?php

namespace App\Filament\Resources\Festivals\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PeriodesRelationManager extends RelationManager
{
    protected static string $relationship = 'periodes';

    protected static ?string $title = 'Periode & Lini Masa';

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
            TextInput::make('tahap')
                ->label('Nama Tahap / Periode')
                ->required()
                ->maxLength(100),
            DateTimePicker::make('mulai')
                ->label('Tanggal Mulai')
                ->required(),
            DateTimePicker::make('selesai')
                ->label('Tanggal Selesai')
                ->required(),
            Textarea::make('keterangan')
                ->label('Keterangan')
                ->rows(2)
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahap')->label('Tahapan')->searchable()->weight('bold'),
                TextColumn::make('mulai')->label('Mulai')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('selesai')->label('Selesai')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('keterangan')->label('Keterangan')->placeholder('-')->limit(40),
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
