<?php

namespace App\Filament\Resources\Countries\RelationManagers;

use App\Models\City;
use App\Rules\TrimmedNotEmpty;
use App\Rules\UniqueCity;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

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
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->rules(fn () => [
                        new UniqueCity($this->getOwnerRecord()->getKey()),
                        new TrimmedNotEmpty()
                    ])
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
                    ->using(function (array $data, CreateAction $action): \Illuminate\Database\Eloquent\Model {
                        try {
                            return $this->getRelationship()->create($data);
                        } catch (QueryException $exception) {
                            if (
                                $exception->getCode() === '23505'
                                && str_contains(
                                    $exception->getMessage(),
                                    'cities_name_ci'
                                )
                            ) {
                                Notification::make()
                                    ->danger()
                                    ->title('Город уже существует')
                                    ->body('Такой город уже добавлен в эту страну.')
                                    ->send();

                                $action->halt();
                            }

                            throw $exception;
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
