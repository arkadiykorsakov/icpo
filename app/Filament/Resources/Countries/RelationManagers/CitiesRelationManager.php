<?php

namespace App\Filament\Resources\Countries\RelationManagers;

use App\Constants\CityConstraints;
use App\Rules\UniqueCity;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class CitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'cities';

    protected static ?string $title = 'Города';

    protected static ?string $modelLabel = 'город';

    protected static ?string $pluralModelLabel = 'городов';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->label('Название')
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->rules(fn () => [
                        new UniqueCity($this->getOwnerRecord()->getKey()),
                    ])
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Добавить город')
                    ->modalHeading('Добавить город')
                    ->using(function (array $data, CreateAction $action) {
                        try {
                            return $this->getRelationship()->create($data);
                        } catch (QueryException $exception) {
                            $handled = UniqueConstraintHandler::handle($exception, [
                                CityConstraints::LOWER_NAME_COUNTRY_ID_UNIQUE => [
                                    'title' => 'Город уже существует',
                                    'body' => 'Такой город уже добавлен в эту страну.',
                                ],
                            ]);

                            if (! $handled) {
                                throw $exception;
                            }

                            $action->halt();
                        }
                    })
                    ->after(
                        fn () => $this->dispatch('cities-updated')
                    ),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->after(
                        fn() => $this->dispatch('cities-updated')
                    )
            ]);
    }
}
