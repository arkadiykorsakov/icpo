<?php

namespace App\Filament\Resources\EyeColors\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EyeColorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
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
