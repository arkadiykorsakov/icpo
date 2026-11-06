<?php

namespace App\Filament\Resources\Languages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LanguagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->searchable()->sortable(),
                TextColumn::make('code')->label('Код языка по BCP 47'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
                    ->modalHeading(fn ($record) => "Удалить запись: {$record->name}?")
            ])
            ->toolbarActions([
                //
            ]);
    }
}
