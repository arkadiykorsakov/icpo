<?php

namespace App\Filament\Resources\Criminals\RelationManagers;

use App\Models\City;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SightingsRelationManager extends RelationManager
{
    protected static string $relationship = 'sightings';

    protected static ?string $title = 'Города, где замечали преступника';

    protected static ?string $modelLabel = 'город';

    protected static ?string $pluralModelLabel = 'записей';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('city_id')
                    ->label('Город')
                    ->relationship(
                        name: 'city',
                        titleAttribute: 'name',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (City $record) => $record->full_name
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('effective_date')
                    ->label('Дата')
                    ->displayFormat('d.m.Y')
                    ->required()
                    ->maxDate(now()),

                TimePicker::make('effective_time')
                    ->label('Время')
                    ->seconds(false)
                    ->nullable(),

                Textarea::make('description')
                    ->label('Комментарий')
                    ->autosize()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('effective_date', 'desc')
            ->columns([
                TextColumn::make('description')
                    ->label('Комментарий')
                    ->wrap(),

                TextColumn::make('city.full_name')
                    ->label('Город'),

                TextColumn::make('effective_date')
                    ->label('Дата')
                    ->date('d.m.Y'),

                TextColumn::make('effective_time')
                    ->label('Время')
                    ->time('H:i')
                    ->placeholder('Неизвестно'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Добавить город')
                    ->modalHeading('Добавить город'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
