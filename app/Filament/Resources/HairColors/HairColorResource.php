<?php

namespace App\Filament\Resources\HairColors;

use App\Filament\Resources\HairColors\Pages\CreateHairColor;
use App\Filament\Resources\HairColors\Pages\ListHairColors;
use App\Filament\Resources\HairColors\Schemas\HairColorForm;
use App\Filament\Resources\HairColors\Tables\HairColorsTable;
use App\Models\HairColor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HairColorResource extends Resource
{
    protected static ?string $model = HairColor::class;

    protected static ?string $breadcrumb = 'Цвет волос';

    protected static string|null|\UnitEnum $navigationGroup = 'Справочники';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Swatch;

    protected static ?string $navigationLabel = 'Цвет волос';

    public static function form(Schema $schema): Schema
    {
        return HairColorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HairColorsTable::configure($table);
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
            'index' => ListHairColors::route('/'),
            'create' => CreateHairColor::route('/create'),
        ];
    }
}
