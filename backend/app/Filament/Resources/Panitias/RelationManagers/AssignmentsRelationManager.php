<?php

namespace App\Filament\Resources\Panitias\RelationManagers;

use App\Models\DivisiPanitia;
use App\Models\Festival;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'festivalAssignments';

    protected static ?string $title = 'Penugasan Festival & Divisi';

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
            Select::make('festival_id')
                ->label('Edisi Festival')
                ->options(fn () => Festival::pluck('nama', 'id'))
                ->required()
                ->searchable(),
            Select::make('divisi_id')
                ->label('Divisi Kepanitiaan')
                ->options(fn () => DivisiPanitia::pluck('nama', 'id'))
                ->searchable(),
            Select::make('jabatan')
                ->label('Jabatan')
                ->options([
                    'ketua'       => 'Ketua Pelaksana',
                    'wakil_ketua' => 'Wakil Ketua',
                    'sekretaris'  => 'Sekretaris',
                    'bendahara'   => 'Bendahara',
                    'koordinator' => 'Koordinator Divisi',
                    'anggota'     => 'Anggota Divisi',
                    'relawan'     => 'Relawan',
                ])
                ->default('anggota')
                ->required(),
            Select::make('status')
                ->label('Status Penugasan')
                ->options([
                    'mendaftar' => 'Mendaftar',
                    'diterima'  => 'Diterima',
                    'aktif'     => 'Aktif',
                    'nonaktif'  => 'Nonaktif',
                    'ditolak'   => 'Ditolak',
                    'mundur'    => 'Mundur',
                ])
                ->default('aktif')
                ->required(),
            DatePicker::make('tanggal_bergabung')
                ->label('Tanggal Bergabung')
                ->default(now()),
            Textarea::make('catatan')
                ->label('Catatan')
                ->rows(2)
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('festival.nama')->label('Festival')->badge(),
                TextColumn::make('divisi.nama')->label('Divisi')->placeholder('-'),
                TextColumn::make('jabatan')->label('Jabatan')->badge(),
                TextColumn::make('status')->label('Status')->badge(),
                TextColumn::make('tanggal_bergabung')->label('Bergabung')->date('d M Y'),
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
