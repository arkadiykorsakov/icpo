<?php

namespace App\Filament\Resources\Countries\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountriesTable
{
    protected static ?string $recordTitleAttribute = 'name';

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->searchable()->sortable(),
                TextColumn::make('iso2')->label('ISO2')->searchable(),
                TextColumn::make('iso3')->label('ISO3'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
                    ->modalHeading(fn ($record) => "Удалить запись: {$record->name}?")
                    ->disabled(fn ($record) => $record->cities()->exists())
                    ->tooltip(fn ($record) =>
                        $record->cities()->exists()
                            ? 'Сначала удалите все города'
                            : null
                    ),
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
