<?php

namespace App\Filament\Resources\OffenseCategories;

use App\Filament\Resources\OffenseCategories\Pages\CreateOffenseCategory;
use App\Filament\Resources\OffenseCategories\Pages\ListOffenseCategories;
use App\Filament\Resources\OffenseCategories\Schemas\OffenseCategoryForm;
use App\Filament\Resources\OffenseCategories\Tables\OffenseCategoriesTable;
use App\Models\OffenseCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OffenseCategoryResource extends Resource
{
    protected static ?string $model = OffenseCategory::class;

    protected static ?string $breadcrumb = 'Категории преступлений';

    protected static string|null|\UnitEnum $navigationGroup = 'Словари';

    protected static ?string $navigationLabel = 'Категории преступлений';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::LockClosed;

    public static function form(Schema $schema): Schema
    {
        return OffenseCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OffenseCategoriesTable::configure($table);
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
            'index' => ListOffenseCategories::route('/'),
            'create' => CreateOffenseCategory::route('/create'),
        ];
    }
}
