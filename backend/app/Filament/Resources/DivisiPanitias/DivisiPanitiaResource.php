<?php

namespace App\Filament\Resources\DivisiPanitias;

use App\Filament\Resources\DivisiPanitias\Pages\CreateDivisiPanitia;
use App\Filament\Resources\DivisiPanitias\Pages\EditDivisiPanitia;
use App\Filament\Resources\DivisiPanitias\Pages\ListDivisiPanitias;
use App\Filament\Resources\DivisiPanitias\Schemas\DivisiPanitiaForm;
use App\Filament\Resources\DivisiPanitias\Tables\DivisiPanitiasTable;
use App\Models\DivisiPanitia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DivisiPanitiaResource extends Resource
{
    protected static ?string $model = DivisiPanitia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Divisi Kepanitiaan';

    protected static ?string $modelLabel = 'Divisi';

    protected static ?string $pluralModelLabel = 'Divisi & Bidang Kerja';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DivisiPanitiaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DivisiPanitiasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDivisiPanitias::route('/'),
            'create' => CreateDivisiPanitia::route('/create'),
            'edit'   => EditDivisiPanitia::route('/{record}/edit'),
        ];
    }
}
