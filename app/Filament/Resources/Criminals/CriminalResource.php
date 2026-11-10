<?php

namespace App\Filament\Resources\Criminals;

use App\Filament\Resources\Criminals\Pages\CreateCriminal;
use App\Filament\Resources\Criminals\Pages\EditCriminal;
use App\Filament\Resources\Criminals\Pages\ListCriminals;
use App\Filament\Resources\Criminals\Pages\ViewCriminal;
use App\Filament\Resources\Criminals\RelationManagers\SightingsRelationManager;
use App\Filament\Resources\Criminals\RelationManagers\SpokenLanguagesRelationManager;
use App\Filament\Resources\Criminals\Schemas\CriminalForm;
use App\Filament\Resources\Criminals\Tables\CriminalsTable;
use App\Models\Criminal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CriminalResource extends Resource
{
    protected static ?string $model = Criminal::class;

    protected static ?string $pluralModelLabel = 'Преступники';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::FingerPrint;

    public static function form(Schema $schema): Schema
    {
        return CriminalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CriminalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SightingsRelationManager::class,
            SpokenLanguagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCriminals::route('/'),
            'create' => CreateCriminal::route('/create'),
            'view' => ViewCriminal::route('/{record}'),
            'edit' => EditCriminal::route('/{record}/edit'),
        ];
    }
}
