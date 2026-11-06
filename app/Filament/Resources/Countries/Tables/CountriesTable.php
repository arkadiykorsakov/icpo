<?php

namespace App\Filament\Resources\Countries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountriesTable
{
    protected static ?string $recordTitleAttribute = 'name';

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->label('Название')->searchable(),
                TextColumn::make('iso2')->label('ISO2'),
                TextColumn::make('iso3')->label('ISO3'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()
                    ->modalHeading(fn ($record) => "Удалить запись: {$record->name}?"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
