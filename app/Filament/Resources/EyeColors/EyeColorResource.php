<?php

namespace App\Filament\Resources\EyeColors;

use App\Filament\Resources\EyeColors\Pages\CreateEyeColor;
use App\Filament\Resources\EyeColors\Pages\ListEyeColors;
use App\Filament\Resources\EyeColors\Schemas\EyeColorForm;
use App\Filament\Resources\EyeColors\Tables\EyeColorsTable;
use App\Models\EyeColor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EyeColorResource extends Resource
{
    protected static ?string $model = EyeColor::class;
    protected static ?string $breadcrumb = 'Цвет глаз';

    protected static string|null|\UnitEnum $navigationGroup = 'Справочники';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Swatch;

    protected static ?string $navigationLabel = 'Цвет глаз';

    public static function form(Schema $schema): Schema
    {
        return EyeColorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EyeColorsTable::configure($table);
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
            'index' => ListEyeColors::route('/'),
            'create' => CreateEyeColor::route('/create'),
        ];
    }
}
