<?php

namespace App\Filament\Resources\OffenseCategories\Tables;

use App\Models\OffenseCategory;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OffenseCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->description(fn (OffenseCategory $record): ?string => $record->description)
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
                    ->modalHeading(fn ($record) => "Удалить запись: {$record->name}?"),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
