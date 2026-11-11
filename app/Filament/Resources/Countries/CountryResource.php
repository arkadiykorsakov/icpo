<?php

namespace App\Filament\Resources\Countries;

use App\Filament\Resources\Countries\Pages\CreateCountry;
use App\Filament\Resources\Countries\Pages\ListCountries;
use App\Filament\Resources\Countries\Pages\ViewCountry;
use App\Filament\Resources\Countries\RelationManagers\CitiesRelationManager;
use App\Filament\Resources\Countries\Schemas\CountryForm;
use App\Filament\Resources\Countries\Tables\CountriesTable;
use App\Models\Country;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;
    protected static ?string $breadcrumb = 'Страны';
    protected static ?string $navigationLabel = 'Страны';
    protected static ?string $pluralModelLabel = 'стран';
    protected static string|null|\UnitEnum $navigationGroup = 'Справочники';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    public static function form(Schema $schema): Schema
    {
        return CountryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CountriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'cities' => CitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'view' => ViewCountry::route('/{record}'),
        ];
    }
}
