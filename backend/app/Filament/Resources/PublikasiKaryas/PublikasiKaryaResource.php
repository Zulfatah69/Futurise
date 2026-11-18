<?php

namespace App\Filament\Resources\PublikasiKaryas;

use App\Filament\Resources\PublikasiKaryas\Pages\CreatePublikasiKarya;
use App\Filament\Resources\PublikasiKaryas\Pages\EditPublikasiKarya;
use App\Filament\Resources\PublikasiKaryas\Pages\ListPublikasiKaryas;
use App\Filament\Resources\PublikasiKaryas\Schemas\PublikasiKaryaForm;
use App\Filament\Resources\PublikasiKaryas\Tables\PublikasiKaryasTable;
use App\Models\PublikasiKarya;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PublikasiKaryaResource extends Resource
{
    protected static ?string $model = PublikasiKarya::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Publikasi & Showcase';

    protected static ?string $modelLabel = 'Publikasi Karya';

    protected static ?string $pluralModelLabel = 'Katalog Showcase & Publikasi';

    protected static ?string $recordTitleAttribute = 'judul_publik';

    public static function form(Schema $schema): Schema
    {
        return PublikasiKaryaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublikasiKaryasTable::configure($table);
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
            'index'  => ListPublikasiKaryas::route('/'),
            'create' => CreatePublikasiKarya::route('/create'),
            'edit'   => EditPublikasiKarya::route('/{record}/edit'),
        ];
    }
}
